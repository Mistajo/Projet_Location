<?php

namespace App\Tests\Controller\Admin;

session_start();

use App\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use App\Tests\Controller\Trait\NeedLoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AgencyControllerAdminTest extends WebTestCase
{
    use NeedLoginTrait;

    public function testAgencyAdminListRestricted(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/agency/list');

        $this->assertResponseRedirects('/login');
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testAgencyAdminCreateRestricted(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/agency/create');

        $this->assertResponseRedirects('/login');
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testLetAuthencatedUserAccessAuth(): void
    {
        $client = static::createClient();
        $user = new User();
        $user->setEmail('ridelocation@hotmail.com');
        $user->setPassword('Joan@456789*');
        $user->setRoles(['ROLE_ADMIN']);
        $session = $client->getContainer()->get('session');
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
        // $this->login(
        //     $client,
        //     'ridelocation@hotmail.com',
        //     'Joan@456789*',
        // );


        $crawler = $client->request('GET', '/admin/agency/create');

        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
