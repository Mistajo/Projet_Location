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
    // on affiche les like sur la page d'accueil des likes
    #[Route('/admin/like/list', name: 'admin.like.index')]
    public function index(LikeRepository $likeRepository, VehicleRepository $vehicleRepository): Response
    {
        // on redirige vers la page d'accueil des likes
        return $this->render('pages/admin/like/index.html.twig', [
            // on recupère tout les likes dans la vue
            'likes' => $likeRepository->findAll(),
            // on recupère tout les véhicules dans la vue
            'vehicle' => $vehicleRepository->findAll(),
        ]);
    }

    // on supprime un like avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/like/{id}/delete', name: 'admin.like.delete', methods: ['DELETE'])]
    public function delete(Like $like, Request $request, EntityManagerInterface $em): Response
    {
        // si le token est valide
        if ($this->isCsrfTokenValid('delete-like-' . $like->getId(), $request->request->get('csrf_token'))) {
            // on prepare la requete de suppression du like
            $em->remove($like);
            // on execute la requete de suppression du like
            $em->flush();
            // on affiche un message de succès
            $this->addFlash('success', 'Le Like a été supprimé avec succès');
        }
        // on redirige vers la page d'accueil des likes
        return $this->redirectToRoute('admin.like.index');
    }

    // on supprime plusieurs likes avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/likes/multiple-likes-delete', name: 'admin.likes.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        LikeRepository $likeRepository,
        EntityManagerInterface $em
    ): Response {
        // on recupere la valeur du token
        $csrfTokenValue = $request->request->get('csrf_token');
        // si le token n'est pas valide
        if (!$this->isCsrfTokenValid("multiple_delete_likes_token_key", $csrfTokenValue)) {
            // on retourne un message d'erreur
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        // dans le cas contraire
        // on recupere les ids 
        $ids = $request->request->get('ids');
        // on les transforme en tableau de chaines de caractères
        $ids = explode(",", $ids);
        // pour chaque id
        foreach ($ids as $id) {
            // on recupere le like
            $like = $likeRepository->findOneBy(['id' => $id]);
            // on prepare la requete de suppression du like
            $em->remove($like);
            // on execute la requete
            $em->flush();
        }
        // on retourne un message de success
        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);
    }
}