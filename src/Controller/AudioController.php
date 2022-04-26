<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Religion;
use App\Entity\Likes;
use App\Entity\Gallery;
use App\Entity\Nationality;
use App\Form\AudioType;
use App\Repository\UserRepository;
use App\Form\RegistrationType;
use App\Form\UserType;
use App\Form\GalleryType;
use App\Repository\ChatRepository;
use App\Repository\NationalityRepository;
use DateTime;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;


/**
 * @Route("/audio")
 */

class AudioController extends AbstractController
{




       /**
     *  @Route("/record", name="record", methods={"GET", "POST"})
     */
    public function record(SluggerInterface $slugger,Request $request, ChatRepository $chatRepository)
    {
       // $file = $request->files->get('audio_data');

dd(888);
        dd($_FILES); //this will print out the received name, temp name, type, size, etc.


$size = $_FILES['audio_data']['size']; //the size in bytes
$input = $_FILES['audio_data']['tmp_name']; //temporary name that PHP gave to the uploaded file
$output = $_FILES['audio_data']['name'].".wav"; //letting the client control the filename is a rather bad idea

//move the file from temp name to local folder using $output name
move_uploaded_file($input, $output);




       
       /* $em = $this->getDoctrine()->getManager();
         $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $form = $this->createForm(AudioType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $uploads_directory  = $this->getParameter('uploads_directory')."/".$this->getUser()->getUsername();
 
            $file = $request->files->get('audio')['audio'];
        

                if($file){
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                    
                    try {
                        $file->move(
                            $uploads_directory ,
                            $newFilename
                        );
                            $user->setAudio($newFilename);
                            $em->persist($user);
                       
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $originalFilename = $file->getClientOriginalName();
                   
                }
        }
         
        $em->flush();

        $util = new UtilityController();
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       

        return $this->render("audio/audio.html.twig", [
            'user' => $user,
            'form' => $form->createView(),
            'totalMsgs'=> $msg[1]
        ]);*/

        return new Response("done");
    }






    /**
     *  @Route("/update", name="audio_update", methods={"GET", "POST"})
     */
    public function audio(SluggerInterface $slugger,Request $request, ChatRepository $chatRepository)
    {
        // dd("here");
        $em = $this->getDoctrine()->getManager();
        // $user = new User();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $form = $this->createForm(AudioType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $uploads_directory  = $this->getParameter('uploads_directory')."/".$this->getUser()->getUsername();
 
            $file = $request->files->get('audio')['audio'];
        

                if($file){
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                    
                    try {
                        $file->move(
                            $uploads_directory ,
                            $newFilename
                        );
                            $user->setAudio($newFilename);
                            $em->persist($user);
                       
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $originalFilename = $file->getClientOriginalName();
                   
                }
        }
        /*else 
        {
             
        }*/

        $em->flush();

        $util = new UtilityController();
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       

        return $this->render("audio/audio.html.twig", [
            'user' => $user,
            'form' => $form->createView(),
            'totalMsgs'=> $msg[1]
        ]);
    }




    /**
     * @Route("/{id}", name="audio_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
 
        
            $entityManager = $this->getDoctrine()->getManager();
            $user->setAudio(0);
            $entityManager->flush();

        return new Response("{1:1}");
    }


 
}
