<?php

namespace App\Controller\User\Like;

use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/user/like/list', name: 'user.like.list')]
    public function index(LikeRepository $likeRepository): Response
    {
        return $this->render('user/like/like/index.html.twig', [
            'likes' => $likeRepository->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC'])
        ]);
    }
}
