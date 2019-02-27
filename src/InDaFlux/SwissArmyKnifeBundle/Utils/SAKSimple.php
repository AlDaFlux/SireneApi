<?php

#use Symfony\Component\DependencyInjection\ContainerInterface;


namespace InDaFlux\SwissArmyKnifeBundle\Utils;



class SAKSimple 
{
    public function RandomString($nb_car=12, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789')
    {
        $nb_lettres = strlen($chaine) - 1;
        $generation = '';
        for($i=0; $i < $nb_car; $i++)
        {
            $pos = mt_rand(0, $nb_lettres);
            $car = $chaine[$pos];
            $generation .= $car;
        }
        return $generation;
    }
    
    
}

