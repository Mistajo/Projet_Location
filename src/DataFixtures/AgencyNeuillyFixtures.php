<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Vehicle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgencyNeuillyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $agency = new Agency();
        $agency->setName('Agence Neuilly-sur-Seine');
        $agency->setDescription('Agence de location de voitures. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $agency->setAddress('8 Avenue Achille Perretti');
        $agency->setTown('Neuilly-sur-Seine');
        $agency->setzipCode('92200');
        $agency->setPhone('01 45 03 04 05');
        $agency->setImage('10832362967a6762e8c35045244dc243-651d9bcd2cc91713314294.jpg');

        $vehicle1 = new Vehicle();
        $vehicle1->setName('Audi R8');
        $vehicle1->setBrand('Audi');
        $vehicle1->setModel('R8');
        $vehicle1->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle1->setImage('Audi_R8.jpg');
        $vehicle1->setDailyPrice(220);
        $vehicle1->setRegistration('ZZ-ZZZ-ZZ');
        $vehicle1->setAgency($agency);

        $vehicle2 = new Vehicle();
        $vehicle2->setName('Ferrari FF');
        $vehicle2->setBrand('Ferrari');
        $vehicle2->setModel('FF');
        $vehicle2->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle2->setImage('ferrari_ff.jpg');
        $vehicle2->setDailyPrice(220);
        $vehicle2->setRegistration('AB-AAA-AA');
        $vehicle2->setAgency($agency);

        $vehicle3 = new Vehicle();
        $vehicle3->setName('Ford GT 2017');
        $vehicle3->setBrand('Ford');
        $vehicle3->setModel('GT 2017');
        $vehicle3->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle3->setImage('Ford_GT_2017.jpg');
        $vehicle3->setDailyPrice(190);
        $vehicle3->setRegistration('AC-AAA-AA');
        $vehicle3->setAgency($agency);

        $vehicle4 = new Vehicle();
        $vehicle4->setName('Mercedes SLS');
        $vehicle4->setBrand('Mercedes');
        $vehicle4->setModel('SLS');
        $vehicle4->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle4->setImage('Mercedes_sls.jpg');
        $vehicle4->setDailyPrice(220);
        $vehicle4->setRegistration('AD-AAA-AA');
        $vehicle4->setAgency($agency);

        $vehicle5 = new Vehicle();
        $vehicle5->setName('Porsche Panamera');
        $vehicle5->setBrand('Porsche');
        $vehicle5->setModel('Panamera');
        $vehicle5->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle5->setImage('porche_Panamera.jpg');
        $vehicle5->setDailyPrice(250);
        $vehicle5->setRegistration('AE-AAA-AA');
        $vehicle5->setAgency($agency);

        $manager->persist($agency);
        $manager->persist($vehicle1);
        $manager->persist($vehicle2);
        $manager->persist($vehicle3);
        $manager->persist($vehicle4);
        $manager->persist($vehicle5);


        $manager->flush();
    }
}
