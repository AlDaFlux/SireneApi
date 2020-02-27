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
use Pericles3Bundle\Entity\ReferentielPublic;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\Gestionnaire;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\BufferedOutput;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;


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


    
    
    public function GetReferentielPublicById($id)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $referentielPublic = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($id);
        if ($referentielPublic)
        {
            return($referentielPublic);
        }
        else
        {
            $this->listAllReferentielPublic();
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
    
    public function GetGestionnaireById($id)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $gestionnaire = $em->getRepository('Pericles3Bundle:Gestionnaire')->findOneById($id);
        if ($gestionnaire)
        {
            return($gestionnaire);
        }
        else
        {
            $this->listAllGestionnaire();
        }
    }
    
    public function GetAllEtablissements()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        return($em->getRepository('Pericles3Bundle:Etablissement')->findAll());
    }
    public function GetAllGestionnaires()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        return($em->getRepository('Pericles3Bundle:Gestionnaire')->findBy([], ['id' => 'ASC']));
    }
    public function GetAllReferentielsPublic()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        return($em->getRepository('Pericles3Bundle:ReferentielPublic')->findVeryAll());
    }
    
    public function listAllGestionnaire()
    {
        $this->listGestionnaire($this->GetAllGestionnaires());
    }
    public function listAllEtablissement()
    {
        $this->listEtablissement($this->GetAllEtablissements());
    }
    public function listAllReferentielPublic()
    {
        $this->listReferentielPublic($this->GetAllReferentielsPublic());
    }
    
    
    public function listGestionnaire($gestionnaires)
    {
         $table = new Table($this->output);
        $gestionnairesAsPlainArrays = array_map(array('self',"arrayMapGestionnaire") , $gestionnaires);
        $table
            ->setHeaders(['id', 'Nom', 'Etablissements', 'TEST'])
            ->setRows($gestionnairesAsPlainArrays)
        ;
        $table->render();
    }
       
    
    public function listEtablissement($etablissements)
    {
         $table = new Table($this->output);
        $etablissementsAsPlainArrays = array_map(array('self',"arrayMapEtablissement") , $etablissements);
        $table
            ->setHeaders(['id', 'Nom', 'Referentiel', 'NbUtilisateurs'])
            ->setRows($etablissementsAsPlainArrays)
        ;
        $table->render();
    }
       
    public function listReferentielPublic($refs)
    {
        $table = new Table($this->output);
        $etablissementsAsPlainArrays = array_map(array('self',"arrayMapReferentielPublic") , $refs);
        $table
            ->setHeaders(['id', 'public', 'annne', 'Etablissements', 'version', 'obsolete', 'en cours de dev', 'actif'])
            ->setRows($etablissementsAsPlainArrays)
        ;
        $table->render();
    }
    
    
    
    public function arrayMapEtablissement(Etablissement $etablissement) 
    {
        return [
               $etablissement->getId(),
               $etablissement->getNom(),
               $etablissement->getReferentielPublic(),
               $etablissement->getNbUsers(),
           ]; 
    }
      public function arrayMapGestionnaire(Gestionnaire $gestionnaire) 
    {
        return [
               $gestionnaire->getId(),
               $gestionnaire->getNom(),
               $gestionnaire->getNbEtablissements(),
               $gestionnaire->IsReel()?"":"*",
           ]; 
    }
     
    
    public function arrayMapReferentielPublic(ReferentielPublic $ref) 
    {
        return [
               $ref->getId(),
               $ref->getPublic(),
               $ref->getYear(),
               $ref->GetNbEtablissements(),
               $ref->getVersion(),
               ($ref->getObsolete() ? "X":""),
               ($ref->getFini() ? "":"X"),
               ($ref->getFiniAndLast() ? "X":""),
           ]; 
    }
     
    
    function deleteSauvegarde($sauvegarde)
    {
        $etablissementController = new \Pericles3Bundle\Controller\BackOffice\SauvegardeController();
        $etablissementController->SetOutput($this->output);
        $etablissementController->SetEm($this->GetEm());
        $etablissementController->deleteSauvegarde($sauvegarde);
    }
    
    
    
    function runSQL($sql)
    {
          
        $command = $this->getApplication()->find('doctrine:query:sql');
        $arguments = array('command' => 'doctrine:query:sql','sql' => $sql);
        $args = new ArrayInput($arguments);
        $command->run($args, $this->output);
    }
    

                            
                            
    
    
}
