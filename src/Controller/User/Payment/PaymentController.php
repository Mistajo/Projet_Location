<?php

namespace App\Controller\User\Payment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/user/Payment/list', name: 'user.Payment.index')]
    public function index(): Response
    {
        return $this->render('pages/user/payment/index.html.twig');
    }
}
