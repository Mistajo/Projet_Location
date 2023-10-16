<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Vehicle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgencyLilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $agency = new Agency();
        $agency->setName('Agence Lille');
        $agency->setDescription('Agence de location de voitures.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $agency->setAddress('8 Rue de Solferino');
        $agency->setTown('Lomme');
        $agency->setzipCode('76000');
        $agency->setPhone('03 45 01 02 03');
        $agency->setImage('Agence_Lille.jpg');


        $vehicle1 = new Vehicle();
        $vehicle1->setName('Bugatti Chiron');
        $vehicle1->setBrand('Buggatti');
        $vehicle1->setModel('Chiron');
        $vehicle1->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle1->setImage('Bugatti_Chiron.jpg');
        $vehicle1->setDailyPrice(200);
        $vehicle1->setRegistration('FF-FFF-FF');
        $vehicle1->setAgency($agency);

        $vehicle2 = new Vehicle();
        $vehicle2->setName('Bugatti Veyron');
        $vehicle2->setBrand('Bugatti');
        $vehicle2->setModel('Veyron');
        $vehicle2->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle2->setImage('Bugatti_Veyron.jpg');
        $vehicle2->setDailyPrice(200);
        $vehicle2->setRegistration('GG-GGG-GG');
        $vehicle2->setAgency($agency);

        $vehicle3 = new Vehicle();
        $vehicle3->setName('Ferrari F8 Tributo');
        $vehicle3->setBrand('Ferrari');
        $vehicle3->setModel('F8-Tributo');
        $vehicle3->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle3->setImage('Ferrari_F8_Tributo.jpg');
        $vehicle3->setDailyPrice(200);
        $vehicle3->setRegistration('HH-HHH-HH');
        $vehicle3->setAgency($agency);

        $vehicle4 = new Vehicle();
        $vehicle4->setName('Ferrari Laferrari');
        $vehicle4->setBrand('Ferrari');
        $vehicle4->setModel('LaFerrari');
        $vehicle4->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle4->setImage('FERRARI_LAFERRARI.jpg');
        $vehicle4->setDailyPrice(200);
        $vehicle4->setRegistration('II-III-II');
        $vehicle4->setAgency($agency);

        $vehicle5 = new Vehicle();
        $vehicle5->setName('Lamborghini Countach');
        $vehicle5->setBrand('Lamborghini');
        $vehicle5->setModel('Countach');
        $vehicle5->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle5->setImage('Lamborghini_Countach.jpg');
        $vehicle5->setDailyPrice(200);
        $vehicle5->setRegistration('JJ-JJJ-JJ');
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
