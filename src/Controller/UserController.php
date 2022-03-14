<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Gallery;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\Filter\UserFilterType;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    // /**
    //  * @Route("/", name="user_index", methods={"GET"})
    //  */
    // public function index(UserRepository $userRepository): Response
    // {
    //     return $this->render('user/index.html.twig', [
    //         'users' => $userRepository->findAll(),
    //     ]);
    // }

    /**
     * @Route("/", name="user_index", methods={"GET","POST"})
     */
    public function index(UserPasswordEncoderInterface $encoder,Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response 
    {
        $pageSize=5;

        $user = new User();
        $user->setUuid(uniqid("",true));
        
        $searchForm = $this->createForm(userFilterType::class,$user);
        $searchForm->handleRequest($request);
        
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $user=$userRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(userType::class, $user);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('user_index');
            }
            $queryBuilder=$userRepository->filterUser($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                $pageSize
            );
            return $this->render('user/index.html.twig', [
                'users' => $data,
                'form' => $form->createView(),
                'searchForm' => $searchForm->createView(),
                'edit'=>$id
            ]);    
        }
        $form = $this->createForm(userType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $encoded = $encoder->encodePassword($user, $form['password']->getData());
            $user->setPassword($encoded);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        $queryBuilder = $userRepository->filterUser($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            $pageSize
        );

        return $this->render('user/index.html.twig', [
            'users' => $data,
            'form' => $form->createView(),
            'searchForm' => $searchForm->createView(),
            'edit'=>false
        ]);
    }

    /**
     * @Route("/{id}", name="update_profile")
    */
    public function changeProfile(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $gallery = $em->getRepository(Gallery::class)->findOneBy(array('photo'=>$id, 'user'=>$this->getUser()));
        if($gallery){
            $user = $this->getUser();
            $user->setProfileImage($gallery->getPhoto());
            $em->persist($user);
            $em->flush();          
        }
        
        return $this->redirectToRoute("gallery");
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
    */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
