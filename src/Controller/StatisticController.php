<?php

namespace App\Controller;

use Twig\Environment; // Twig
use App\Entity\Dataset;
use Doctrine\ORM\EntityManagerInterface; // Doctrine
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Faker;

class StatisticController extends AbstractController
{
    use TraitMenu;

    private $manager;
    private $twig;
    private $dataSet;

    public function __construct(
        EntityManagerInterface $manager,
        Environment $twig
    ) {
        $this->manager = $manager;
        $this->twig = $twig;
    }

    /**
     * @Route("/statistic", name="statistic")
     */
    public function index(): Response
    {

        return new Response(
            $this->twig->render('statistic/index.html.twig')
        );
    }

    /**
     * @Route("/generate", name="generate")
     */
    public function generate(): JsonResponse
    {
        $dataset = []; // les données name student/heure(s)

        // pour des stats ...
        $timeMin = 0;
        $timeMax = 30;
        $nbStudent = 1000;
        $total = 0;


        $faker = Faker\Factory::create();
        $times = range($timeMin, $timeMax); // 0 à 30 heure(s) d'étude

        // fonction pratique pour tirer une heure aléatoirement
        $alea = function ($data) {
            return array_rand($data);
        };

        foreach ( range(1, $nbStudent) as $num ) {
            $timeAlea = $alea($times);
            $dataset[$faker->unique()->name()] = $timeAlea;

            // info supplémentaire stat ...
            $total += $timeAlea;
        }

        // calcul de la moynne et de la variance stat ...
        $avg = round($total / $nbStudent, 2);
        $std = 0;
        foreach ($dataset as $k => $time) $std += ($time  - $avg) ** 2;

        $dataset['nbStudent'] = $nbStudent;
        $dataset['totalHour'] = $total;
        $dataset['avgHour'] = $avg;
        $dataset['stdHour'] = round(sqrt($std / $nbStudent), 2);
        $dataset['varianceHour'] = round($std / $nbStudent, 2);

        // persit les données en base de données
        $repository = $this->manager->getRepository(Dataset::class);
        $entity = $repository->findBy(['name' => 'Study time']);

        if (count($entity) > 0) {
            $entity[0]->setName('Study time')
                ->setData($dataset);
            $this->manager->persist($entity[0]);
        } else {
            $entityDataset = new Dataset;
            $entityDataset->setName('Study time')
                ->setData($dataset);
            $this->manager->persist($entityDataset);
        }

        $this->manager->flush();

        return new JsonResponse($dataset);
    }

    /**
     * @Route("/diagram", name="diagram")
     */
    public function diagram(): JsonResponse
    {
        $repository = $this->manager->getRepository(Dataset::class);
        $entity = $repository->findBy(['name' => 'Study time']);

        if (count($entity) > 0) {
            $dataset = $entity[0]->getData();
            return new JsonResponse($dataset);
        }

        return new JsonResponse([]);
    }
}
