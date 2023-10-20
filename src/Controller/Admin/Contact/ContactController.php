<?php

namespace App\Controller\Admin\Contact;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    // on affiche la liste des contact dans la page d'accueil des contacts
    #[Route('/admin/contact/list', name: 'admin.contact.index')]
    public function index(ContactRepository $contactRepository): Response
    {
        // on redirige vers la page d'accueil des commentaires
        return $this->render('pages/admin/contact/index.html.twig', [
            // on récupère tout les contacts dans la vue
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    // on supprime un contact dans la page d'accueil des contacts avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/contact/{id}/delete', name: 'admin.contact.delete', methods: ['DELETE'])]
    public function delete(Contact $contact, Request $request, EntityManagerInterface $em): Response
    {
        // si le token de sécurité est valide
        if ($this->isCsrfTokenValid('delete-contact-' . $contact->getId(), $request->request->get('csrf_token'))) {
            // on prepare la requette de suppression de contact
            $em->remove($contact);
            // on execute la requêtte
            $em->flush();
            // on affiche un message de succès
            $this->addFlash('success', 'Le Contact a été supprimé avec succès');
        }
        // on redirige vers la page d'accueil des commentaires
        return $this->redirectToRoute('admin.contact.index');
    }

    // on supprime plusieurs contacts dans la page d'accueil des contacts avec la methode DELETE(pour utiliser la methode PUT et DELETE, il faut que http_method_override et handle_all_throwables soient sur TRUE dans le fichier config/packages/framework.yaml)
    #[Route('/admin/post/multiple-contacts-delete', name: 'admin.contact.multiple_delete', methods: ['DELETE'])]
    public function multipleDelete(
        Request $request,
        ContactRepository $contactRepository,
        EntityManagerInterface $em
    ): Response {
        // on recupère la valeur du token de sécurité
        $csrfTokenValue = $request->request->get('csrf_token');
        // si le token de sécurité n'est pas valide
        if (!$this->isCsrfTokenValid("multiple_delete_contacts_token_key", $csrfTokenValue)) {
            // on retourne un message d'erreur
            return $this->json(
                ['status' => false, "message" => "Un problème est survenu, veuillez réessayer."],
                Response::HTTP_BAD_REQUEST
            );
        }
        // Dans le cas contraire
        // on recupere les ids
        $ids = $request->request->get('ids');
        // on transforme les ids en tableau de chaine de caractères
        $ids = explode(",", $ids);
        // pour chaque id dans le tableau 
        foreach ($ids as $id) {
            // on recupere l'id du contact
            $contact = $contactRepository->findOneBy(['id' => $id]);
            // on prepare la requete de suppression du contact
            $em->remove($contact);
            // on execute la requete
            $em->flush();
        }
        // on retourne un message de success
        return $this->json(['status' => true, "message" => "La suppression multiple a été effectuée avec succès."]);

        // return new JsonResponse();

    }
}
