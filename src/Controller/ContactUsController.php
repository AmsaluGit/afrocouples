<?php

namespace App\Controller;

use App\Entity\ContactUs;
use App\Form\ContactUsType;
use App\Repository\ContactUsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\Filter\ContactUsFilterType;
use App\Repository\ChatRepository;
use DateTime;

/**
 * @Route("/contactus")
 */
class ContactUsController extends AbstractController
{

    /**
     * @Route("/", name="send_message", methods={"GET", "POST"})
     */
    public function sendMessage(ContactUsRepository $contactUsRepository, PaginatorInterface $paginator, Request $request, ChatRepository $chatRepository): Response
    {

        $contactus = new ContactUs();
        $form = $this->createForm(ContactUsType::class, $contactus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $contactus->setCreatedAt(new DateTime());
                $entityManager->persist($contactus);
                $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        $util = new UtilityController();
        $msg = $util->getTotalMessagesList($chatRepository, $this->getUser());
       

        return $this->render('contact_us/send_message.html.twig', [
            'form' => $form->createView(),
            'error' => "",
            'totalMsgs'=> $msg[1]
        ]);

 
    }


    /**
     * @Route("/admin", name="contact_us_index", methods={"GET"})
     */
    public function index(ContactUsRepository $contactUsRepository, PaginatorInterface $paginator, Request $request, ContactUsRepository $contactURepository): Response
    {

        $pageSize = 5;

$contactU = new ContactUs();
$searchForm = $this->createForm(ContactUsFilterType::class, $contactU);
 

$key = "";
if(    $request->query->get('contact_us_filter')    )
{
    $key = $request->query->get('contact_us_filter')['email'];
}

$queryBuilder = $contactURepository->filterContactUs($key);



$data = $paginator->paginate(
    $queryBuilder,
    $request->query->getInt('page', 1),
    $pageSize
);

return $this->render('contact_us/index.html.twig', [
    'contactuses' => $data,
    'searchForm' => $searchForm->createView(),
]);

 
    }

    /**
     * @Route("/new", name="contact_us_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contactU = new ContactUs();
        $form = $this->createForm(ContactUsType::class, $contactU);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactU);
            $entityManager->flush();

            return $this->redirectToRoute('contact_us_index');
        }

        return $this->render('contact_us/new.html.twig', [
            'contact_u' => $contactU,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_us_show", methods={"GET"})
     */
    public function show(ContactUs $contactU): Response
    {
        return $this->render('contact_us/show.html.twig', [
            'contact_u' => $contactU,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contact_us_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContactUs $contactU): Response
    {
        $form = $this->createForm(ContactUsType::class, $contactU);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_us_index');
        }

        return $this->render('contact_us/edit.html.twig', [
            'contact_u' => $contactU,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_us_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ContactUs $contactU): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contactU->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contactU);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_us_index');
    }
}
