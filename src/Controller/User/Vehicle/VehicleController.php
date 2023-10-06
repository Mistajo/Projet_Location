<?php

namespace App\Controller\User\Vehicle;

use App\Entity\Like;
use App\Entity\Agency;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Entity\Vehicle;
use App\Entity\Reservation;
use App\Form\CommentFormType;
use App\Form\ReservationFormType;
use App\Repository\LikeRepository;
use App\Repository\AgencyRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VehicleController extends AbstractController
{
    #[Route('/user/vehicle', name: 'user.vehicle.index')]
    public function index(VehicleRepository $vehicleRepository, AgencyRepository $agency, PaginatorInterface $paginator, Request $request): Response
    {
        $vehiclesAvailable = $vehicleRepository->findBY(['isAvailable' => true], ['availableAt' => 'DESC']);


        $vehicles = $paginator->paginate(
            $vehiclesAvailable,

            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/user/vehicle/index.html.twig', [
            'vehicles' => $vehicles,
            'agencies' => $agency->findAll(),
        ]);
    }

    #[Route('/user/vehicle/{id}/show', name: 'user.vehicle.show', methods: ['GET', 'POST'])]
    public function show(Vehicle $vehicle, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();
        $agency = $vehicle->getAgency();

        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setVehicle($vehicle);
            $comment->setUser($this->getUser());
            $comment->setAgency($agency);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('user.vehicle.show', ['id' => $vehicle->getId()]);
        }
        return $this->render('pages/user/vehicle/show.html.twig', [
            'vehicle' => $vehicle,
            "form" => $form->createView(),
        ]);
    }

    #[Route('/user/vehicles/filter-by-category/{id}', name: 'user.vehicles.filter_by_agency', methods: ['GET'])]
    public function filterByAgency(Agency $agency, AgencyRepository $agencyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $agencies = $agencyRepository->findAll();
        $vehiclesAvailable = $agency->getAvailableVehicles();

        $vehicles = $paginator->paginate(
            $vehiclesAvailable,

            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render("pages/user/vehicle/index.html.twig", [
            "agencies" => $agencies,
            "vehicles" => $vehicles,
        ]);
    }

    #[Route('/user/vehicle/{id}/like', name: 'user.vehicle.like', methods: ['GET'])]
    public function like(Vehicle $vehicle, LikeRepository $likeRepository, EntityManagerInterface $em): Response
    {
        //Récuperons l'utilisateur censé etre connecté
        $user = $this->getUser();
        $agency = $vehicle->getAgency();
        //S'il n'est pas connecté
        if (!$user) {
            //Retournons la réponse au navigateur du client, lui expliquant que l'utilisateur n'est pas connecté
            return $this->json(['message' => "Vous devez être connecté avant d'aimer cet article"], 403);
        }
        //Dans le cas contraire

        //Vérifions, Si l'article a déja été aimé par l'utilisateur connecté,
        if ($vehicle->isLikedBy($user)) {

            //Récupérons ce like,
            $like = $likeRepository->findOneBy(['vehicle' => $vehicle, 'user' => $user, 'agency' => $agency]);

            //Demandons au gestionnaire des entités de supprimer le like
            $em->remove($like);
            $em->flush();

            //Retournons la réponse correspondante au navigateur du client pour qu'il mette à jour les données
            return $this->json([
                'message' => "Le like a été retiré",
                'totalLikes' => $likeRepository->count(['vehicle' => $vehicle])
            ]);
        }

        //Dans le cas contraire,

        //créons le nouveau like
        $like = new Like();
        $like->setUser($user);
        $like->setVehicle($vehicle);
        $like->setAgency($agency);

        // Demandons au gestionnaire des entités de réaliser la requette d'insertion en base.accordion
        $em->persist($like);
        $em->flush();

        //Retournons la réponse correspondante au navigateur du client pour qu'il mette à jour les données.
        return $this->json([
            'message' => "Le like a été ajouté",
            'totalLikes' => $likeRepository->count(['vehicle' => $vehicle])
        ]);
    }

    #[Route('/user/vehicle/{id}/reservation/index', name: 'user.vehicle.reservation.index')]
    public function Reservation(Vehicle $vehicle): Response
    {
        return $this->render('pages/user/vehicle/reservation.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/user/vehicle/{id}/reservation/confirmation', name: 'user.vehicle.reservation.confirmation', methods: ['GET', 'PUT'])]
    public function Confirmation(Vehicle $vehicle, Request $request, EntityManagerInterface $em): Response
    {
        $agency = $vehicle->getAgency();

        if ($this->isCsrfTokenValid("vehicle_reservation_" . $vehicle->getId(), $request->request->get('csrf_token'))) {
            $reservation = new Reservation();
            $reservation->setVehicle($vehicle);
            $reservation->setUser($this->getUser());
            $reservation->setAgency($agency);
            $reservation->setStartDate(new DateTimeImmutable('now'));
            $reservation->setEndDate(new DateTimeImmutable('now'));
            $prix = $vehicle->getDailyPrice() * ($reservation->getEndDate()->diff($reservation->getStartDate())->days + 1);
            $reservation->setTotalPrice($prix);

            $em->persist($reservation);
            $em->flush();
            $this->addFlash("success", "Votre reservation pour le vehicule" . " " . $vehicle->getName() . " " . "a été enregistrée." . " " . "Rendez-Vous à votre agence pour le retrait et le reglement");
        }
        return $this->redirectToRoute("user.vehicle.index");

        return $this->render('pages/user/home.index.html.twig', [
            'vehicle' => $vehicle,
            'reservation' => $reservation,

        ]);
    }
}
