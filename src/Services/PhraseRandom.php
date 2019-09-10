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
            $this->finder = new Finder(); // ce n'est pas un service une dépendance
            // par contre ces deux instances sont des services que l'on a récupéré dans
            // le conteneur de service
            $this->logger = $logger;
            $this->params = $params;
        }
        
        
        public function get($rand = 1): string
        {
            $this->finder->files()->in($this->params->get('app.dir_phrases'))
            ->notContains('dolor')
            ->notContains('bad');
            
            $rand = rand(1, count($this->finder));
            $count = 1;
            
            if ($this->finder->hasResults()) {
                
                foreach ($this->finder as $file) {
                    if ($count == $rand) {
                        $this->logger->info($file);

                        return $file->getContents();
                    }

                    $count++;
                }
            }

            return '';
        }
    }
    