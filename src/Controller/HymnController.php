<?php

namespace App\Controller;

use App\Repository\HymnRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HymnController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(HymnRepository $hymnRepository): Response
    {
        $hymns = $hymnRepository->findMany();

        return $this->render('homepage.html.twig', [
            'hymns' => $hymns,
        ]);
    }
}
