<?php

namespace App\Controller\Admin\Vehicle;

use DateTimeImmutable;
use App\Entity\Vehicle;
use App\Form\VehicleFormType;
use App\Repository\AgencyRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VehicleController extends AbstractController
{
    #[Route('/admin/vehicle/list', name: 'admin.vehicle.index')]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        $vehicles = $vehicleRepository->findby([], ['createdAt' => 'DESC']);
        $vehicles = $vehicleRepository->findAll();
        return $this->render('pages/admin/vehicle/index.html.twig', [
            'vehicles' => $vehicles
        ]);
    }

    // La route pour creer un nouveau vehicule
    #[Route('/admin/vehicle/create', name: 'admin.vehicle.create', methods: ['GET', 'POST'])]
    // on creer la fonction create qui va etre appele par la route
    public function create(Request $request, EntityManagerInterface $em, AgencyRepository $agencyRepository): Response
    {
        // si y'a pas d'agence existante
        if (count($agencyRepository->findAll()) == 0) {
            // on affiche un message flash 
            $this->addFlash('warning', 'Vous devez d\'abord créer des agences');
            // on redirige vers la page index des agences.
            return $this->redirectToRoute('admin.agency.index');
        }
        // dans le cas contraire on creer un nouveau vehicule
        $vehicle = new Vehicle();
        // on creer le formulaire en se basant sur le type de formulaire
        $form = $this->createForm(VehicleFormType::class, $vehicle);
        // on prepare la demande
        $form->handleRequest($request);
        // si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère l'utilisateur connecté
            $vehicle->addUser($this->getUser());
            // l'entity manager prépare la requette
            $em->persist($vehicle);
            // et l'enregistre dans la base de données
            $em->flush();
            // on affiche un message flash
            $this->addFlash("success", "Le vehicle" . " " . $vehicle->getName() . " " . "a bien été créé.");
            // on redirige vers la page index des vehicules
            return $this->redirectToRoute("admin.vehicle.index");
        }
        // dans le cas contraire on affiche la page de création du vehicule
        return $this->render("pages/admin/vehicle/create.html.twig", [
            // on affiche le formulaire à la vue
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/vehicle/{id}/available', name: 'admin.vehicle.available', methods: ['PUT'])]
    public function available(Vehicle $vehicle, EntityManagerInterface $em, Request $request): Response
    {
        // Si le token est valide
        if ($this->isCsrfTokenValid('vehicle_available_' . $vehicle->getId(), $request->request->get('csrf_token'))) {
            // si  le vehicule est déja disponile
            if ($vehicle->isAvailable()) {
                // Retirons-le de la liste des vehicles
                $vehicle->setIsAvailable(false);
                // Rendons nulle la date de publication
                $vehicle->setAvailableAt(null);
                // Générons le message flash
                $this->addFlash("success", "Le vehicule" . " " . $vehicle->getName() . " " .  "est désormais indisponible.");
            } else {
                // Rendons-le disponible dans la liste des vehicules
                $vehicle->setIsAvailable(true);
                // Générons la date de publication
                $vehicle->setAvailableAt(new DateTimeImmutable('now'));
                // Générons le message flash
                $this->addFlash("success", "Le vehicule" . " " . $vehicle->getName() . " " . "est désormais disponible.");
            }
            //Demandons à l'entity manager de préparer la requette
            $em->persist($vehicle);
            //Demandons à l'entity manager d'executer la requete
            $em->flush();
        }
        //effectuons une redirection vers la page d'acceuil de la section vehicle
        return $this->redirectToRoute("admin.vehicle.index");
    }

    #[Route('/admin/vehicle/{id}/show', name: 'admin.vehicle.show', methods: ['GET'])]
    public function show(Vehicle $vehicle, AgencyRepository $agencyRepository): Response
    {
        if (count($agencyRepository->findAll()) == 0) {
            $this->addFlash('warning', 'Vous devez d\'abord créer des agences');
            return $this->redirectToRoute('admin.agency.index');
        }
        return $this->render('pages/admin/vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/admin/vehicle/{id}/edit', name: 'admin.vehicle.edit', methods: ['GET', 'PUT'])]
    public function edit(Request $request, Vehicle $vehicle, EntityManagerInterface $em, AgencyRepository $agencyRepository): Response
    {
        if (count($agencyRepository->findAll()) == 0) {
            $this->addFlash('warning', 'Vous devez d\'abord créer des agences');
            return $this->redirectToRoute('admin.agency.index');
        }

        $form = $this->createForm(VehicleFormType::class, $vehicle, [
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash("success", "Le vehicule" . " " . $vehicle->getName() . " " . "a bien été modifié.");
            return $this->redirectToRoute("admin.vehicle.index");
        }

        return $this->render('pages/admin/vehicle/edit.html.twig', [
            "form" => $form->createView(),
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/admin/vehicle/{id}/delete', name: 'admin.vehicle.delete', methods: ['DELETE'])]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $em, AgencyRepository $agencyRepository): Response
    {
        if (count($agencyRepository->findAll()) == 0) {
            $this->addFlash('warning', 'Vous devez d\'abord créer des agences');
            return $this->redirectToRoute('admin.agency.index');
        }

        // nous verrifions si le token est valide
        if ($this->isCsrfTokenValid("delete_vehicle_" . $vehicle->getId(), $request->request->get("csrf_token"))) {
            //nous demandons à l'entuty manager de supprimer le vehicule concerné
            $em->remove($vehicle);
            // l'entity manager execute la requete
            $em->flush();
            // nous affichons le messade flash
            $this->addFlash("success", "Le vehicule"  . " " . $vehicle->getname() . " " . "a bien été supprimé.");
            // nous redirigeons vers la page d'acceuil des vehicules
            return $this->redirectToRoute("admin.vehicle.index");
        }
        return $this->redirectToRoute("admin.vehicle.index");
    }

    #[Route('/admin/vehicle/multiple-vehicles-delete', name: 'admin.vehicle.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        VehicleRepository $vehicleRepository,
        EntityManagerInterface $em
    ): Response {
        $csrfTokenValue = $request->request->get('csrf_token');
        if (!$this->isCsrfTokenValid("multiple_delete_vehicles_token_key", $csrfTokenValue)) {
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        $ids = $request->request->get('ids');
        $ids = explode(",", $ids);
        foreach ($ids as $id) {
            $vehicle = $vehicleRepository->findOneBy(['id' => $id]);
            $em->remove($vehicle);
            $em->flush();
        }
        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);
        // return new JsonResponse();
    }
}
