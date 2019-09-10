<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
// Pour utiliser les services importer la classe suivante AbstractController
// Mais vous pouvez utiliser le autowiring => injectez les dépendance directement dans le constructeur
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// vous devez utiliser cette classe pour les annotations
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Doctrine
use App\Services\PhraseRandom;
use Twig\Environment; // Twig
use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Beer;

class BarController
{
    use TraitMenu;

    private $twig;
    private $manager;
    private $phrase;

    public function __construct(
        Environment $twig,
        EntityManagerInterface $manager,
        PhraseRandom $phrase
    ) {
        $this->twig = $twig;
        $this->manager = $manager;
        $this->phrase = $phrase;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repository = $this->manager->getRepository(Beer::class);
        // SELECT * FROM beers
        $beers = $repository->findAll();
        return new Response($this->twig->render('bar/index.html.twig', [
            // données passées à la vue
            'controller_name' => 'BarController',
            'title' => 'La page index des bières', // pour le title html
            'beers' => $beers
        ]));
    }

    /**
     * @Route("/country/{id}", name="country_beer")
     */
    public function country(int $id)
    {
        $repository = $this->manager->getRepository(Country::class);
        // SELECT * FROM country where id = $id
        $country = $repository->find($id);
        return new Response($this->twig->render('bar/country.html.twig', [
            'title' => $country->getName(),
            'beers' => $country->getBeers(),
        ]));
    }

    /**
     * @Route("/category/{id}", name="category_beer")
     */
    public function category(int $id)
    {
        $repository = $this->manager->getRepository(Category::class);
        // SELECT * FROM category where id = $id
        $category = $repository->find($id); // entité Catgory hydraté 

        return new Response($this->twig->render('bar/category.html.twig', [
            'title' => $category->getName(),
            'beers' => $category->getBeers(),
        ]));
    }

    /**
     * @Route("/beer/{id}", name="show_beer")
     */
    public function showBeer($id)
    {
        return new Response($this->twig->render('bar/beer.html.twig', [
            // données passées à la vue
            'controller_name' => 'BarController',
            'title' => 'La page index des bières belges',   // pour le title html
            'beers' => $this->beers_country[$id]
        ]));
    }

}
