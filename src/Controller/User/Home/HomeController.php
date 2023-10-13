<?php

namespace App\Controller\User\Home;

use App\Repository\AgencyRepository;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\ReservationRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/user/home', name: 'user.home.index')]
    public function index(AgencyRepository $agencyRepository, VehicleRepository $vehicleRepository, ReservationRepository $reservationRepository, CommentRepository $commentRepository, LikeRepository $likeRepository): Response
    {
        $user = $this->getUser();

        return $this->render('pages/user/home/index.html.twig', [
            'agencies' => $agencyRepository->findAll(),
            'vehicles' => $vehicleRepository->findAll(),
            'reservations' => $reservationRepository->findAll(),
            'comments' => $commentRepository->findAll(),
            'likes' => $likeRepository->findAll(),
            'user' => $user
        ]);
    }
}
