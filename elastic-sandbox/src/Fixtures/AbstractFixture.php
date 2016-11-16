<?php
namespace ElasticSandbox\Fixtures;

use ElasticSearch\Client as ElasticSearchClient;

abstract class AbstractFixture
{
    /**
     * Document type to be populated
     *
     * @var string
     */
    protected $type;

    /**
     * Index to be populated
     *
     * @var string
     */
    protected $index;

    /**
     * @return array Data
     */
    protected abstract function data();

    public function load(ElasticSearchClient $client)
    {
        $data = $this->data();

        foreach ($data as $dataItem) {
            $params = [];
            $params['index'] = $this->index;
            $params['type']  = $this->type;
            $params['id'] = $dataItem['id'];
            unset($dataItem['id']);

            $params['body'] = $dataItem;

            $client->index($params);
        }
    }

    /**
     * Set the index name
     *
     * @param string $index Index name
     *
     * @return self
     */
    public function setIndex($index)
    {
        $this->index = (string) $index;

        return $this;
    }

    /**
     * Gets the version of the current fixture
     */
    public function version()
    {
        return md5(serialize($this->data()));
    }
}