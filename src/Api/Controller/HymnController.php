<?php

namespace App\Api\Controller;

use App\Repository\HymnRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HymnController extends AbstractController
{
    #[Route('/api/hymns', name: 'api.hymns.index', methods: ['GET', 'HEAD'])]
    public function index(HymnRepository $hymnRepository): JsonResponse
    {
        $hymns = $hymnRepository->findMany();

        return new JsonResponse(['data' => $hymns]);
    }

    #[Route('/api/hymns/{hymnId}', name: 'api.hymns.show', methods: ['GET', 'HEAD'])]
    public function show(int $hymnId, HymnRepository $hymnRepository): JsonResponse
    {
        $hymn = $hymnRepository->findOne($hymnId);

        return new JsonResponse(['data' => $hymn]);
    }
}
