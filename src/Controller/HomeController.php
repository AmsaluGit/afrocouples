<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Religion;
use App\Repository\UserRepository;
use App\Form\UserType;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $religion = $em->getRepository(Religion::class)->findAll();
        // $users = $em->getRepository(User::class)->findAll();
        $query = $em->createQuery(
            'SELECT u FROM App\Entity\User u ORDER BY u.id'
            );
            
        $users = $query->setMaxResults(20)->getResult();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'religions' => $religion,
            'users' => $users
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

        
        if($request->request->get('age')){
            foreach($request->request->get('age') as $key => $value)
            {
                if(preg_match($pattern, $value)){
                    if($value == "smaller"){
                        for($i=18; $i<=24; $i++)
                        {
                            $age[] = $i;
                        }
                    }
                    if($value == "small"){
                        for($i=25; $i<30; $i++)
                        {
                            $age[] = $i;
                        }
                    }
                    if($value == "big"){
                        for($i=30; $i<35; $i++)
                        {
                            $age[] = $i;
                        }
                    }
                    if($value == "bigger"){
                        for($i=35; $i<40; $i++)
                        {
                            $age[] = $i;
                        }
                    }

                    if($value == "biggest")
                    {
                        for($i=40; $i<60; $i++)
                        {
                            $age[] = $i;
                        }
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
        $dql = $user_repo->getFilteredData($name, $sex, $religion, $age, $start, $end);
        
        // $dql->setParameter("age", $age);
        // $dql->setParameter("sex", $sex);
        $users = $dql->getArrayResult();
        // dd($content);
        // dd($content);
        $response['users'] = $users;
        $response['draw'] = $request->request->get("draw");

        $returnResponse = new JsonResponse();
        $returnResponse->setJson(json_encode($response));
    
        return $returnResponse;
    }

    /**
     *  @Route("/user/{id}/detail", name="user_detail", methods={"GET", "POST"})
     */
    public function detail($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(array("userId"=> $id));
        
        return $this->render("home/show.html.twig", [
            'user' => $user,
        ]);
    }

    /**
     *  @Route("/register", name="user_register", methods={"GET", "POST"})
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            dd("");
        }
        return $this->render("home/register.html.twig",[
            'form' => $form->createView()
        ]);
    }
}
