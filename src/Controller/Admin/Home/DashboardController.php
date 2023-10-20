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
    // on affiche les agences, les commentaires,les contacts, les reservations, les likes, les utilisateurs, les vehicules sur le tableau de bords de l'admin
    #[Route('/admin/home/dashboard', name: 'admin.home.dashboard')]
    public function index(AgencyRepository $agencyRepository, CommentRepository $commentRepository, ContactRepository $contactRepository, LikeRepository $likeRepository, ReservationRepository $reservationRepository, UserRepository $userRepository, VehicleRepository $vehicleRepository): Response
    {
        return $this->render('pages/admin/home/index.html.twig', [
            // on récupère toutes les agences dans la vue
            'agencies' => $agencyRepository->findAll(),
            // on récupère tout les commentaires dans la vue
            'comments' => $commentRepository->findAll(),
            // on récupères tout les contacts dans la vue
            'contacts' => $contactRepository->findAll(),
            // on récupère tout les likes dans la vue
            'likes' => $likeRepository->findAll(),
            // on récupère toutes les reservations dans la vue
            'reservations' => $reservationRepository->findAll(),
            // on récupère tout les utilisateurs dans la vue
            'users' => $userRepository->findAll(),
            // on récupère tout les vehicules dans la vue
            'vehicles' => $vehicleRepository->findAll(),
        ]);
    }
}
