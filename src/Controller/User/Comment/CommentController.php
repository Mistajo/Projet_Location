<?php

namespace App\Controller\User\Comment;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\AgencyRepository;
use App\Repository\CommentRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('/user/comment/list', name: 'user.comment.index')]
    public function index(CommentRepository $commentRepository, VehicleRepository $vehicleRepository, AgencyRepository $agency, PaginatorInterface $paginator, Request $request): Response
    {
        $comment = $commentRepository->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC']
        );
        $comments = $paginator->paginate(
            $comment,

            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('pages/user/comment/index.html.twig', [
            'comments' =>  $comments,
            'agencies' => $agency->findAll(),
            'vehicles' => $vehicleRepository->findAll(),
        ]);
    }

    #[Route('/user/comment/{id}/show', name: 'user.comment.show', methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        return $this->render('pages/user/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/user/comment/{id}/edit', name: 'user.comment.edit', methods: ['GET', 'PUT'])]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CommentFormType::class, $comment, [
            'method' => 'PUT',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre Commentaire a été modifié avec succès');

            return $this->redirectToRoute('user.comment.index');
        }
        return $this->render('pages/user/comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    #[Route('/admin/comment/{id}/delete', name: 'user.comment.delete', methods: ['DELETE'])]
    public function delete(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete_comment_" . $comment->getId(), $request->request->get("csrf_token"))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash("success", "Le commentaire a bien été supprimé.");
            return $this->redirectToRoute("user.comment.index");
        }
        return $this->redirectToRoute("user.comment.index");
    }
}
