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

    public function searchHymnsByTitleStrictly(string $query)
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

    public function searchHymnsByTitle(string $query, int $size = 3)
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

    public function searchCouplets(string $query, int $size = 5)
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