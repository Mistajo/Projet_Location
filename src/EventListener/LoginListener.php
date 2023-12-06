<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class LoginListener
{
    private $em;



    public function __construct(EntityManagerInterface $em,)
    {
        $this->em = $em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)

    {


        // Get the User entity.
        $user = $event->getAuthenticationToken()->getUser();
        // Update your field here.
        $user->setLastLoginAt(new \DateTime());
        // Persist the data to database.
        $this->em->persist($user);
        $this->em->flush();
    }
}