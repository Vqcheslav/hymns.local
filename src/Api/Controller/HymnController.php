<?php

namespace App\Api\Controller;

use App\Repository\CoupletRepository;
use App\Repository\HymnRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HymnController extends AbstractController
{
    private HymnRepository $hymnRepository;

    private Client $elasticSearch;

    public function __construct(
        HymnRepository $hymnRepository,
        CoupletRepository $coupletRepository
    )
    {
        $this->hymnRepository = $hymnRepository;
        $this->elasticSearch = ClientBuilder::create()
            ->setApiKey('Z3FUNmQ0WUJNZldUaF9CbEtLMU46ZWV3dF8xN2ZSaGV5S0s1OHoybDNIQQ==')
            ->build();
    }

    #[Route('/api/hymns', name: 'api.hymns.index', methods: ['GET', 'HEAD'])]
    public function index(): JsonResponse
    {
        $hymns = $this->hymnRepository->findMany();

        return new JsonResponse(['data' => $hymns]);
    }

    #[Route('/api/hymns/{hymnId}', name: 'api.hymns.show', methods: ['GET', 'HEAD'])]
    public function show(int $hymnId): JsonResponse
    {
        $hymn = $this->hymnRepository->findOne($hymnId);

        return new JsonResponse(['data' => $hymn]);
    }

    #[Route('/api/hymns/search/{query}', name: 'api.hymns.search', methods: ['GET', 'HEAD'])]
    public function search(string $query): JsonResponse
    {
        $params = [
            'body' => $query,
        ];

        $result = $this->elasticSearch->search($params);

        return new JsonResponse(['data' => $result->asArray()]);
    }
}
