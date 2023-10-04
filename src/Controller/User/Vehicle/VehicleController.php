<?php

namespace App\Controller\User\Vehicle;

use App\Entity\Agency;
use App\Entity\Comment;
use App\Entity\Vehicle;
use App\Form\CommentFormType;
use App\Repository\AgencyRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VehicleController extends AbstractController
{
    #[Route('/user/vehicle', name: 'user.vehicle.index')]
    public function index(VehicleRepository $vehicleRepository, AgencyRepository $agency): Response
    {
        $vehicles = $vehicleRepository->findBY(['isAvailable' => true], ['availableAt' => 'DESC']);
        return $this->render('pages/user/vehicle/index.html.twig', [
            'vehicles' => $vehicles,
            'agencies' => $agency->findAll(),
        ]);
    }

    #[Route('/user/vehicle/{id}/show', name: 'user.vehicle.show', methods: ['GET', 'POST'])]
    public function show(Vehicle $vehicle, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setVehicle($vehicle);
            $comment->setUser($this->getUser());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('user.vehicle.show', ['id' => $vehicle->getId()]);
        }

        return $this->render('pages/user/vehicle/show.html.twig', [
            'vehicle' => $vehicle,
            "form" => $form->createView(),
        ]);
    }

    #[Route('/user/vehicles/filter-by-category/{id}', name: 'user.vehicles.filter_by_agency', methods: ['GET'])]
    public function filterByAgency(Agency $agency): Response
    {
        dd($agency);
    }
}
