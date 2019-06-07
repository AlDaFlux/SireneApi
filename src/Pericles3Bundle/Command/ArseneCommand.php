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

use Pericles3Bundle\Entity\PatchToDoBatch;
use Pericles3Bundle\Entity\PatchToDo;


use DateTime;



/**
 * Command.
 */
abstract class ArseneCommand extends ContainerAwareCommand
{

    
    public $input;
    public $output;
    

    
    function persist($element)
    {
        $this->GetEm()->persist($element);
    }
 
    function flush()
    {
        $this->GetEm()->flush();
    }
 
    

    public function GetEm()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        return($em);
    }
    
    
    
    public function GetPatchTodoBatchEnCours()
    {
        $patchtoDoBatch = $this->GetEm()->getRepository('Pericles3Bundle:PatchToDoBatch')->findEnCours();
        if ($patchtoDoBatch)
        {
            $this->output->writeln("<info>Un patch batch est en cours depuis ".$patchtoDoBatch->getDateDebutPatch()->diff(new DateTime())->format('%h heures %i minutes %s secondes')."</info>");
        }
        else
        {
            $this->output->writeln("<info>Aucun patch batch en cours</info>");
        }
        return($patchtoDoBatch);
    }
    
    
    
    public function GetPatchEnCours()
    {
        return($this->GetPatchTodoEnCours() or $this->GetPatchTodoBatchEnCours());
    }

    
    
    public function GetPatchTodoEnCours()
    {
        $patchtoDo = $this->GetEm()->getRepository('Pericles3Bundle:PatchToDo')->findEnCours();
        if ($patchtoDo)
        {
            $this->output->writeln("<info>Un batch est en cours </info>");
        }
        else
        {
            $this->output->writeln("<info>Aucun patch batch en cours</info>");
        }
        return($patchtoDo);
    }
    
    
    public function PatchTodoBatchStart()
    {
        $patchtoDoBatch = $this->GetPatchTodoBatchEnCours();
        
        
            if ($patchtoDoBatch)
            {
                $this->output->writeln("<error>Patch déja en cours ! </error>");
            }
            else
            {
                $this->output->writeln("<info>Démarrage d'un batch patch</info>");
                $patchtoDoBatch = new PatchToDoBatch;
                $patchtoDoBatch->setDateDebutPatch(new DateTime());
                $this->persist($patchtoDoBatch);
                $this->flush();
            }
    }
    
    public function PatchTodoBatchStop()
    {
                $patchtoDoBatch = $this->GetPatchTodoBatchEnCours();

            if ($patchtoDoBatch)
            {
                $this->output->writeln("<info>Fin du batch patch</info>");
                $patchtoDoBatch->setDateFinPatch(new DateTime());
                $this->persist($patchtoDoBatch);
                $this->flush();
            }
            else
            {
                $this->output->writeln("<error>Aucun patch batch a stopper ! </error>");
            }
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
