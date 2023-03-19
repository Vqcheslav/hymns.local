<?php

namespace App\Api\Controller;

use App\Message\MailNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus
    ) {
    }

    #[Route('/api/user/send', name: 'api.user.send', methods: ['GET', 'HEAD'])]
    public function send(): JsonResponse
    {
        $this->bus->dispatch(new MailNotification('Look! I created a message!'));

        return new JsonResponse(['result' =>  true]);
    }
}