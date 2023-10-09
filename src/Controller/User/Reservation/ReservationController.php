<?php

namespace App\Controller\User\Reservation;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/user/reservation/list', name: 'user.reservation.index')]
    public function index(ReservationRepository $reservationRepository): Response
    {

        return $this->render('pages/user/reservation/index.html.twig', [
            'reservations' => $reservationRepository->findBy(['user' => $this->getUser()]),
        ]);
    }
}
