<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;

trait TraitMenu{

    public function mainMenu(string $routeName, array $params)
    {
        $repository = $this->manager->getRepository(Category::class);

        return new Response( $this->twig->render(
            'partials/menu.html.twig',
            [
                'categories' => $repository->findByTerm('normal'),
                'routeName' => $routeName,
                'id' => $params['id'] ?? null,
                'phrase' => $this->phrase->get()
            ]
        ));
    }
}