<?php

namespace App\Controller;

use App\Entity\Likes;
use App\Entity\User;
use App\Form\LikesType;
use App\Repository\LikesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/likes")
 */
class LikesController extends AbstractController
{
    /**
     * @Route("/", name="likes_index", methods={"GET"})
     */
    public function index(LikesRepository $likesRepository): Response
    {
        return $this->render('likes/index.html.twig', [
            'likes' => $likesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="likes_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(array('idNumber' => $request->request->get('id')));
        $like = $em->getRepository(Likes::class)->findOneBy(array('likedBy'=>$this->getUser(),'liker'=>$user));
        $returnResponse = new JsonResponse();
        if($like){
            $em->remove($like);
            $em->flush();

            $returnResponse->setJson("rm");
            return $returnResponse;
        }
        else{
            $like = new Likes();
            if($user){
                $like->setLikedBy($this->getUser());
                $like->setLiker($user);
                $like->setCreatedAt(new DateTime());
                $em->persist($like);
                $em->flush();
                
                $returnResponse->setJson("cr");
                return $returnResponse;
            }
            else{
                $returnResponse->setJson("un");
                return $returnResponse;
            }
        }
        return $returnResponse;
    }

    /**
     * @Route("/{id}", name="likes_show", methods={"GET"})
     */
    public function show(Likes $like): Response
    {
        return $this->render('likes/show.html.twig', [
            'like' => $like,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="likes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Likes $like): Response
    {
        $form = $this->createForm(LikesType::class, $like);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('likes_index');
        }

        return $this->render('likes/edit.html.twig', [
            'like' => $like,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="likes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Likes $like): Response
    {
        if ($this->isCsrfTokenValid('delete'.$like->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($like);
            $entityManager->flush();
        }

        return $this->redirectToRoute('likes_index');
    }
}
