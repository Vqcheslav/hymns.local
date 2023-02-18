<?php

namespace App\Api\Controller;

use App\Repository\CoupletRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CoupletController extends AbstractController
{
    private CoupletRepository $coupletRepository;

    public function __construct(CoupletRepository $coupletRepository)
    {
        $this->coupletRepository = $coupletRepository;
    }

    #[Route('/api/couplets/{hymnId}', name: 'api.couplets.index', methods: ['GET', 'HEAD'])]
    public function index(int $hymnId): JsonResponse
    {
        $couplets = $this->coupletRepository->findByHymnId($hymnId);

        return new JsonResponse(['data' => $couplets]);
    }
}
