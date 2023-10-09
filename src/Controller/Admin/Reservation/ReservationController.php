<?php

namespace App\Controller\Admin\Reservation;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/admin/reservation/list', name: 'admin.reservation.index')]
    public function index(ReservationRepository $reservationRepository, VehicleRepository $vehicleRepository,): Response
    {
        return $this->render('pages/admin/reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
            'vehicle' => $vehicleRepository->findAll(),

        ]);
    }

    #[Route('/admin/réservation/{id}/delete', name: 'admin.reservation.delete', methods: ['DELETE'])]
    public function delete(Reservation $reservation, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete-reservation-' . $reservation->getId(), $request->request->get('csrf_token'))) {
            $em->remove($reservation);
            $em->flush();
            $this->addFlash('success', 'La réservation a été supprimée avec succès');
        }
        return $this->redirectToRoute('admin.reservation.index');
    }

    #[Route('/admin/réservations/multiple-reservations-delete', name: 'admin.reservations.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $em
    ): Response {
        $csrfTokenValue = $request->request->get('csrf_token');

        if (!$this->isCsrfTokenValid("multiple_delete_reservations_token_key", $csrfTokenValue)) {
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        $ids = $request->request->get('ids');
        $ids = explode(",", $ids);
        foreach ($ids as $id) {
            $reservation = $reservationRepository->findOneBy(['id' => $id]);

            $em->remove($reservation);
            $em->flush();
        }

        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);
    }
}
