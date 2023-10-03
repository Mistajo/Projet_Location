<?php

namespace App\Controller\User\Vehicle;

use App\Entity\Agency;
use App\Repository\AgencyRepository;
use App\Repository\VehicleRepository;
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
}
