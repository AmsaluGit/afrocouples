<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\PasswordChangeType;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if(in_array("USER_ROLE", $this->getUser()->getRoles()))
                return $this->redirectToRoute('home');
            else{
                //return $this->redirectToRoute('user_index');
                return $this->redirectToRoute('home');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/change/password", name="change_password", methods={"GET","POST"})
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(PasswordChangeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $checkPass = $passwordEncoder->isPasswordValid($this->getUser(), $form['password']->getData());
            if ($checkPass) {
                $entityManager = $this->getDoctrine()->getManager();
                $user = $this->getUser();
                $user->setPassword($passwordEncoder->encodePassword($user, $form['plainPassword']->getData()));
                $entityManager->persist($user);
                $entityManager->flush();

           } else {
                return $this->render('security/changePassword.html.twig', [
                    'form' => $form->createView(),
                    'error' => "Incorrect Old password"
                ]);
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('security/changePassword.html.twig', [
            'form' => $form->createView(),
            'error' => ""
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
