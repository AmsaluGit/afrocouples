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
         $time = new DateTime();

         $chat = new Chat();
         $chat->setMfrom($fromUser);
         $chat->setMto($toUser);
         $chat->setMessage($message);
         $chat->setCreatedAt($time);
         
         $em->persist($chat);
         $em->flush();

         $mesg = [
            'message' => $message,
            'name' => $fromUser->getFname(),
            'direction' => 'left',
            'createdAt' => $time,
            'fromuuid' => $fromUser->getUuid(),
         ];


        //now publish the message to the reciver(s).
        $channel1 = $toUser->getUuid();
           $update1 = new Update (
            $channel1,
             json_encode($mesg ),
             // true // private
         );

           $mesg['direction']='right';
            //now publish the message to the reciver(s).
            $channel2 = $fromUser->getUuid(); // the publish to sender itself, for multiple devices.
           $update2 = new Update (
            $channel2,
             json_encode($mesg),
             // true // private
         );

         // Publisher's JWT must contain this topic, a URI template it matches or * in mercure.publish or you'll get a 401
         // Subscriber's JWT must contain this topic, a URI template it matches or * in mercure.subscribe to receive the update
         $mercurHub->publish($update1);
         $mercurHub->publish($update2);
 
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
