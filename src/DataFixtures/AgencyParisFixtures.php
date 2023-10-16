<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgencyParisFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $agencyParis = $this->createAgencyParis();



        $manager->persist($agencyParis);


        $manager->flush();
    }



    private function createAgencyParis(): Agency
    {
        $agency = new Agency();
        $agency->setName('Agence Paris');
        $agency->setDescription('Agence de location de voitures. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $agency->setAddress('8 Avenue des Champs Elysées');
        $agency->setTown('Paris');
        $agency->setzipCode('75008');
        $agency->setPhone('01 45 02 03 04');
        $agency->setImage('/public/images/agencies/barnes11-65169c26067f2874481182.jpg');
        return $agency;
    }
}
