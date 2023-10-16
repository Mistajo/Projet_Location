<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Vehicle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AgencyLyonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $agency = new Agency();
        $agency->setName('Agence Lyon');
        $agency->setDescription('Agence de location de voitures.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $agency->setAddress('8 Rue du Plat');
        $agency->setTown('Lyon');
        $agency->setzipCode('69002');
        $agency->setPhone('04 45 01 02 03');
        $agency->setImage('Agence_lyon.jpg');

        $vehicle1 = new Vehicle();
        $vehicle1->setName('Aston Martin DB7 Vantage Volante');
        $vehicle1->setBrand('Aston Martin');
        $vehicle1->setModel('DB7 Vantage Volante');
        $vehicle1->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle1->setImage('Aston_Martin_DB7_Vantage_Volante.jpg');
        $vehicle1->setDailyPrice(200);
        $vehicle1->setRegistration('KK-KKK-KK');
        $vehicle1->setAgency($agency);

        $vehicle2 = new Vehicle();
        $vehicle2->setName('Lamborghini Boss Talks');
        $vehicle2->setBrand('Lamborghini');
        $vehicle2->setModel('Boss Talks');
        $vehicle2->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle2->setImage('Lamborghini_Boss_Talks_LM002.jpg');
        $vehicle2->setDailyPrice(200);
        $vehicle2->setRegistration('LL-LLL-LL');
        $vehicle2->setAgency($agency);

        $vehicle3 = new Vehicle();
        $vehicle3->setName('Lamborghini Urus');
        $vehicle3->setBrand('Lamborghini Urus');
        $vehicle3->setModel('Urus');
        $vehicle3->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle3->setImage('Lamborghini_Urus.jpg');
        $vehicle3->setDailyPrice(200);
        $vehicle3->setRegistration('MM-MMM-MM');
        $vehicle3->setAgency($agency);

        $vehicle4 = new Vehicle();
        $vehicle4->setName('Range Rover Autobiography V8');
        $vehicle4->setBrand('Range Rover');
        $vehicle4->setModel('Autobiography V8');
        $vehicle4->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle4->setImage('land-rover_range-rover_Autobiography_V8.jpg');
        $vehicle4->setDailyPrice(200);
        $vehicle4->setRegistration('NN-NNN-NN');
        $vehicle4->setAgency($agency);

        $vehicle5 = new Vehicle();
        $vehicle5->setName('Mercedes GLS 2020');
        $vehicle5->setBrand('Mercedes');
        $vehicle5->setModel('GLS 2020');
        $vehicle5->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elementum eu facilisis sed odio morbi. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Arcu risus quis varius quam quisque id. Eget arcu dictum varius duis at consectetur lorem donec. Mauris pellentesque pulvinar pellentesque habitant morbi. Fermentum leo vel orci porta non pulvinar neque laoreet suspendisse. Nisi scelerisque eu ultrices vitae auctor.');
        $vehicle5->setImage('Mercedes_GLS_2020.jpg');
        $vehicle5->setDailyPrice(250);
        $vehicle5->setRegistration('OO-OOO-OO');
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
