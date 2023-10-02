<?php

namespace App\Controller\User\Agency;

use App\Repository\AgencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgencyController extends AbstractController
{
    #[Route('/user/agency', name: 'user.agency.index')]
    public function index(AgencyRepository $agencyRepository): Response
    {
        return $this->render('pages/user/agency/index.html.twig', [
            'agencies' => $agencyRepository->findAll()
        ]);
    }
}
