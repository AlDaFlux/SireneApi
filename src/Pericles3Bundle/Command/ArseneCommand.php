<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pericles3Bundle\Command;
use Pericles3Bundle\Entity\User;
use Pericles3Bundle\Entity\Etablissement;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\BufferedOutput;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Helper\Table;



/**
 * Command.
 */
abstract class ArseneCommand extends ContainerAwareCommand
{

    
    public $input;
    public $output;
    

    

    public function GetEm()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        return($em);
    }
    

    public function GetEtablissementById($id)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $etablissement = $em->getRepository('Pericles3Bundle:Etablissement')->findOneById($id);
        if ($etablissement)
        {
            return($etablissement);
        }
        else
        {
            $this->listAllEtablissement();
        }
        
    }
    
    public function GetAllEtablissements()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        return($em->getRepository('Pericles3Bundle:Etablissement')->findAll());
    }
    
    public function listAllEtablissement()
    {
        $this->listEtablissement($this->GetAllEtablissements());
    }
    
    public function listEtablissement($etablissements)
    {
         $table = new Table($this->output);
        $etablissementsAsPlainArrays = array_map(array('self',"arrayMapEtablissement") , $etablissements);
        $table
            ->setHeaders(['ISBN', 'Title', 'Author'])
            ->setRows($etablissementsAsPlainArrays)
        ;
        $table->render();
    }
    
    public function arrayMapEtablissement(Etablissement $etablissement) 
    {
        return [
               $etablissement->getId(),
               $etablissement->getNom(),
           ]; 
    }
     
    
    function deleteSauvegarde($sauvegarde)
    {
        $etablissementController = new \Pericles3Bundle\Controller\BackOffice\SauvegardeController();
        $etablissementController->SetOutput($this->output);
        $etablissementController->SetEm($this->GetEm());
        $etablissementController->deleteSauvegarde($sauvegarde);
    }
    
    
    

                            
                            
    
    
}
