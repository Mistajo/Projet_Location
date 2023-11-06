<?php

namespace App\Controller\Admin\Agency;

use App\Entity\Agency;
use App\Form\AgencyFormType;
use App\Repository\AgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AgencyController extends AbstractController
{
    // on affiche la liste des agences
    #[Route('/admin/agency/list', name: 'admin.agency.index')]
    public function index(AgencyRepository $agencyRepository): Response
    {
        //on recupère la date de creation pour pouvoir les classer par ordre décroissant
        $agencies = $agencyRepository->findby([], ['createdAt' => 'DESC']);
        // on récupère toutes les agences
        $agencies = $agencyRepository->findAll();
        // on fait la redirection s vers la page d'acceuil des agences
        return $this->render('pages/admin/agency/index.html.twig', [
            'agencies' => $agencies
        ]);
    }

    // on creer une agence, en precisant les methodes
    #[Route('/admin/agency/create', name: 'admin.agency.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        // on creer une nouvelle agence
        $agency = new Agency();
        // on fait appel au formulaire creer avec symfony console make:Form qui corresponds à l'entité des agences
        $form = $this->createForm(AgencyFormType::class, $agency);
        // on prepare la requete
        $form->handleRequest($request);
        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on demande au manager des entités de préparer la requette
            $em->persist($agency);
            // on de mande au manager des entités de l'enregistrer dans la base de données
            $em->flush();
            // on affiche un message de succès
            $this->addFlash("success", "L'agence" . " " . $agency->getname() . " " . "a bien été crée.");
            // on redirige vers la page d'acceuil des agences
            return $this->redirectToRoute("admin.agency.index");
        }
        // on redirige vers la page de création d'une agence
        return $this->render("pages/admin/agency/create.html.twig", [
            // on passe le formulaire à la vue
            "form" => $form->createView()
        ]);
    }

    // on affiche l'agence, avec la methode GET
    #[Route('/admin/agency/{id}/show', name: 'admin.agency.show', methods: ['GET'])]
    public function show(Agency $agency): Response
    {
        // on redirige vers la page d'affichage de l'agence 
        return $this->render('pages/admin/agency/show.html.twig', [
            // on affiche l'agence
            'agency' => $agency,
        ]);
    }

    // on modifie les agences avec les methodes GET et PUT(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/agency/{id}/edit', name: 'admin.agency.edit', methods: ['GET', 'PUT'])]
    public function edit(Agency $agency, Request $request, EntityManagerInterface $em): Response
    {
        // on creer le formulaire en lui precisant la methode
        $form = $this->createForm(AgencyFormType::class, $agency, [
            'method' => 'PUT'
        ]);
        // on prepare la requete
        $form->handleRequest($request);
        // on verifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on envois la requete
            $em->flush();
            // on affiche un message de succes
            $this->addFlash("success", "L'agence" . " " . $agency->getname() . " " .  "a bien été modifiée.");
            // on redirige vers la page d'accueil des agences
            return $this->redirectToRoute("admin.agency.index");
        }
        // on redirige vers la page de modification de l'agence
        return $this->render("pages/admin/agency/edit.html.twig", [
            // on passe le formulaire dans la vue
            "form" => $form->createView(),
            // on passe l'agence dans la vue
            "agency" => $agency
        ]);
    }

    // on supprime une agence en precisant la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/agency/{id}/delete', name: 'admin.agency.delete', methods: ['DELETE'])]
    public function delete(Agency $agency, Request $request, EntityManagerInterface $em): Response
    {
        // si le Token est valide
        if ($this->isCsrfTokenValid("delete_agency_" . $agency->getId(), $request->request->get("csrf_token"))) {
            // On prepare la requete de suppression de l'agence
            $em->remove($agency);
            // on envois la requete
            $em->flush();
            // on affiche un message de succès
            $this->addFlash("success", "L'agence"  . " " .  $agency->getname() . " " . "a bien été supprimée.");
            // on redirige vers la page d'accueil des agences
            return $this->redirectToRoute("admin.agency.index");
        }
        // on redirige vers la page d'accueil des agences
        return $this->redirectToRoute("admin.agency.index");
    }

    // On supprime plusieur agences en meme temps avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/agency/multiple-agencies-delete', name: 'admin.agency.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        AgencyRepository $agencyRepository,
        EntityManagerInterface $em
    ): Response {
        // on recupere la valeur du token
        $csrfTokenValue = $request->request->get('csrf_token');
        // si le token n'est pas valide, on renvoie une erreur
        if (!$this->isCsrfTokenValid("multiple_delete_agencies_token_key", $csrfTokenValue)) {
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        // dans le cas contraire
        // on récupère la valeur de l'id de l'agence
        $ids = $request->request->get('ids');
        // on les transforme en tableau de chaine de caractère
        $ids = explode(",", $ids);
        // pour chaque id trouvé
        foreach ($ids as $id) {
            // on récupère l'id de l'agence 
            $agency = $agencyRepository->findOneBy(['id' => $id]);
            // on prepare la requette de suppression des agences
            $em->remove($agency);
            // on envois la requette
            $em->flush();
        }
        // on renvoie un message de succès
        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);
        // return new JsonResponse();
    }
}
