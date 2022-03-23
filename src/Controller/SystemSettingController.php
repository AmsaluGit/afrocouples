<?php

namespace App\Controller;

use App\Entity\SystemSetting;
use App\Form\SystemSettingType;
use App\Repository\SystemSettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/setting")
 */
class SystemSettingController extends AbstractController
{
    /**
     * @Route("/", name="app_system_setting_index", methods={"GET"})
     */
    public function index(SystemSettingRepository $systemSettingRepository): Response
    {
        return $this->render('system_setting/index.html.twig', [
            'system_settings' => $systemSettingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_system_setting_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SystemSettingRepository $systemSettingRepository): Response
    {
        $systemSetting = new SystemSetting();
        $form = $this->createForm(SystemSettingType::class, $systemSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $systemSettingRepository->add($systemSetting);
            return $this->redirectToRoute('app_system_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('system_setting/new.html.twig', [
            'system_setting' => $systemSetting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_system_setting_show", methods={"GET"})
     */
    public function show(SystemSetting $systemSetting): Response
    {
        return $this->render('system_setting/show.html.twig', [
            'system_setting' => $systemSetting,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_system_setting_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SystemSetting $systemSetting, SystemSettingRepository $systemSettingRepository): Response
    {
        $form = $this->createForm(SystemSettingType::class, $systemSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $systemSettingRepository->add($systemSetting);
            return $this->redirectToRoute('app_system_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('system_setting/edit.html.twig', [
            'system_setting' => $systemSetting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_system_setting_delete", methods={"POST"})
     */
    public function delete(Request $request, SystemSetting $systemSetting, SystemSettingRepository $systemSettingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systemSetting->getId(), $request->request->get('_token'))) {
            $systemSettingRepository->remove($systemSetting);
        }

        return $this->redirectToRoute('app_system_setting_index', [], Response::HTTP_SEE_OTHER);
    }
}
