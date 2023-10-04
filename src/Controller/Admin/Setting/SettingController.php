<?php

namespace App\Controller\Admin\Setting;

use App\Entity\Setting;
use App\Form\SettingFormType;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingController extends AbstractController
{
    #[Route('/admin/setting', name: 'admin.setting.index')]
    public function index(SettingRepository $settingRepository): Response
    {
        $data = $settingRepository->findAll();

        $setting = $data[0];

        return $this->render('pages/admin/setting/index.html.twig', [
            'setting' => $setting
        ]);
    }

    #[Route('/admin/setting{id}/edit', name: 'admin.setting.edit', methods: ['GET', 'PUT'])]
    public function edit(Setting $setting, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(SettingFormType::class, $setting, [
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($setting);
            $em->flush();
            $this->addFlash('success', 'Les paramètres du site ont bien été modifiés');

            return $this->redirectToRoute('admin.setting.index');
        }


        return $this->render('pages/admin/setting/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
