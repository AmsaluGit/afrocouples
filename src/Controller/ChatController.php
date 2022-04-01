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
         try {
            $mercurHub->publish($update1);
            $mercurHub->publish($update2);
         } catch (\Throwable $th) {
             //throw $th;
         }
        
 
         //return new Response ('private update published! to '.$toUser->getUsername());
         return $this->json('Message sent to: '.$toUser->getUsername(),200,['contentType'=>'application/json']);
         
    }
 

     /**
     * @Route("/message/{uuid}", name="message")
     */
    public function message($uuid, UserRepository $userRepository, ChatRepository $chatRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $friend = $userRepository->findOneBy(['uuid' => $uuid]);
        $chats = $chatRepository->findChatHistory($currentUser, $friend);

        $receivedChatsFromFriends = $chatRepository->findBy(['mfrom'=>$friend, 'mto'=> $currentUser,'seen'=>false]);
        foreach ($receivedChatsFromFriends as $key => $value) {
            $value->setSeen(true);
        }
        $em->flush();
 
        if(!$currentUser)
        {
            dd("user not found");
        }

        $util = new UtilityController();
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       

        return $this->render('chat/chat.html.twig', [
            'user' => $friend,
            'chats'=> $chats,
            'totalMsgs'=> $msg[1]
        ]);
    }

 /**
     * @Route("/messages-list", name="messages-list",  methods={"GET"} )
     */
    public function messageList( Request $request, ChatRepository $chatRepository)
    {
       /* $unreadMessages = $chatRepository->findBy(['mto'=>$this->getUser(), 'seen'=> false]);
 
        $unique_messages = array();
        $totalCount = 0;
 
        foreach ($unreadMessages as $key => $value) {
            $fullName = $value->getMfrom()->getFname().' '. $value->getMfrom()->getMname();
            $uuid = $value->getMfrom()->getUuid();
            $keyval = $value->getMfrom()->getUsername().'~'. $fullName.'~'. $uuid;

            if(isset($unique_messages[ $keyval]))
            {
                $unique_messages[ $keyval] = $unique_messages[ $keyval] + 1;
            }
            else
            {
                $unique_messages[ $keyval] = 1;
            }
            $totalCount ++;
        }*/

        $util = new UtilityController();
        
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       
        return $this->render('chat/messages_list.html.twig', [
            'messages'=> $msg[0],
            'totalMsgs'=> $msg[1]
        ]);
    }

    /*public function getTotalMessagesList($chatRepository)
    {
        $unreadMessages = $chatRepository->findBy(['mto'=>$this->getUser(), 'seen'=> false]);
 
        $unique_messages = array();
        $totalCount = 0;
 
        foreach ($unreadMessages as $key => $value) {
            $fullName = $value->getMfrom()->getFname().' '. $value->getMfrom()->getMname();
            $uuid = $value->getMfrom()->getUuid();
            $keyval = $value->getMfrom()->getUsername().'~'. $fullName.'~'. $uuid;

            if(isset($unique_messages[ $keyval]))
            {
                $unique_messages[ $keyval] = $unique_messages[ $keyval] + 1;
            }
            else
            {
                $unique_messages[ $keyval] = 1;
            }
            $totalCount ++;
        }

        return [$unique_messages, $totalCount]; 
    }*/
   
}
