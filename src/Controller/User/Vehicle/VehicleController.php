<?php

namespace App\Controller\User\Vehicle;


use Stripe\Price;
use Stripe\Stripe;
use App\Entity\Like;
use App\Entity\User;
use App\Entity\Agency;
use App\Entity\Comment;
use App\Entity\Payment;
use App\Entity\Vehicle;
use App\Entity\Reservation;
use Stripe\Checkout\Session;
use App\Form\CommentFormType;
use App\Form\ReservationFormType;
use App\Repository\LikeRepository;
use App\Repository\AgencyRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setVehicle($vehicle);
            $comment->setUser($this->getUser());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('user.vehicle.show', ['id' => $vehicle->getId()]);
        }
        return $this->render('pages/user/vehicle/show.html.twig', [
            'vehicle' => $vehicle,
            "form" => $form->createView(),
        ]);
    }

    #[Route('/user/vehicles/filter-by-vehicle/{id}', name: 'user.vehicles.filter_by_agency', methods: ['GET'])]
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
        //Récuperons l'utilisateur connecté
        $user = $this->getUser();
        $agency = $vehicle->getAgency();

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
    public function Reservation(Vehicle $vehicle, Request $request, EntityManagerInterface $em): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationFormType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $startDate = $reservation->getStartDate();
            $endDate = $reservation->getEndDate();
            $vehicleId = $vehicle->getId();

            $agency = $vehicle->getAgency();
            $reservation->setUser($this->getUser());
            $reservation->setVehicle($vehicle);
            $reservation->setAgency($agency);
            $totalPrice = $reservation->calculateTotalPrice();
            $reservation->setTotalPrice($totalPrice);
            $reservation->setVehicle($vehicle);

            $lastReservation = $em->getRepository(Reservation::class)
                ->findOneBy(['vehicle' => $vehicleId], ['endDate' => 'DESC']);

            $existingReservation = $em->getRepository(Reservation::class)->findOneBy([
                'startDate' => $startDate,
                'endDate' => $endDate,
                'vehicle' => $vehicleId
            ]);

            if ($existingReservation) {
                $this->addFlash('error', "Ce véhicule n'est plus disponible à la réservation pour cette période.");
            } elseif ($lastReservation && $lastReservation->getEndDate() >= $startDate) {
                $this->addFlash('error', "Ce véhicule n'est plus disponible à la réservation pour cette période.");
            } else {
                $em->persist($reservation);
                $em->flush();
                return $this->redirectToRoute('user.vehicle.payment.stripe', ['reservationid' => $reservation->getId()]);
            }
        }
        return $this->render('pages/user/vehicle/reservation.html.twig', [
            'vehicle' => $vehicle,
            'reservation' => $reservation,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/user/reservation/{reservationid}/create-session-stripe', name: 'user.vehicle.payment.stripe')]
    public function StripePayment($reservationid, EntityManagerInterface $em, UrlGeneratorInterface $generator): RedirectResponse
    {
        $reservationRepository = $em->getRepository(Reservation::class);
        $reservation = $reservationRepository->find($reservationid);
        if (!$reservation) {
            throw $this->createNotFoundException("La réservation demandée n'existe pas.");
        }
        Stripe::setApiKey(apiKey: 'sk_test_51OEv73JVomzWvXK9tsoMDu8vvpeHmD4gE1VMlBS0LgD43FHop2HztLMJwCr38Rl2ayVLvYUMpskFUwjDUzkkDAIi00UQjjay9u');
        $price = Price::create([
            'unit_amount' => $reservation->getTotalPrice() * 100, // Total amount multiplied by 100 (in cents)
            'currency' => 'EUR', // Use your preferred currency (e.g., 'eur', 'usd')
            'product_data' => [
                'name' => 'Réservation de véhicule #' . $reservation->getId(),
            ],
        ]);
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price' => $price->id,
                'quantity' => 1,
            ]],
            'success_url' => $generator->generate('user.payment.success', [
                'reservationid' => $reservation->getId(),
                'userid' => $reservation->getUser()->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),

            'cancel_url' => $generator->generate('user.payment.error', [
                'reservationid' => $reservation->getId(),
                'userid' => $reservation->getUser()->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/user/reservation/{reservationid}/success', name: 'user.payment.success')]
    public function paymentSuccess($reservationid, EntityManagerInterface $em, AgencyRepository $agency, VehicleRepository $vehicleRepository, Request $request, PaginatorInterface $paginator)
    {
        $vehiclesAvailable = $vehicleRepository->findBY(['isAvailable' => true], ['availableAt' => 'DESC']);

        $vehicles = $paginator->paginate(
            $vehiclesAvailable,
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        $payment = $em->getRepository(Payment::class)->findOneBy(
            ['reservation' =>
            $reservationid]
        );
        $reservation = $em->getRepository(Reservation::class)->find($reservationid);
        $totalPrice = $reservation->getTotalPrice();

        $payment = new Payment();

        $payment->setUser($this->getUser());
        $payment->setTotalPrice($totalPrice);
        $payment->setMethodOfPayment('Card');
        $payment->setReservation($reservation);
        $reservation->setPaymentStatus('Payée');
        $PaymentStatus = $reservation->getPaymentStatus();
        // Vérifier si le paiement est un succès
        if ($PaymentStatus === 'Payée') {
            $em->persist($payment);
            $em->persist($reservation);
            $em->flush();
            // Ajouter le message flash
            $this->addFlash("success", "Merci pour votre réservation. Rendez-vous dans votre agence pour récupérer votre véhicule");
            // Rediriger vers une autre page
            return $this->redirectToRoute('user.vehicle.index');
        }

        // Si le paiement n'est pas un succès, vous pouvez également afficher un message flash pour indiquer l'échec
        $this->addFlash('error', "Votre Paiement à échoué. Merci de réassayer");
        // Rediriger vers une autre page
        return $this->redirectToRoute('user.vehicle.payment.stripe');

        return $this->render('pages/user/vehicle/index.html.twig', [
            'agencies' => $agency->findAll(),
            'vehicles' => $vehicles,
        ]);
    }

    #[Route('/user/reservation/{reservationid}/error', name: 'user.payment.error')]
    public function paymentError($reservationid, EntityManagerInterface $em, AgencyRepository $agency, VehicleRepository $vehicleRepository, Request $request, PaginatorInterface $paginator)
    {
        $vehiclesAvailable = $vehicleRepository->findBY(['isAvailable' => true], ['availableAt' => 'DESC']);

        $vehicles = $paginator->paginate(
            $vehiclesAvailable,
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        $payment = $em->getRepository(Payment::class)->findOneBy(
            ['reservation' =>
            $reservationid]
        );
        $reservation = $em->getRepository(Reservation::class)->find($reservationid);
        $totalPrice = $reservation->getTotalPrice();

        $payment = new Payment();

        $payment->setUser($this->getUser());
        $payment->setTotalPrice($totalPrice);
        $payment->setMethodOfPayment('Card');
        $payment->setReservation($reservation);
        $reservation->setPaymentStatus('Non payée');
        $PaymentStatus = $reservation->getPaymentStatus();
        // Vérifier si le paiement est un succès
        if ($PaymentStatus === 'Payée') {
            $em->persist($payment);
            $em->persist($reservation);
            $em->flush();
            // Ajouter le message flash
            $this->addFlash("success", "Merci pour votre réservation. Rendez-vous dans votre agence pour récupérer votre véhicule");
            // Rediriger vers une autre page
            return $this->redirectToRoute('user.vehicle.index');
        }

        // Si le paiement n'est pas un succès, vous pouvez également afficher un message flash pour indiquer l'échec
        $this->addFlash('error', "Votre Paiement à échoué. Merci de réassayer");
        // Rediriger vers une autre page
        return $this->redirectToRoute('user.vehicle.index');

        return $this->render('pages/user/vehicle/index.html.twig', [
            'agencies' => $agency->findAll(),
            'vehicles' => $vehicles,
        ]);

        return $this->render('pages/user/vehicle/recap_reservation.html.twig', []);
    }
}
