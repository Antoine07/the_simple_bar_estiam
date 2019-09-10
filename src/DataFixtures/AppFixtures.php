<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Beer;
use App\Entity\Country;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AppFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    
        $faker = Faker\Factory::create('fr_FR');

        // countries
        $countries = ['belgium', 'french', 'English', 'germany'];
        $numbCountries = count($countries);
        $entitiesCountries =[];
        foreach ($countries as $name) {
            $country = new Country();
            $country->setEmail($faker->email);
            $country->setName($name);
            // $manager->persist($country); // persist cascade dans Beer
            $entitiesCountries[] = $country;
        }

        $count = 0;
        while ($count < 20) {
            $beer = new Beer;
            $beer->setName($faker->word); // attention j'ai changé title par name pour l'entité Beer
            $beer->setPublishedAt($faker->dateTime());
            $beer->setDescription($faker->text);
            $beer->setCountry($entitiesCountries[rand(0, $numbCountries - 1 )]);
            $beer->setPrice($faker->randomFloat(1, 3, 20));

            $manager->persist($beer);
            $count++;
        }

        $manager->flush();
    }

    function getOrder()
    {
        return 2;
    }
}
