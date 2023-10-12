<?php

namespace App\Controller\User\Reservation;

use App\Entity\Vehicle;
use App\Entity\Reservation;
use App\Repository\AgencyRepository;
use App\Repository\VehicleRepository;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/user/reservation/list', name: 'user.reservation.index')]
    public function index(ReservationRepository $reservationRepository, VehicleRepository $vehicleRepository, AgencyRepository $agency, PaginatorInterface $paginator, Request $request): Response
    {
        $reservation = $reservationRepository->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC']
        );
        $reservations = $paginator->paginate(
            $reservation,

            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );


        return $this->render('pages/user/reservation/index.html.twig', [
            'reservations' => $reservations,
            'vehicle' => $vehicleRepository->findAll(),
        ]);
    }

    #[Route('/user/reservation/{id}/show', name: 'user.reservation.show')]
    public function show(Reservation $reservation, VehicleRepository $vehicleRepository): Response
    {
        return $this->render('pages/user/reservation/show.html.twig', [
            'reservation' => $reservation,
            'vehicle' => $vehicleRepository->findAll(),
        ]);
    }
}
