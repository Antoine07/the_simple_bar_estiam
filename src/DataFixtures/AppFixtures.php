<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Beer;
use App\Entity\Country;
use App\Entity\Category; // alias de namespace

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AppFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    
        // les catégories spéciales
        // Category::class permet de spécifier le namespace
        //$categoriesSpecials = $manager->getRepository(Category::class)->findBy(['term' => 'special']);
        $categoriesSpecials = $manager->getRepository(Category::class)->findByTerm('special');

        // dump($categoriesSpecials); // debug
        $nbCatSpecials = count($categoriesSpecials);

        // les catégories dites normales
        $categoriesNormals = $manager->getRepository(Category::class)->findBy(['term' => 'normal']);
        // dump($categoriesNormals); // debug

        $shuffleSlice = function($data, $start, $end){
            shuffle($data);

            return array_slice($data, $start, $end);
        };

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

            $categoryNormal = $shuffleSlice($categoriesNormals, 0, 1)[0];
            $beer->addCategory($categoryNormal);

            foreach(
                $shuffleSlice($categoriesSpecials, 0, rand(1, $nbCatSpecials)) as $cat
            ) $beer->addCategory($cat);

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
