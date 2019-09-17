<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Dataset;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DatasetFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       
        $faker = Faker\Factory::create('fr_FR');

        // echantillon de 30 valeurs que l'on répète 90 fois
        

        $manager->flush();
    }
}
