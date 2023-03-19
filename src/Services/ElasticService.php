<?php

namespace App\Services;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;

class ElasticService
{
    private Client $elastic;

    public function __construct()
    {
        $this->elastic = ClientBuilder::create()->build();
    }

    /**
     * @return Client
     */
    public function getElastic(): Client
    {
        return $this->elastic;
    }

    public function searchHymnsByTitleStrictly(string $query): Elasticsearch|Promise
    {
        $params = [
            'index' => 'hymns',
            'body'  => [
                'query' => [
                    'match' => [
                        'hymnTitle' => [
                            'query'    => $query,
                            'operator' => 'and'
                        ]
                    ]
                ]
            ]
        ];

        return $this->elastic->search($params);
    }

    public function searchHymnsByTitle(string $query, int $size = 3): Elasticsearch|Promise
    {
        $params = [
            'index' => 'hymns',
            'size'  => $size,
            'body'  => [
                'query' => [
                    'match' => [
                        'hymnTitle' => $query
                    ]
                ]
            ]
        ];

        return $this->elastic->search($params);
    }

    public function searchCouplets(string $query, int $size = 5): Elasticsearch|Promise
    {
        $params = [
            'index' => 'couplets',
            'size'  => $size,
            'body'  => [
                'query' => [
                    'match' => [
                        'couplet' => $query
                    ]
                ]
            ]
        ];

        return $this->elastic->search($params);
    }
}