<?php
namespace ElasticSandbox\Fixtures;

use ElasticSearch\Client as ElasticSearchClient;

class FixtureLoader
{
    /**
     * @var ElasticSearchClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $fixtures = [];

    /**
     * The file extension of fixture files.
     *
     * @var string
     */
    private $fileExtension = '.php';

    public function __construct(ElasticSearchClient $client)
    {
        $this->client = $client;
    }

    public function addFixture(AbstractFixture $fixture)
    {
        $this->fixtures[] = $fixture;
    }

    /**
     * @return array
     */
    public function getFixtures()
    {
        return $this->fixtures;
    }

    public function getVersion()
    {
        return md5(array_reduce($this->getFixtures(), function($carry, AbstractFixture $current) {
            return $current->version();
        }), '');
    }

    public function executeAll()
    {
        foreach ($this->fixtures as $fixture) {
            $fixture->load($this->client);
        }
    }

    /**
     * Check if a given fixture is transient and should not be considered a data fixtures
     * class.
     *
     * @return boolean
     */
    public function isTransient($className)
    {
        $rc = new \ReflectionClass($className);
        if ($rc->isAbstract()) return true;
        $parents = class_parents($className);
        return in_array(AbstractFixture::class, $parents) ? false : true;
    }

    /**
     * Find fixtures classes in a given directory and load them.
     *
     * @param string $dir Directory to find fixture classes in.
     * @see https://github.com/doctrine/data-fixtures
     * @return array $fixtures Array of loaded fixture object instances.
     */
    public function loadFromDirectory($dir)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException(sprintf('"%s" does not exist', $dir));
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        return $this->loadFromIterator($iterator);
    }

    /**
     * Load fixtures from files contained in iterator.
     *
     * @param \Iterator $iterator Iterator over files from which fixtures should be loaded.
     * @see https://github.com/doctrine/data-fixtures/blob/master/lib/Doctrine/Common/DataFixtures/Loader.php
     * @return array $fixtures Array of loaded fixture object instances.
     */
    private function loadFromIterator(\Iterator $iterator)
    {
        $includedFiles = [];
        foreach ($iterator as $file) {
            if (($fileName = $file->getBasename($this->fileExtension)) == $file->getBasename()) {
                continue;
            }
            $sourceFile = realpath($file->getPathName());
            require_once $sourceFile;
            $includedFiles[] = $sourceFile;
        }

        $fixtures = [];
        $declared = get_declared_classes();
        foreach ($declared as $className) {
            $reflClass = new \ReflectionClass($className);
            $sourceFile = $reflClass->getFileName();
            if (in_array($sourceFile, $includedFiles) && ! $this->isTransient($className)) {
                $fixture = new $className;
                $fixtures[] = $fixture;
                $this->addFixture($fixture);
            }
        }
        return $fixtures;
    }
}