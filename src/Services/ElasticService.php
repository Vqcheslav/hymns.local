<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticService
{
    private \Elastic\Elasticsearch\Client $elastic;

    public function __construct()
    {
        $this->elastic = ClientBuilder::create()->build();
    }

    /**
     * @return \Elastic\Elasticsearch\Client
     */
    public function getElastic(): \Elastic\Elasticsearch\Client
    {
        return $this->elastic;
    }
}