<?php

namespace App\Controller\Admin\Agency;

use App\Entity\Agency;
use App\Form\AgencyFormType;
use App\Repository\AgencyRepository;
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
        return $this->render('pages/admin/agency/index.html.twig', [
            'agencies' => $agencies
        ]);
    }

    #[Route('/admin/agency/create', name: 'admin.agency.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $agency = new Agency();
        $form = $this->createForm(AgencyFormType::class, $agency);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd('poto');
        }
        return $this->render("pages/admin/agency/create.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
