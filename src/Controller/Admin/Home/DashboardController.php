<?php

namespace App\Controller\Admin\Home;

use App\Repository\AgencyRepository;
use App\Repository\CommentRepository;
use App\Repository\ContactRepository;
use App\Repository\LikeRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/home/dashboard', name: 'admin.home.dashboard')]
    public function index(AgencyRepository $agencyRepository, CommentRepository $commentRepository, ContactRepository $contactRepository, LikeRepository $likeRepository, ReservationRepository $reservationRepository, UserRepository $userRepository, VehicleRepository $vehicleRepository): Response
    {
        return $this->render('pages/admin/home/index.html.twig', [
            'agencies' => $agencyRepository->findAll(),
            'comments' => $commentRepository->findAll(),
            'contacts' => $contactRepository->findAll(),
            'likes' => $likeRepository->findAll(),
            'reservations' => $reservationRepository->findAll(),
            'users' => $userRepository->findAll(),
            'vehicles' => $vehicleRepository->findAll(),
        ]);
    }
}
