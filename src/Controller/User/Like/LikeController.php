<?php

namespace App\Controller\User\Like;

use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/user/like/list', name: 'user.like.index')]
    public function index(LikeRepository $likeRepository): Response
    {
        return $this->render('pages/user/like/index.html.twig', [
            'likes' => $likeRepository->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC'])
        ]);
    }
}
