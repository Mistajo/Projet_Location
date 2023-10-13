<?php

namespace App\Controller\User\Like;

use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\AgencyRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/user/like/list', name: 'user.like.index')]
    public function index(LikeRepository $likeRepository, VehicleRepository $vehicleRepository, AgencyRepository $agency, PaginatorInterface $paginator, Request $request): Response
    {
        $like = $likeRepository->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC']
        );
        $likes = $paginator->paginate(
            $like,

            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('pages/user/like/index.html.twig', [
            'likes' =>  $likes,
            'agencies' => $agency->findAll(),
            'vehicles' => $vehicleRepository->findAll(),
        ]);
    }

    #[Route('/user/like/{id}/show', name: 'user.like.show', methods: ['GET'])]
    public function show(Like $like): Response
    {
        return $this->render('pages/user/like/show.html.twig', [
            'like' => $like,
        ]);
    }

    #[Route('/admin/like/{id}/delete', name: 'user.like.delete', methods: ['DELETE'])]
    public function delete(Like $like, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete_like_" . $like->getId(), $request->request->get("csrf_token"))) {
            $em->remove($like);
            $em->flush();
            $this->addFlash("success", "Le like a bien été supprimé.");
            return $this->redirectToRoute("user.like.index");
        }
        return $this->redirectToRoute("user.like.index");
    }
}
