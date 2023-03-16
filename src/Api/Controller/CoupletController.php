<?php

namespace App\Api\Controller;

use App\Repository\CoupletRepository;
use App\Services\ElasticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CoupletController extends AbstractController
{
    private CoupletRepository $coupletRepository;

    private ElasticService $elasticService;

    public function __construct(CoupletRepository $coupletRepository, ElasticService $elasticService)
    {
        $this->coupletRepository = $coupletRepository;
        $this->elasticService = $elasticService;
    }

    #[Route('/api/couplets/{hymnId}', name: 'api.couplets.index', methods: ['GET', 'HEAD'])]
    public function index(int $hymnId): JsonResponse
    {
        $couplets = $this->coupletRepository->findByHymnId($hymnId);

        return new JsonResponse(['data' => $couplets]);
    }

    #[Route('/api/couplets/search/{query}', name: 'api.couplets.search', methods: ['GET', 'HEAD'])]
    public function search(string $query): JsonResponse
    {
        $result = $this->elasticService->searchCouplets($query);

        return new JsonResponse(['data' => $result->asArray()]);
    }
}
