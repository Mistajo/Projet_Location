<?php

namespace App\Controller\User\Vehicle;

use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    #[Route('/user/vehicle', name: 'user.vehicle.index')]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        return $this->render('pages/user/vehicle/index.html.twig', [
            'vehicles' => $vehicleRepository->findAll()
        ]);
    }
}
