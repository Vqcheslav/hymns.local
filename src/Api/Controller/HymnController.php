<?php

namespace App\Api\Controller;

use App\Repository\CoupletRepository;
use App\Repository\HymnRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HymnController extends AbstractController
{
    private HymnRepository $hymnRepository;

    public function __construct(HymnRepository $hymnRepository, CoupletRepository $coupletRepository)
    {
        $this->hymnRepository = $hymnRepository;
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
}
