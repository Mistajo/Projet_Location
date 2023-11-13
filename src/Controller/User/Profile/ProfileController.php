<?php

namespace App\Controller\User\Profile;

use App\Form\EditProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EditProfilePasswordFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'user.profile.index')]
    public function index(): Response
    {
        return $this->render('pages/user/profile/index.html.twig');
    }

    #[Route('/user/profile/edit', name: 'user.profile.edit', methods: ['GET', 'PUT'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileFormType::class, $user, ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié');

            return $this->redirectToRoute('user.profile.index');
        }

        return $this->render('pages/user/profile/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/user/profile/edit-password', name: 'user.profile.edit_password', methods: ['GET', 'PUT'])]
    public function editPassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        //on recupère l'utilisateur connecté
        $user = $this->getUser();
        //On creer le formulaire, en utilisant la methode Put
        $form = $this->createForm(EditProfilePasswordFormType::class, null, [
            'method' => 'PUT'
        ]);
        //on recupere la requete
        $form->handleRequest($request);
        //si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //on recupere les données du tableau
            $data = $request->request->all();
            //on recupere les données du mot de passe du formulaire associé à la  modificaion du profile
            $password = $data['edit_profile_password_form']['password']['first'];
            //on demande au hasher d'hasher le mot de passe
            $passwordHashed = $hasher->hashPassword($user, $password);
            //on modifie le mot de passe hasher
            $user->setPassword($passwordHashed);
            //l'entity manager prepare la requete
            $em->persist($user);
            //et l'execute
            $em->flush();
            //on affiche un message flash
            $this->addFlash('success', "Votre mot de passe a été modifié.");
            //on dirige vers la page d'acceuil du profil de l'admin
            return $this->redirectToRoute('user.profile.index');
        }
        //dans le cas contraire on renvoit sur la page de modification du mot de passe 
        //on affiche le formulaire
        return $this->render("pages/user/profile/edit_password.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/user/profile/delete', name: 'user.profile.delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid("profile-delete", $request->request->get('csrf_token'))) {
            $user = $this->getUser();

            $em->remove($user);
            $em->flush();

            $this->container->get('security.token_storage')->setToken(null);

            $this->addFlash('success', "Votre compte a bien été supprimé. Vous allez nous manquer.");
        }

        return $this->redirectToRoute('visitor.authentication.login');
    }
}
