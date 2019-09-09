<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
// Pour utiliser les services importer la classe suivante AbstractController
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// vous devez utiliser cette classe pour les annotations
use Symfony\Component\Routing\Annotation\Route;

class BarController extends AbstractController
{
    /**
    * @Route("/", name="home")
    */
    public function index()
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
        
        return $this->render('/bar/home.html.twig', [
            'beers' => $beers
            ]);
        }
        
        /**
        * @Route("/bar/{slug}", name="bar")
        */
        public function bar($slug){
            
            // double cote permettent d'interpréter
            // la variable php
            return new Response("URL bar : $slug");
        }
    }