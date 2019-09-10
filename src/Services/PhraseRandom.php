<?php
namespace App\Services;

use Symfony\Component\Finder\Finder;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PhraseRandom
{

    private $finder;
    private $logger;

    public function __construct(
        LoggerInterface $logger,
        ParameterBagInterface $params
    ) {
        $this->finder = new Finder();
        $this->logger = $logger;
        $this->params = $params;
    }


    public function get($rand = 1)
    {

        $this->logger->info('About to find a happy message!');

        $this->finder->files()->in($this->params->get('app.dir_phrases'))
                              ->notContains('dolor')
                              ->notContains('bad');

        dump(count($this->finder));

        if ($this->finder->hasResults()) {
            $rand = rand();
            foreach ($this->finder as $file) {
                $contents = $file->getContents();
                dump($contents);
                // ...
            }
        }
    }
}
