<?php

namespace App\Controller;

use App\Entity\Occupation;
use App\Form\OccupationType;
use App\Repository\OccupationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\Filter\OccupationFilterType;

/**
 * @Route("/occupation")
 */
class OccupationController extends AbstractController
{
    /**
     * @Route("/", name="occupation_index", methods={"GET"})
     */
    public function index(OccupationRepository $occupationRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $pageSize = 5;

$occupation = new Occupation();
$searchForm = $this->createForm(OccupationFilterType::class, $occupation);


$key = "";
if(    $request->query->get('occupation_filter')    )
{
    $key = $request->query->get('occupation_filter')['name'];
}

$queryBuilder = $occupationRepository->filterByName($key);



$data = $paginator->paginate(
    $queryBuilder,
    $request->query->getInt('page', 1),
    $pageSize
);

return $this->render('occupation/index.html.twig', [
    'occupations' => $data,
    'searchForm' => $searchForm->createView(),
]);

/*
        return $this->render('occupation/index.html.twig', [
            'occupations' => $occupationRepository->findAll(),
        ]);*/
    }

    /**
     * @Route("/new", name="occupation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $occupation = new Occupation();
        $form = $this->createForm(OccupationType::class, $occupation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($occupation);
            $entityManager->flush();

            return $this->redirectToRoute('occupation_index');
        }

        return $this->render('occupation/new.html.twig', [
            'occupation' => $occupation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="occupation_show", methods={"GET"})
     */
    public function show(Occupation $occupation): Response
    {
        return $this->render('occupation/show.html.twig', [
            'occupation' => $occupation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="occupation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Occupation $occupation): Response
    {
        $form = $this->createForm(OccupationType::class, $occupation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('occupation_index');
        }

        return $this->render('occupation/edit.html.twig', [
            'occupation' => $occupation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="occupation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Occupation $occupation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$occupation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($occupation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('occupation_index');
    }
}
