<?php

namespace App\Controller\Admin\Comment;

use App\Entity\Comment;
use App\Entity\Vehicle;
use App\Repository\AgencyRepository;
use App\Repository\CommentRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/admin/comment/list', name: 'admin.comment.index')]
    public function index(CommentRepository $commentRepository, VehicleRepository $vehicleRepository, AgencyRepository $agencyRepository): Response
    {

        return $this->render('pages/admin/comment/index.html.twig', [
            "comments" => $commentRepository->findAll(),
            "vehicles" => $vehicleRepository->findAll(),
            "agency" => $agencyRepository->findAll()


        ]);
    }

    #[Route('/admin/comment/{id}/activate', name: 'admin.comment.activate', methods: ['PUT'])]
    public function activate(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid("comment_activate_" . $comment->getId(), $request->request->get('csrf_token'))) {
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


    #[Route('/admin/comment/{id}/delete', name: 'admin.comment.delete', methods: ['DELETE'])]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid("delete_comment_" . $comment->getId(), $request->request->get('csrf_token'))) {
            $em->remove($comment);
            $em->flush();

            $this->addFlash('success', "Le commentaire  a été supprimé.");
        }

        return $this->redirectToRoute('admin.comment.index');
    }


    #[Route('/admin/comments/multiple-comments-delete', name: 'admin.comments.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        CommentRepository $commentRepository,
        EntityManagerInterface $em
    ): Response {
        $csrfTokenValue = $request->request->get('csrf_token');

        if (!$this->isCsrfTokenValid("multiple_delete_comments_token_key", $csrfTokenValue)) {
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        $ids = $request->request->get('ids');
        $ids = explode(",", $ids);
        foreach ($ids as $id) {
            $comment = $commentRepository->findOneBy(['id' => $id]);

            $em->remove($comment);
            $em->flush();
        }

        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);
    }
}
