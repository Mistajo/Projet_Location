<?php

namespace App\Controller\Admin\Comment;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/admin/comment/list', name: 'admin.comment.index')]
    public function index(CommentRepository $commentRepository): Response
    {

        return $this->render('pages/admin/comment/index.html.twig', [
            "comments" => $commentRepository->findAll(),

        ]);
    }

    #[Route('/admin/comment/{id}/activate/agency', name: 'admin.comment.activate.agency', methods: ['PUT'])]
    public function activateAgency(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid("comment_activate_agency_" . $comment->getId(), $request->request->get('csrf_token'))) {
            if ($comment->isIsActivated() == true) {
                $comment->setIsActivated(false);
                $this->addFlash('success', 'Le commentaire a été désactivé');
            } else {
                $comment->setIsActivated(true);
                $this->addFlash('success', 'Le commentaire a été activé');
            }
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('admin.comment.index');
    }

    #[Route('/admin/comment/{id}/activate/vehicle', name: 'admin.comment.activate.vehicle', methods: ['PUT'])]
    public function activateVehicle(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid("comment_activate_vehicle_" . $comment->getId(), $request->request->get('csrf_token'))) {
            if ($comment->isIsActivated() == true) {
                $comment->setIsActivated(false);
                $this->addFlash('success', 'Le commentaire a été désactivé');
            } else {
                $comment->setIsActivated(true);
                $this->addFlash('success', 'Le commentaire a été activé');
            }
            $em->persist($comment);
            $em->flush();
        }
        return $this->redirectToRoute('admin.comment.index');
    }

    #[Route('/admin/comment/{id}/delete/agency', name: 'admin.comment.delete_agency', methods: ['DELETE'])]
    public function deleteAgency(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid("delete_comment_agency_" . $comment->getId(), $request->request->get('csrf_token'))) {
            $em->remove($comment);
            $em->flush();

            $this->addFlash('success', "Le commentaire de l'agence a été supprimé.");
        }

        return $this->redirectToRoute('admin.comment.index');
    }

    #[Route('/admin/comment/{id}/delete/vehicle', name: 'admin.comment.delete_vehicle', methods: ['DELETE'])]
    public function deleteVehicle(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid("delete_comment_vehicle_" . $comment->getId(), $request->request->get('csrf_token'))) {
            $em->remove($comment);
            $em->flush();

            $this->addFlash('success', "Le commentaire du véhicule a été supprimé.");
        }

        return $this->redirectToRoute('admin.comment.index');
    }
}
