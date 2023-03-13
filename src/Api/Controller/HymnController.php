<?php

namespace App\Api\Controller;

use App\Repository\HymnRepository;
use App\Services\ElasticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HymnController extends AbstractController
{
    private HymnRepository $hymnRepository;

    private ElasticService $elasticService;

    public function __construct(HymnRepository $hymnRepository, ElasticService $elasticService)
    {
        $this->hymnRepository = $hymnRepository;
        $this->elasticService = $elasticService;
    }

    #[Route('/api/hymns', name: 'api.hymns.index', methods: ['GET', 'HEAD'])]
    public function index(): JsonResponse
    {
        $hymns = $this->hymnRepository->findMany(3300);

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
        $couplets = $this->elasticService->searchCouplets($query);

        return new JsonResponse(['data' => $couplets->asArray()]);
    }
}
