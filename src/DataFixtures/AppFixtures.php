<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Beer;
use App\Entity\Country;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    
        $faker = Faker\Factory::create('fr_FR');

        // country
        $country = new Country;
        $country->setName($faker->word);
        //$manager->persist($country); // pas la peine si vous avez mis le cascade={"persist"}

        // $manager->flush();

        $count = 0;
        while ($count < 20) {
            $beer = new Beer;
            $beer->setName($faker->word); // attention j'ai changé title par name pour l'entité Beer
            $beer->setPublishedAt($faker->dateTime());
            $beer->setDescription($faker->text);

            // $country = new Country;
            // $country->setName($faker->word);

            $beer->setCountry($country);
            $manager->persist($beer);
            $count++;
        }

        $manager->flush();
    }
}
