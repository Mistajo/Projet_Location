<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Vehicle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VehicleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {



        $manager->flush();
    }
}
