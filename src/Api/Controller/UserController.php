<?php

namespace App\Api\Controller;

use App\Message\MailNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    #[Route('/api/user/send', name: 'api.user.send', methods: ['GET', 'HEAD'])]
    public function send(): JsonResponse
    {
        // will cause the MailNotificationHandler to be called
        $this->bus->dispatch(new MailNotification('Look! I created a message!'));

        return new JsonResponse(['result' =>  true]);
    }
}