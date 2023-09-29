<?php

namespace App\Controller\Admin\Agency;

use App\Entity\Agency;
use App\Form\AgencyFormType;
use App\Repository\AgencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AgencyController extends AbstractController
{
    #[Route('/admin/agency/list', name: 'admin.agency.index')]
    public function index(AgencyRepository $agencyRepository): Response
    {
        $agencies = $agencyRepository->findby([], ['createdAt' => 'DESC']);
        $agencies = $agencyRepository->findAll();
        return $this->render('pages/admin/agency/index.html.twig', [
            'agencies' => $agencies
        ]);
    }

    #[Route('/admin/agency/create', name: 'admin.agency.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $agency = new Agency();
        $form = $this->createForm(AgencyFormType::class, $agency);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($agency);
            $em->flush();
            $this->addFlash("success", "L'agence a bien été crée.");
            return $this->redirectToRoute("admin.agency.index");
        }
        return $this->render("pages/admin/agency/create.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/agency/{id}/edit', name: 'admin.agency.edit', methods: ['GET', 'PUT'])]
    public function edit(Agency $agency, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AgencyFormType::class, $agency, [
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash("success", "L'agence a bien été modifiée.");
            return $this->redirectToRoute("admin.agency.index");
        }
        return $this->render("pages/admin/agency/edit.html.twig", [
            "form" => $form->createView(),
            "agency" => $agency
        ]);
    }

    #[Route('/admin/agency/{id}/delete', name: 'admin.agency.delete', methods: ['DELETE'])]
    public function delete(Agency $agency, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid("delete_agency_" . $agency->getId(), $request->request->get("csrf_token"))) {
            $em->remove($agency);
            $em->flush();
            $this->addFlash("success", "L'agence"  . $agency->getname() . "a bien été supprimée.");
            return $this->redirectToRoute("admin.agency.index");
        }
        return $this->redirectToRoute("admin.agency.index");
    }
}
