<?php

namespace App\Controller\Admin\Profile;

use App\Form\EditProfileFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/admin/profile', name: 'admin.profile.index')]
    public function index(): Response
    {

        return $this->render('pages/admin/profile/index.html.twig');
    }

    #[Route('/admin/profile/edit', name: 'admin.profile.edit', methods: ['GET', 'PUT'])]
    public function edit(): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileFormType::class, $user, ['method' => 'PUT']);


        return $this->render('pages/admin/profile/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
