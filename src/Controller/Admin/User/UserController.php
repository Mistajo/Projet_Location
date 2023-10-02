<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\EditUserRolesFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/admin/user/list', name: 'admin.user.index')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('pages/admin/user/index.html.twig', [
            "users" => $userRepository->findAll()
        ]);
    }

    #[Route('/admin/user/{id}/edit_roles', name: 'admin.user.edit_roles', methods: ["GET", 'PUT'])]
    public function editRoles(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EditUserRolesFormType::class, $user, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "Les Roles de " . $user->getFirstName() . " " . $user->getLastName() . " " . "ont bien été modifiés");
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render("pages/admin/user/edit_roles.html.twig", [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'admin.user.delete', methods: ['DELETE'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('csrf_token'))) {

            $em->remove($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur " . $user->getFirstName() . " " . $user->getLastName() . " a bien été supprimé");
        }
        return $this->redirectToRoute('admin.user.index');
    }
}
