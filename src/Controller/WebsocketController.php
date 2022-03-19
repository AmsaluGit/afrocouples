<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WebsocketController extends AbstractController
{
    /**
     * @Route("/messageolder/{uuid}/7777", name="messagesolder")
     */
    public function messages($uuid, UserRepository $userRepository)
    {

        $user = $userRepository->findOneBy(['uuid' => $uuid]);

        return $this->render('websocket/index.html.twig', [
            'user' => $user,
        ]);
    }
}
