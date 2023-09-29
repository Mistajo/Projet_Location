<?php

namespace App\Controller\Visitor\Welcome;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\AgencyRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WelcomeController extends AbstractController
{
    #[Route('/', name: 'visitor.welcome.index')]
    public function index(AgencyRepository $agencyRepository, VehicleRepository $vehicleRepository): Response
    {
        return $this->render('pages/visitor/welcome/index.html.twig', [
            'agencies' => $agencyRepository->findAll(),
            'vehicles' => $vehicleRepository->findBy(["isAvailable" => true], ["availableAt" => "DESC"], 6),
        ]);
    }
}
