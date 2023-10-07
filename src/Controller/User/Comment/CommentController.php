<?php

namespace App\Controller\User\Comment;

use App\Repository\CommentRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/user/comment/list', name: 'user.comment.index')]
    public function index(CommentRepository $commentRepository, VehicleRepository $vehicleRepository,): Response
    {

        return $this->render('pages/user/comment/index.html.twig', [
            'comments' => $commentRepository->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']),
            'vehicles' => $vehicleRepository->findAll()
        ]);
    }
}
