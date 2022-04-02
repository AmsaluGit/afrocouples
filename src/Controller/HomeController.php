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
use App\Repository\UserRepository;
use App\Form\RegistrationType;
use App\Form\UserType;
use App\Form\GalleryType;
use App\Repository\ChatRepository;
use App\Repository\NationalityRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class HomeController extends AbstractController
{
 
   
    /**
     * @Route("/messages", name="messages")
     */
    public function messagePublisher(HubInterface $mercurHub, UserRepository $userRepository): Response
    {
       //send message, or publish.
       /* $update  = new Update(
            'http://localhost/country/1/edit',
            json_encode(['status' => 'OutOfStock'])// you can make html here.
        );
        $mercurHub->publish($update);
*/
        $toUser = $userRepository->find(3); //solomon
        $update = new Update(
            $toUser->getUuid(),
            json_encode(['status' => 'OutOfStock']),
            // true // private
        );

        // Publisher's JWT must contain this topic, a URI template it matches or * in mercure.publish or you'll get a 401
        // Subscriber's JWT must contain this topic, a URI template it matches or * in mercure.subscribe to receive the update
        $mercurHub->publish($update);

        return new Response('private update published!');

    }

     /**
     * @Route("/tempo", name="tempo")
     */
    public function temp(): Response
    {
        /**
         * this is for training server deployment preparation.
         */
        return $this->render('home/delete.html.twig', [
            
        ]);


    }

 



    /**
     * @Route("/", name="home")
     */
    public function index(ChatRepository $chatRepository): Response
    {
        
        $em = $this->getDoctrine()->getManager();
        $religion = $em->getRepository(Religion::class)->findBy([],['name'=>'asc'], null,null);
        $query = $em->createQuery(
            'SELECT u FROM App\Entity\User u ORDER BY u.id desc'
            );
        if ($this->getUser()) {
            $sex = $this->getUser()->getSex();
        $query = $em->createQuery(
                "SELECT u FROM App\Entity\User u where u.sex != '".$sex."' and u.id != ".$this->getUser()->getId()." ORDER BY u.id desc"
                );
        }
        
            
        $users = $query->setMaxResults(20)->getResult();

        $age_array = array();
        
        foreach ($users as $key => $value) {
            $diff = date_diff(date_create($value->getBirthdate()->format("Y-m-d")), date_create(date("Y-m-d")));
            $age_array[$value->getId()] = $diff->format('%y');
        }


        $util = new UtilityController();
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       
 
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'religions' => $religion,
            'users' => $users,
            'age_array' => $age_array,
            'totalMsgs'=> $msg[1]
        ]);
    }

    /**
     * @Route("/filter", name="user_filter")
     */
    public function filter(Request $request, UserRepository $user_repo): Response
    {
        $sex = array();
        $religion = array();
        $name = array();
        $age = array();
        $pattern = "/^[A-Za-z\d]{1,30}$/";
        $error = false;

        //$sex[] = strtoupper($this->getUser()->getSex())=="F" ? "m":"f";

        if($request->request->get('sex')){
            foreach($request->request->get('sex') as $key => $value)
            {
                // $value = str_replace('"', "", $value);
                if($value == "F" || $value=="M"){
                    $sex[] = $value;
                }
                else{
                    $error = true;
                }
            }
        }
        elseif ($this->getUser()) //if logged in 
        {
            $sex[] = strtoupper($this->getUser()->getSex())=="F" ? "m":"f";
          
            
        }
        else 
        {
            //
        }
 
        
        if($request->request->get('age')){
            foreach($request->request->get('age') as $key => $value)
            {
                if(preg_match($pattern, $value)){
                    if($value == "smaller"){
                        $age[] = date("Y-m-d", $this->getYearAndDate(18));
                        $age[] = date("Y-m-d", $this->getYearAndDate(24));
                    }
                    if($value == "small"){
                        $age[] = date("Y-m-d", $this->getYearAndDate(24));
                        $age[] = date("Y-m-d", $this->getYearAndDate(29));
                        // for($i=25; $i<30; $i++)
                        // {
                        //     $age[] = date("Y-m-d", $this->getYearAndDate($i));
                        // }
                    }
                    if($value == "big"){
                        $age[] = date("Y-m-d", $this->getYearAndDate(29));
                        $age[] = date("Y-m-d", $this->getYearAndDate(34));
                        // for($i=30; $i<35; $i++)
                        // {
                        //     $age[] = date("Y-m-d", $this->getYearAndDate($i));
                        // }
                    }
                    if($value == "bigger"){
                        $age[] = date("Y-m-d", $this->getYearAndDate(34));
                        $age[] = date("Y-m-d", $this->getYearAndDate(39));
                        // for($i=35; $i<40; $i++)
                        // {
                        //     $age[] = date("Y-m-d", $this->getYearAndDate($i));
                        // }
                    }

                    if($value == "biggest")
                    {
                        $age[] = date("Y-m-d", $this->getYearAndDate(39));
                        $age[] = date("Y-m-d", $this->getYearAndDate(60));
                        // for($i=40; $i<60; $i++)
                        // {
                        //     $age[] = date("Y-m-d", $this->getYearAndDate($i));
                        // }
                    }

                }
                else{
                    echo "errorr";
                    $error = true;
                }
            }
        }

        if($request->request->get('religion')){
            foreach($request->request->get('religion') as $key => $value)
            {
                if(preg_match($pattern, $value)){
                    $religion[] = $value;
                }
                else{
                    $error = true;
                }
            }
        }

        if($request->request->get('name')){
            // dd($request->request->get('name'));
            foreach($request->request->get('name') as $key => $value)
            {
                if(preg_match($pattern, $value)){
                    $name[] = $value;
                }
                else{
                    $error = true;
                }
            }
        }

        $start = $request->request->get('start');
        $end = $request->request->get('end');
        $em = $this->getDoctrine()->getManager();

        $loggedInSex = $this->getUser()? $this->getUser()->getSex(): null;

 
        if($loggedInSex)
        {
            $dql = $user_repo->getFilteredData($name, $sex, $religion, $age, $start, $end, $loggedInSex);
        }
        else 
        {
            $dql = $user_repo->getFilteredData($name, $sex, $religion, $age, $start, $end, null);
        }

       
     
        // $dql->setParameter("age", $age);
        // $dql->setParameter("sex", $sex);
        $users = $dql->getArrayResult();
        // dd($content);
         //dd($users);
        $response['users'] = $users;
        $response['draw'] = $request->request->get("draw");

        $returnResponse = new JsonResponse();
        $returnResponse->setJson(json_encode($response));
        
  
        return $returnResponse;
    }

    /**
     *  @Route("/profile", name="profile", methods={"GET", "POST"})
     */
    public function profile(SluggerInterface $slugger, Request $request, ChatRepository $chatRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $gly = new Gallery();
            /*$file = $form['profileImage']->getData();
            if($file){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setProfileImage($newFilename);
            }*/
            
            $this->getDoctrine()->getManager()->flush();
        }

        $util = new UtilityController();
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       

        return $this->render("home/edit2.html.twig", [
            'user' => $user,
            'form' => $form->createView(),
            'totalMsgs'=> $msg[1]
        ]);
    }

    /**
     *  @Route("/gallery", name="gallery", methods={"GET", "POST"})
     */
    public function gallery(SluggerInterface $slugger,Request $request, ChatRepository $chatRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $gly = new Gallery();
        $form = $this->createForm(GalleryType::class, $gly);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $uploads_directory  = $this->getParameter('uploads_directory')."/".$this->getUser()->getUsername();
            $files = $request->files->get('gallery')['photos'];

            foreach($files as $file){
                $gly = new Gallery();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $uploads_directory,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $gly->setPhoto($newFilename);
                $gly->setUser($this->getUser());
                $em->persist($gly);
                $em->flush();
            }
        }

        $gallery = $em->getRepository(Gallery::class)->findBy(array("user" => $this->getUser()->getId()));


        $util = new UtilityController();
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       

        return $this->render("home/gallery.html.twig", [
            'gallery' => $gallery,
            'form' => $form->createView(),
            'totalMsgs'=> $msg[1]
        ]);
    }

    /**
     *  @Route("/user/{uuid}/detail", name="user_detail", methods={"GET", "POST"})
     */
    public function detail($uuid, UserRepository $userRepository, ChatRepository $chatRepository)
    {
        $user = $userRepository->findOneBy(['uuid'=>$uuid]);
        if(!$user)
        {
            $this->addFlash("No user found with this UUID", 'danger');
            return $this->redirectToRoute("home");
        }
        $em = $this->getDoctrine()->getManager();
        $like = $em->getRepository(Likes::class)->findOneBy(array('likedBy'=>$this->getUser(),'liker'=>$user));

        $diff = date_diff(date_create($user->getBirthdate()->format("Y-m-d")), date_create(date("Y-m-d")));
        $age = $diff->format('%y');

        $util = new UtilityController();
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       



        return $this->render("home/show.html.twig", [
            'user' => $user,
            'like' => $like,
            'age' => $age,
            'totalMsgs'=> $msg[1]
        ]);
    }

    public function getYearAndDate($difference){
        $currentDate = date("Y-m-d", time());
        $date = strtotime("$currentDate -$difference year");
        return $date;
    }

    /**
     *  @Route("/register", name="user_register", methods={"GET", "POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoderInterface, NationalityRepository $nationalityRepository)
    {
        $user = new User();
        $user->setUuid(uniqid("",true));
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $password = $form['plainPassword']->getData();
            $user->setPassword($userPasswordEncoderInterface->encodePassword($user, $password));
            $user->setIdNumber(time());
            $nationality = $nationalityRepository->find(1);//Ethiopian by default
            $user->setNationality($nationality);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_login");
        }
        return $this->render("home/register.html.twig",[
            'form' => $form->createView()
        ]);
    }
}
