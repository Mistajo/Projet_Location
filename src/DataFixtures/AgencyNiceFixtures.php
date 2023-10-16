<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Vehicle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgencyNiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $agency = new Agency();
        $agency->setName('Agence Nice');
        $agency->setDescription('Agence de location de voitures. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $agency->setAddress('8 Rue de France');
        $agency->setTown('Nice');
        $agency->setzipCode('06000');
        $agency->setPhone('04 45 03 04 05');
        $agency->setImage('Agence_Nice.jpg');

        $vehicle1 = new Vehicle();
        $vehicle1->setName('Aston Martin Vanquish');
        $vehicle1->setBrand('Aston Martin');
        $vehicle1->setModel('Vanquish');
        $vehicle1->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle1->setImage('Aston_Martin_Vanquish.jpg');
        $vehicle1->setDailyPrice(250);
        $vehicle1->setRegistration('AE-AAA-AA');
        $vehicle1->setAgency($agency);

        $vehicle2 = new Vehicle();
        $vehicle2->setName('Aston Martin Virage');
        $vehicle2->setBrand('Aston Martin');
        $vehicle2->setModel('Virage');
        $vehicle2->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle2->setImage('aston-martin-virage.jpg');
        $vehicle2->setDailyPrice(250);
        $vehicle2->setRegistration('AF-AAA-AA');
        $vehicle2->setAgency($agency);

        $vehicle3 = new Vehicle();
        $vehicle3->setName('Audi R8');
        $vehicle3->setBrand('Audi');
        $vehicle3->setModel('R8');
        $vehicle3->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle3->setImage('audi_r8_v10_rws_2018_4k-HD.jpg');
        $vehicle3->setDailyPrice(200);
        $vehicle3->setRegistration('AG-AAA-AA');
        $vehicle3->setAgency($agency);

        $vehicle4 = new Vehicle();
        $vehicle4->setName('Lamborghini Aventador');
        $vehicle4->setBrand('Lamborghini');
        $vehicle4->setModel('Aventador');
        $vehicle4->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle4->setImage('lamborghini-aventador.jpg');
        $vehicle4->setDailyPrice(220);
        $vehicle4->setRegistration('AH-AAA-AA');
        $vehicle4->setAgency($agency);

        $vehicle5 = new Vehicle();
        $vehicle5->setName('Lexus NX 300');
        $vehicle5->setBrand('Lexus');
        $vehicle5->setModel('NX 300');
        $vehicle5->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle5->setImage('Lexus_NX_300.jpg');
        $vehicle5->setDailyPrice(250);
        $vehicle5->setRegistration('AI-AAA-AA');
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
