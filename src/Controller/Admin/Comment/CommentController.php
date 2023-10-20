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
    // on affiche la liste des commentaires sur la page d'accueil des commenatires
    #[Route('/admin/comment/list', name: 'admin.comment.index')]
    public function index(CommentRepository $commentRepository, VehicleRepository $vehicleRepository, AgencyRepository $agencyRepository): Response
    {
        return $this->render('pages/admin/comment/index.html.twig', [
            // on récupère tout les commentaires dans la vue
            "comments" => $commentRepository->findAll(),
            // on récupère tout les véhicules dans la vue
            "vehicles" => $vehicleRepository->findAll(),
            // on récupère toutes les agences dans la vue
            "agency" => $agencyRepository->findAll()
        ]);
    }

    // on active ou on désactive un commentaire avec la methode PUT(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/comment/{id}/activate', name: 'admin.comment.activate', methods: ['PUT'])]
    public function activate(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        // si le token est valide
        if ($this->isCsrfTokenValid("comment_activate_" . $comment->getId(), $request->request->get('csrf_token'))) {
            // si le commentaire est activé
            if ($comment->isIsActivated() == true) {
                // on le desactive
                $comment->setIsActivated(false);
                // on affiche un message de succès
                $this->addFlash('success', 'Le commentaire a été désactivé');
            } else {
                // dans le cas contraire
                // on active le commentaire
                $comment->setIsActivated(true);
                // on affiche un message de succès
                $this->addFlash('success', 'Le commentaire a été activé');
            }
            // on prepare la requete 
            $em->persist($comment);
            // on envoie la requete
            $em->flush();
        }
        // on redirige vers l'accueil des commentaires
        return $this->redirectToRoute('admin.comment.index');
    }

    // On supprime un commentaire avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/comment/{id}/delete', name: 'admin.comment.delete', methods: ['DELETE'])]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        // si le token de sécurité est valide
        if ($this->isCsrfTokenValid("delete_comment_" . $comment->getId(), $request->request->get('csrf_token'))) {
            // on prepare la requette de suppression du commentaire
            $em->remove($comment);
            // on envoie la requete
            $em->flush();
            // on affiche un message de succès
            $this->addFlash('success', "Le commentaire  a été supprimé.");
        }
        // on redirige vers l'accueil des commentaires
        return $this->redirectToRoute('admin.comment.index');
    }


    // On supprime plusieurs commentaires avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/comments/multiple-comments-delete', name: 'admin.comments.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        CommentRepository $commentRepository,
        EntityManagerInterface $em
    ): Response {
        // on récupère la valeur du token de sécurité
        $csrfTokenValue = $request->request->get('csrf_token');
        // si le token de sécurité n'est pas valide
        if (!$this->isCsrfTokenValid("multiple_delete_comments_token_key", $csrfTokenValue)) {
            // on renvois un message d'erreur
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        // dans le cas contraire
        // on récupères les ids des commentaires
        $ids = $request->request->get('ids');
        // on les transforme en tableau de chaine de carractères
        $ids = explode(",", $ids);
        // pour chaque id trouvé
        foreach ($ids as $id) {
            // on récupère l'id du commentaire
            $comment = $commentRepository->findOneBy(['id' => $id]);
            // on prépare la requette de suppression du commentaire
            $em->remove($comment);
            // on execute la requete
            $em->flush();
        }
        // on retourne un message de succès
        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);
    }
}
