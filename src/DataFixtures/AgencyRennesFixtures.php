<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgencyRennesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $agencyRennes = $this->createAgencyRennes();


        $manager->persist($agencyRennes);

        $manager->flush();
    }

    private function createAgencyRennes(): Agency
    {
        $agency = new Agency();
        $agency->setName('Agence Rennes');
        $agency->setDescription('Agence de location de voitures. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $agency->setAddress('8 Quai Duguay-Trouin');
        $agency->setTown('Rennes');
        $agency->setzipCode('35000');
        $agency->setPhone('02 45 04 05 06');
        $agency->setImage('/public/images/agencies/Agence_rennes.jpg');
        return $agency;
    }
}
