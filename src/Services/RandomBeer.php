<?php
namespace App\Services;

class RandomBeer{

    private $manager;

    public function __construct( $manager)
    {
        $this->manager = $manager;
    }

}