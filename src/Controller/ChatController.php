<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{

     
    /**
     * @Route("/chat/{uuid}", name="chat",  methods={"GET", "POST"} )
     */
    public function messagePublisher($uuid, HubInterface $mercurHub, UserRepository $userRepository, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

         $message = $request->request->get('message');
         $fromUser = $this->getUser();
         $toUser = $userRepository->findOneBy(['uuid' => $uuid]);

         if($toUser===$fromUser )
         {
            $this->addFlash("error","self messaging is disabled for now.");
            return $this->redirectToRoute('home');
         }

         $chat = new Chat();
         $chat->setMfrom($fromUser);
         $chat->setMto($toUser);
         $chat->setMessage($message);
         $chat->setCreatedAt(new DateTime());
         
         $em->persist($chat);
         $em->flush();


        //now publish the message to the reciver(s).
        $channel = $toUser->getUuid();
           $update = new Update (
            $channel,
             json_encode([
                 'message' => $message,
                 'name' => $fromUser->getFname(),
                 'direction' => 'left',
                 'fromuuid' => $fromUser->getUuid(),
            ]),
             // true // private
         );

         // Publisher's JWT must contain this topic, a URI template it matches or * in mercure.publish or you'll get a 401
         // Subscriber's JWT must contain this topic, a URI template it matches or * in mercure.subscribe to receive the update
         $mercurHub->publish($update);
 
         //return new Response ('private update published! to '.$toUser->getUsername());
         return $this->json('Message sent to: '.$toUser->getUsername(),200,['contentType'=>'application/json']);
         
    }
 

     /**
     * @Route("/message/{uuid}", name="message")
     */
    public function message($uuid, UserRepository $userRepository, ChatRepository $chatRepository)
    {

        $currentUser = $this->getUser();
        $friend = $userRepository->findOneBy(['uuid' => $uuid]);
        $chats = $chatRepository->findChatHistory($currentUser, $friend);
 
        if(!$currentUser)
        {
            dd("user not found");
        }

        return $this->render('chat/index.html.twig', [
            'user' => $friend,
            'chats'=> $chats,
        ]);
    }


   
}
