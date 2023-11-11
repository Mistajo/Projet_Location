<?php

namespace App\Controller\User\Agency;

use App\Entity\Like;
use App\Entity\Agency;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\LikeRepository;
use App\Repository\AgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgencyController extends AbstractController
{
    #[Route('/user/agency', name: 'user.agency.index')]
    public function index(AgencyRepository $agencyRepository, AgencyRepository $agency, PaginatorInterface $paginator, Request $request): Response
    {
        $agency = $agencyRepository->findAll();
        $agencies = $paginator->paginate(
            $agency, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        return $this->render('pages/user/agency/index.html.twig', [
            'agency' => $agencyRepository->findAll(),
            'agencies' => $agencies,
        ]);
    }

    #[Route('/user/agency/{id}/show', name: 'user.agency.show', methods: ['GET', 'POST'])]
    public function show(Agency $agency, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setUser($this->getUser());
            $comment->setAgency($agency);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('user.agency.show', ['id' => $agency->getId()]);
        }

        return $this->render('pages/user/agency/show.html.twig', [
            'agency' => $agency,
            "form" => $form->createView(),

        ]);
    }

    #[Route('/user/agency/{id}/like', name: 'user.agency.like', methods: ['GET'])]
    public function like(Agency $agency, LikeRepository $likeRepository, EntityManagerInterface $em): Response
    {
        //Récuperons l'utilisateur censé etre connecté
        $user = $this->getUser();


        //Vérifions, Si l'article a déja été aimé par l'utilisateur connecté,
        if ($agency->isLikedBy($user)) {

            //Récupérons ce like,
            $like = $likeRepository->findOneBy(['agency' => $agency, 'user' => $user]);

            //Demandons au gestionnaire des entités de supprimer le like
            $em->remove($like);
            $em->flush();

            //Retournons la réponse correspondante au navigateur du client pour qu'il mette à jour les données
            return $this->json([
                'message' => "Le like a été retiré",
                'totalLikes' => $likeRepository->count(['agency' => $agency])
            ]);
        }

        //Dans le cas contraire,

        //créons le nouveau like
        $like = new Like();
        $like->setUser($user);
        $like->setAgency($agency);

        // Demandons au gestionnaire des entités de réaliser la requette d'insertion en base.accordion
        $em->persist($like);
        $em->flush();

        //Retournons la réponse correspondante au navigateur du client pour qu'il mette à jour les données.
        return $this->json([
            'message' => "Le like a été ajouté",
            'totalLikes' => $likeRepository->count(['agency' => $agency])
        ]);
    }
}
