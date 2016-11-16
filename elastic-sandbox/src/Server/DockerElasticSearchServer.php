<?php
namespace ElasticSandbox\Server;

use Clue\React\Docker\Factory as DockerClientFactory;
use React\EventLoop\Factory as EventLoopFactory;
use GuzzleHttp\Client as HttpClient;
use Clue\React\Buzz\Message\ResponseException;
use function Clue\React\Block\await;
use Elasticsearch\ClientBuilder as ElasticSearchClientBuilder;
use Elasticsearch\Client as ElasticSearchClient;
use GuzzleHttp\Exception\RequestException;

class DockerElasticSearchServer
{
    const ES_IMAGE = 'elasticsearch';

    /**
     * @var React\EventLoop\LoopInterface
     */
    protected $eventLoop;

    /**
     * @var Clue\React\Docker\Client
     */
    protected $dockerClient;

    /**
     * @var GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var ElasticSearch\Client
     */
    protected $elasticSearchClient;

    /**
     * Host
     *
     * @var string
     */
    protected $host;

    /**
     * Port
     *
     * @var string
     */
    protected $port;

    /**
     * Indicates if the server is ready
     *
     * @var boolean
     */
    protected $ready = false;

    /**
     * @var array
     */
    protected $currentContainerData;

    /**
     * Console output
     *
     * @var Callable
     */
    protected $output;

    /**
     * Creates a new instance of DockerElasticSearchServer
     *
     * @param array $settings Settings
     */
    public function __construct(array $settings)
    {
        $settings += [
            'appName' => null,
            'configPath' => null,
            'dataPathBase' => null,
            'port' => null
        ];

        $this->appName = $settings['appName'] ?: 'app';
        $this->host = 'localhost';
        $this->port = $settings['port'] ?: '9200';
        $this->dataPathBase = $settings['dataPathBase'];
        $this->configPath = $settings['configPath'];

        $this->eventLoop = EventLoopFactory::create();
        $dockerFactory = new DockerClientFactory($this->eventLoop);
        $this->dockerClient = $dockerFactory->createClient();
        $this->httpClient = new HttpClient();
    }

    /**
     * Sets an output handler
     *
     * @param Callable $output Output handler
     */
    public function setOutput(Callable $output)
    {
        $this->output = $output;
    }

    /**
     * Prints a message in the output handler
     *
     * @param string $message Message
     * @param boolean $newline Print line
     */
    protected function out($message, $newline = true)
    {
        if ($this->output) {
            $output = $this->output;

            $output($message, $newline);
        }
    }

    /**
     * Docker container name
     *
     * @return string
     */
    protected function containerName()
    {
        static $name;

        if (!$name) {
            $name = 'elastic-sandbox_' . $this->appName;
        }

        return $name;
    }

    /**
     * Host
     *
     * @return string
     */
    public function host()
    {
        return $this->host;
    }

    /**
     * Port
     *
     * @return string
     */
    public function port()
    {
        return $this->port;
    }

    /**
     * Complete server address in form host:port
     *
     * @return string
     */
    public function address()
    {
        return "{$this->host()}:{$this->port()}";
    }

    /**
     * Returns the host data full path
     *
     * @return string
     */
    public function dataPath()
    {
        static $dataPath;

        if (!$dataPath) {
            $dataPath = $this->dataPathBase . '/elastic-sandbox_' . $this->appName;
        }

        return $dataPath;
    }

    /**
     * ElasticSearch configuration path
     *
     * @return string
     */
    public function configPath()
    {
        return $this->configPath;
    }

    /**
     * Backup base path in host
     *
     * @return string
     */
    public function backupPath()
    {
        return $this->dataPath() . '/backup';
    }

    /**
     * Get client associated to this server
     *
     * @return ElasticSearch\Client
     */
    public function getClient()
    {
        if (!$this->elasticSearchClient) {
            $this->prepare();

            if (class_exists(ElasticSearchClientBuilder::class)) {
                $this->elasticSearchClient = ElasticSearchClientBuilder::create()
                    ->setHosts([$this->address()])
                    ->build();
            } else {
                $this->elasticSearchClient = new ElasticSearchClient([
                    'hosts' => [$this->address()]
                ]);
            }
        }

        return $this->elasticSearchClient;
    }

    /**
     * Prepares a elastic search server container
     *
     * @return array Container data
     */
    public function prepare()
    {
        if ($this->ready) {
            return $this->currentContainerData;
        }

        $container = $this->containerData($this->containerName());

        $needsToBeRecreated = !$container || !$this->isWellConfigured($container);

        if ($needsToBeRecreated) {
            $this->out('Needs to be recreated');

            if ($container) {
                $this->removeContainer();
            }

            $this->createContainer();
        }

        $containerData = $this->containerData($this->containerName());

        if ($containerData['State']['Running']) {
            $container = $containerData;
        } else {
            $container = $this->startContainer($containerData['Id']);
        }

        $this->ready = true;
        $this->currentContainerData = $container;
        return $container;
    }

    /**
     * Removes the container associated with this server
     *
     * @return void
     */
    protected function removeContainer()
    {
        $promise = $this->dockerClient->containerRemove($this->containerName(), true, true);
        await($promise, $this->eventLoop);
    }

    /**
     * Creates a container
     *
     * @return void
     */
    protected function createContainer()
    {
        $containerConfig = [
            'Image' => static::ES_IMAGE . ':latest',
            'ExposedPorts' => [
                "9200/tcp" => null
            ],
            'Volumes' => [
                '/usr/share/elasticsearch/data' => null,
                '/mount/backups' => null,
                '/mount/config' => null
            ],
            'Cmd' => [
                '--cluster.name=elasticsandbox',
                '--path.repo=/mount/backups/'
            ]
        ];

        $promise = $this->dockerClient->containerCreate($containerConfig, $this->containerName());
        $results = await($promise, $this->eventLoop);
    }

    /**
     * Starts a container by its id
     *
     * @param string $containerId
     *
     * @return array Start result
     */
    protected function startContainer($containerId)
    {
        $containerId = (string) $containerId;

        $promise = $this->dockerClient->containerStart($containerId, [
            "PortBindings" => [
                "9200/tcp" => [
                    ["HostPort" => (string) $this->port()]
                ]
            ],
            "Binds" => [
                $this->dataPath() . ':/usr/share/elasticsearch/data',
                $this->backupPath() . ':/mount/backups',
                $this->configPath() . ':/mount/config'
            ],
        ]);

        $result = await($promise, $this->eventLoop);

        $isAlive = function() {
            static $existRequestMethod;

            if (is_null($existRequestMethod)) {
                $existRequestMethod = method_exists($this->httpClient, 'request');
            }

            try {
                if ($existRequestMethod) {
                    $this->httpClient->request('GET', 'http://localhost:' . $this->port());
                } else {
                    $this->httpClient->get('http://localhost:' . $this->port());
                }

                return true;
            } catch (RequestException $e) {
                return false;
            }
        };

        $this->out('Waiting for node alive...', false);
        while (!$isAlive()) {
            $this->out('.', false);
            usleep(500 * 1000);
        }
        $this->out('', true);

        return $result;
    }

    /**
     * Checks if the container is well configured, comparing against settings
     *
     * @param array $containerData Container data
     *
     * @return boolean
     */
    protected function isWellConfigured(array $containerData)
    {
        $localPort = $this->port();

        if (
            ($localPort && empty($containerData['HostConfig']['PortBindings']['9200/tcp'])) ||
            ($localPort && $localPort != $containerData['HostConfig']['PortBindings']['9200/tcp'][0]['HostPort']) ||
            (!$localPort && !empty($containerData['HostConfig']['PortBindings']['9200/tcp']))
        ) {
            return false;
        }

        //Check mounted volumes
        $backupVolumeEntry = "{$this->backupPath()}:/mount/backups";
        $dataVolumeEntry = "{$this->dataPath()}:/usr/share/elasticsearch/data";
        $mounts = $containerData['HostConfig']['Binds'];

        if (
            (count($mounts) != 3) ||
            !in_array($backupVolumeEntry, $mounts) ||
            !in_array($dataVolumeEntry, $mounts)
        ) {
            return false;
        }

        //All checks passed, then it is well configured
        return true;
    }

    /**
     * Get information about a container
     *
     * @return array
     */
    protected function containerData($containerName)
    {
        try {
            $promise = $this->dockerClient->containerInspect((string) $containerName);

            $results = await($promise, $this->eventLoop);

            return $results;
        } catch (ResponseException $e) {
            if ($e->getResponse()->getCode() == 404) {
                return null;
            } else {
                throw $e;
            }
        }
    }
}