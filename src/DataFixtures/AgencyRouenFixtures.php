<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgencyRouenFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $agencyRouen = $this->createAgencyRouen();


        $manager->persist($agencyRouen);


        $manager->flush();
    }


    private function createAgencyRouen(): Agency
    {
        $agency = new Agency();
        $agency->setName('Agence Rouen');
        $agency->setDescription('Agence de location de voitures.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $agency->setAddress('8 Rue de la Republique');
        $agency->setTown('Rouen');
        $agency->setzipCode('76000');
        $agency->setPhone('07 45 01 02 03');
        $agency->setImage('/public/images/agencies/Agence Rouen.jpg');
        return $agency;
    }
}
