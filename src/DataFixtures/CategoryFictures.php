<?php

namespace App\DataFixtures;
use Faker;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use App\Entity\Category;

class CategoryFictures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        // une seule catégorie pour une bière
        $categoryNormals = ['blonde', 'brune', 'blanche'];
        foreach ($categoryNormals as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setTerm('normal');

            $manager->persist($category);
        }

        // on peut avoir plusieurs "categories" spéciales par bière
        $categoriesSpecials = ['houblon', 'rose', 'menthe', 'grenadine', 'réglisse', 'marron', 'whisky', 'bio'];
        foreach ($categoriesSpecials as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setTerm('special');

            $manager->persist($category);
        }

        $manager->flush();
    }

    function getOrder()
    {
        return 1;
    }
}
