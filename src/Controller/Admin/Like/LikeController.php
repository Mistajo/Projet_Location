<?php

namespace App\Controller\Admin\Like;

use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/admin/like/list', name: 'admin.like.index')]
    public function index(LikeRepository $likeRepository, VehicleRepository $vehicleRepository): Response
    {

        return $this->render('pages/admin/like/index.html.twig', [
            'likes' => $likeRepository->findAll(),
            'vehicle' => $vehicleRepository->findAll(),
        ]);
    }

    #[Route('/admin/like/{id}/delete', name: 'admin.like.delete', methods: ['DELETE'])]
    public function delete(Like $like, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete-like-' . $like->getId(), $request->request->get('csrf_token'))) {
            $em->remove($like);
            $em->flush();
            $this->addFlash('success', 'Le Like a été supprimé avec succès');
        }
        return $this->redirectToRoute('admin.like.index');
    }

    #[Route('/admin/likes/multiple-likes-delete', name: 'admin.likes.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        LikeRepository $likeRepository,
        EntityManagerInterface $em
    ): Response {
        $csrfTokenValue = $request->request->get('csrf_token');

        if (!$this->isCsrfTokenValid("multiple_delete_likes_token_key", $csrfTokenValue)) {
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        $ids = $request->request->get('ids');
        $ids = explode(",", $ids);
        foreach ($ids as $id) {
            $like = $likeRepository->findOneBy(['id' => $id]);

            $em->remove($like);
            $em->flush();
        }

        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);
    }
}
