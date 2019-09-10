<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
// Pour utiliser les services importer la classe suivante AbstractController
// Mais vous pouvez utiliser le autowiring => injectez les dépendance directement dans le constructeur
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// vous devez utiliser cette classe pour les annotations
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Doctrine
use Twig\Environment; // Twig

use App\Entity\Beer;

class BarController
{

    private $twig;
    private $manager;

    public function __construct(Environment $twig, EntityManagerInterface $manager)
    {
        $this->twig = $twig;
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="home") 
     */
    public function index() : Response
    {
        $beers = [
            "Philomenn Blonde, 5,6 %",
            "Philomenn Rousse, 6,0 %",
            "Philomenn Stout, 4,5 %",
            "Philomenn Triple 'Spoum', 9,0 %",
            "Philomenn Blonde Tourbée, au malt fumé à la Tourbée, 8,0 %",
            "Philomenn Blanche, 5,6 %",
            "Philomenn Brune 'Spoum des Talus', bière millésimée à la mûre sauvage, 7,0-8,5 %",
            "Philomenn HAC, bière blonde houblonnée à cru, 6,5 %",
        ];

        $beers = $this->manager->getRepository(Beer::class)->findAll();

        return new Response($this->twig->render('/bar/home.html.twig', [
            'beers' => $beers
        ]));
    }

    /**
     * @Route("/bar/{slug}", name="bar")
     */
    public function bar($slug)
    {
        // double cote permettent d'interpréter
        // la variable php
        return new Response("URL bar : $slug");
    }
}