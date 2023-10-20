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
    // Route pour l'affichage de la liste des réservations
    #[Route('/admin/reservation/list', name: 'admin.reservation.index')]
    public function index(ReservationRepository $reservationRepository, VehicleRepository $vehicleRepository,): Response
    {
        // on redirige vers la page d'accueil de la liste des réservations
        return $this->render('pages/admin/reservation/index.html.twig', [
            // on récupère toutes les réservations dans la vue
            'reservations' => $reservationRepository->findAll(),
            // on récupère toutes les voitures dans la vue
            'vehicle' => $vehicleRepository->findAll(),
        ]);
    }

    // Route pour la suppression d'une réservation. Avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/réservation/{id}/delete', name: 'admin.reservation.delete', methods: ['DELETE'])]
    public function delete(Reservation $reservation, Request $request, EntityManagerInterface $em): Response
    {
        // si le token de sécurité est valide
        if ($this->isCsrfTokenValid('delete-reservation-' . $reservation->getId(), $request->request->get('csrf_token'))) {
            // on prepare la requete de suppression d'une réservation
            $em->remove($reservation);
            //  on envoie la requete
            $em->flush();
            // on retourne un message de success
            $this->addFlash('success', 'La réservation a été supprimée avec succès');
        }
        // on redirige vers la page des réservations
        return $this->redirectToRoute('admin.reservation.index');
    }

    // Route pour la suppression d'une réservation multiple. Avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/réservations/multiple-reservations-delete', name: 'admin.reservations.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $em
    ): Response {
        // on récupere la valeur du token
        $csrfTokenValue = $request->request->get('csrf_token');
        // si le token n'est pas valide 
        if (!$this->isCsrfTokenValid("multiple_delete_reservations_token_key", $csrfTokenValue)) {
            // pn retourne un message d'erreur
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        // dans le cas contraire
        // on récupères les ids
        $ids = $request->request->get('ids');
        // on transforme les ids en tableau de chaine de caractère
        $ids = explode(",", $ids);
        // pour chaque id
        foreach ($ids as $id) {
            // on récupère la réservation
            $reservation = $reservationRepository->findOneBy(['id' => $id]);
            // on prepare la requete de suppression de la réservation
            $em->remove($reservation);
            // on exexute la requete
            $em->flush();
        }
        // on retourne un message de success
        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);
    }
}