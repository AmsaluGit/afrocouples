<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\Gallery1Type;
use App\Repository\GalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/gallery")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route("/new", name="gallery_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(Gallery1Type::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gallery);
            $entityManager->flush();

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('gallery/new.html.twig', [
            'gallery' => $gallery,
            'form' => $form->createView(),
        ]);
    }

    /**
     *  @Route("/upload", name="upload_image", methods={"POST"})
     */
    public function upload(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->get('image');
        if($data){
            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $data = base64_decode($image_array_2[1]);
            $image_name = time() . '.png';
            $image_path = $this->getParameter('uploads_directory').$image_name;
            
            try{
                file_put_contents($image_path, $data);

                $gallery = new Gallery();
                $gallery->setPhoto($image_name);
                $gallery->setUser($this->getUser());
                $em->persist($gallery);
                $em->flush();

                $response["status"] = true;
                $response["image"] = "/uploads/".$image_name;;
                $returnResponse = new JsonResponse();
                $returnResponse->setJson(json_encode($response));
                return $returnResponse;
            }
            catch(Exception $e){
                $response["status"] = false;
                $returnResponse = new JsonResponse();
                $returnResponse->setJson(json_encode($response));
                return $returnResponse;   
            }
            
        }

    }


    /**
     * @Route("/{id}", name="gallery_show", methods={"GET"})
     */
    public function show(Gallery $gallery): Response
    {
        return $this->render('gallery/show.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gallery_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gallery $gallery): Response
    {
        $form = $this->createForm(Gallery1Type::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gallery_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Gallery $gallery): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('delete'.$gallery->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gallery');
    }
}
