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

class UtilityController extends AbstractController
{

  
    public function getTotalMessagesList($chatRepository, $toUser)
    {
        $unreadMessages = $chatRepository->findBy(['mto'=> $toUser, 'seen'=> 0]);
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
    }
   
}
