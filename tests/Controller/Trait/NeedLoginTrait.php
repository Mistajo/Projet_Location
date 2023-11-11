<?php

namespace App\Tests\Controller\Trait;

use App\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait NeedLoginTrait
{

    public function login(KernelBrowser $client, $email, $password,)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);
        $session = $client->getContainer()->get('session');
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
