<?php
namespace ElasticSandbox;

use InvalidArgumentException;
use Elasticsearch\ClientBuilder;
use ElasticSandbox\Fixtures\FixtureLoader;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use ElasticSandbox\Server\DockerElasticSearchServer;

/**
 * ElasticSearch sandbox for testing purposes
 *
 * @author Carlos Frutos <carlos@kiwing.it>
 */
class ElasticSandbox
{
    /**
     * @var ElasticSandbox\Fixtures\FixtureLoader
     */
    protected $fixtureLoader;

    /**
     * @var Elasticsearch\Client
     */
    protected $elasticSearchClient;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var array
     */
    protected $typeMappings;

    /**
     * @var ElasticSandbox\Server\DockerElasticSearchServer
     */
    protected $elasticSearchServer;

    /**
     * Is booted?
     *
     * @var boolean
     */
    protected $booted = false;

    /**
     * Creates a new Sandbox
     *
     * @param DockerElasticSearchServer $elasticSearchServer Docker based server
     * @param array $settings Initialization settings
     * @param array $typeMappings Mappings
     */
    public function __construct(DockerElasticSearchServer $elasticSearchServer, array $settings, array $typeMappings)
    {
        $this->elasticSearchServer = $elasticSearchServer;

        $this->settings = $settings + [
            'appName' => 'app',
            'indexName' => 'index',
            'fixturesPath' => (string) null
        ];

        $this->typeMappings = $typeMappings;
    }

    /**
     * Index name
     *
     * @return string
     */
    protected function indexName()
    {
        return $this->settings['indexName'];
    }

    /**
     * Backup repository name
     *
     * @return string
     */
    protected function repositoryName()
    {
        return $this->settings['appName'] . '_backup';
    }

    /**
     * Backup snapshot name based on current version
     *
     * @return string
     */
    protected function currentSnapshotName()
    {
        return "snapshot_{$this->settings['appName']}_{$this->indexName()}_{$this->version()}";
    }

    /**
     * Boot the sandbox
     *
     * @param boolean $force Force to recreate indices and data
     *
     * @return void
     */
    public function boot($force = false)
    {
        $force = (boolean) $force;

        $this->elasticSearchClient = $this->elasticSearchServer->getClient();

        $this->fixtureLoader = new FixtureLoader($this->elasticSearchClient);
        $this->fixtureLoader->loadFromDirectory($this->settings['fixturesPath']);

        if ($this->snapshotExists($this->currentSnapshotName()) && !$force) {
            $this->restoreBackup();
        } else {
            $this->recreateIndex();
            $this->doAllMappings();
            $this->loadData();
            $this->saveBackup();
        }

        $this->booted = true;
    }

    /**
     * Back the ES Sandbox to the initial state
     *
     * @param boolean $force Force to recreate indices and data
     *
     * @return void
     */
    public function reboot($force = false)
    {
        $force = (boolean) $force;

        if (!$this->booted || $force) {
            $this->boot($force);
        } else {
            $this->restoreBackup();
        }
    }

    /**
     * Recreates the index
     *
     * @return void
     */
    protected function recreateIndex()
    {
        $indexName = $this->indexName();
        $client = $this->elasticSearchClient;

        if ($client->indices()->exists(['index' => $indexName])) {
            $client->indices()->delete(['index' => $indexName]);
        }

        $client->indices()->create([
            'index' => $indexName,
            'body' => [
                'index' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0
                ]
            ]
        ]);
    }

    /**
     * Put all mappings
     *
     * @return void
     */
    protected function doAllMappings()
    {
        foreach ($this->typeMappings as $type => $properties) {
            $params = [];
            $params['index'] = $this->indexName();
            $params['type']  = $type;

            $mapping = [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => $properties
            ];

            $params['body'][$type] = $mapping;

            $this->elasticSearchClient->indices()->putMapping($params);
        }
    }

    /**
     * Load data from fixture loader
     *
     * @return void
     */
    protected function loadData()
    {
        foreach ($this->fixtureLoader->getFixtures() as $fixture) {
            $fixture->setIndex($this->indexName());
        }

        $this->fixtureLoader->executeAll();
    }

    /**
     * Save backup snapshot with current data and indices
     *
     * @return void
     */
    protected function saveBackup()
    {
        $client = $this->elasticSearchClient;

        if (!$this->repositoryExists($this->repositoryName())) {
            $client->snapshot()->createRepository([
                'repository' => $this->repositoryName(),
                'body' => [
                    'type' => 'fs',
                    'settings' => [
                        'location' => '/mount/backups/' . $this->repositoryName(),
                        'compress' => false
                    ]
                ]
            ]);
        }

        $this->removeSnapshotIfExists($this->currentSnapshotName());

        $client->snapshot()->create([
            'repository' => $this->repositoryName(),
            'snapshot' => $this->currentSnapshotName(),
            'wait_for_completion' => true,
            'body' => [
                'indices' => $this->indexName(),
            ]
        ]);
    }

    /**
     * Restores the initial snapshot
     *
     * @return void
     */
    protected function restoreBackup()
    {
        $client = $this->elasticSearchClient;

        $client->indices()->close(['index' => $this->indexName()]);

        $client->snapshot()->restore([
            'repository' => $this->repositoryName(),
            'snapshot' => $this->currentSnapshotName(),
            'body' => [
                'indices' => $this->indexName(),
            ]
        ]);

        $client->indices()->open(['index' => $this->indexName()]);

        while (!$this->indexIsOpen()) {
            usleep(5000);
        }

        usleep(1000 * 5);
    }

    /**
     * Check if the index is open
     */
    public function indexIsOpen()
    {
        if (!$this->elasticSearchClient->ping()) {
            return false;
        }

        $r = explode(' ', $this->elasticSearchClient->cat()->indices(['index' => $this->indexName()]));

        return !empty($r) && (count($r) >= 2) & ($r[1] === 'open');
    }

    /**
     * Check if a backup repository exists
     *
     * @param string $name Backup repository name
     *
     * @return boolean
     */
    protected function repositoryExists($name)
    {
        try {
            $this->elasticSearchClient->snapshot()->getRepository(['repository' => $name]);

            return true;
        } catch (Missing404Exception $e) {
            return false;
        }
    }

    /**
     * Check if a snapshot exists
     *
     * @param string $snapshotName Snapshot name
     *
     * @return boolean
     */
    protected function snapshotExists($snapshotName)
    {
        try {
            $this->elasticSearchClient->snapshot()->get([
                'repository' => $this->repositoryName(),
                'snapshot' => $snapshotName
            ]);

            return true;
        } catch (Missing404Exception $e) {
            return false;
        }
    }

    /**
     * Remove a snapshot if it exists
     *
     * @param string $snapshotName Snapshot name
     *
     * @return void
     */
    protected function removeSnapshotIfExists($snapshotName)
    {
        if ($this->snapshotExists($snapshotName)) {
            $this->elasticSearchClient->snapshot()->delete([
                'repository' => $this->repositoryName(),
                'snapshot' => $snapshotName
            ]);
        }
    }

    /**
     * Version hash of current state (Fixtures + Mapping)
     *
     * @throws InvalidArgumentException Without fixture loader
     *
     * @return string Version hash
     */
    public function version()
    {
        if (!$this->fixtureLoader) {
            throw new InvalidArgumentException('Can\'t calculate version hash without a fixture loader');
        }

        static $version;

        if (!$version)
        {
            $fixturesVersions = array_map(function($fixture) {
                return $fixture->version();
            }, $this->fixtureLoader->getFixtures());

            $fixtureTotalVersion = md5(serialize($fixturesVersions));

            $mappingVersion = md5(serialize($this->typeMappings));

            $version = md5($fixtureTotalVersion . $mappingVersion);
        }

        return $version;
    }
}