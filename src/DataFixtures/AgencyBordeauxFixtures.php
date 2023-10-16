<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Vehicle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgencyBordeauxFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $agency = new Agency();
        $agency->setName('Agence Bordeaux');
        $agency->setDescription('Agence de location de voitures.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $agency->setAddress('8 Rue Eugene Jacquet');
        $agency->setTown('Bordeaux');
        $agency->setzipCode('33000');
        $agency->setPhone('05 45 01 02 03');
        $agency->setImage('Agence_Bordeaux.jpg');

        $vehicle1 = new Vehicle();
        $vehicle1->setName('Audi E-Tron');
        $vehicle1->setBrand('Audi');
        $vehicle1->setModel('E-Tron');
        $vehicle1->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle1->setImage('1-audi-e-tron.jpg');
        $vehicle1->setDailyPrice(100);
        $vehicle1->setRegistration('AA-AAA-AA');
        $vehicle1->setAgency($agency);

        $vehicle2 = new Vehicle();
        $vehicle2->setName('Audi Q8');
        $vehicle2->setBrand('Audi');
        $vehicle2->setModel('Q8');
        $vehicle2->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle2->setImage('Audi-Q8.jpg');
        $vehicle2->setDailyPrice(150);
        $vehicle2->setRegistration('BB-BBB-BB');
        $vehicle2->setAgency($agency);

        $vehicle3 = new Vehicle();
        $vehicle3->setName('Audi RS7');
        $vehicle3->setBrand('Audi');
        $vehicle3->setModel('RS7');
        $vehicle3->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle3->setImage('audi-rs7.jpg');
        $vehicle3->setDailyPrice(130);
        $vehicle3->setRegistration('CC-CCC-CC');
        $vehicle3->setAgency($agency);

        $vehicle4 = new Vehicle();
        $vehicle4->setName('Lamborghini Aventador');
        $vehicle4->setBrand('Lamborghini');
        $vehicle4->setModel('Aventador');
        $vehicle4->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle4->setImage('Lamborghini_aventador.jpg');
        $vehicle4->setDailyPrice(160);
        $vehicle4->setRegistration('DD-DDD-DD');
        $vehicle4->setAgency($agency);

        $vehicle5 = new Vehicle();
        $vehicle5->setName('Porsche 918 Spyder');
        $vehicle5->setBrand('Porsche');
        $vehicle5->setModel('918 Spyder');
        $vehicle5->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle5->setImage('Porsche_918_Spyder.jpg');
        $vehicle5->setDailyPrice(160);
        $vehicle5->setRegistration('EE-EEE-EE');
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
