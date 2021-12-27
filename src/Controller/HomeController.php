<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Religion;
use App\Entity\User;

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
}
