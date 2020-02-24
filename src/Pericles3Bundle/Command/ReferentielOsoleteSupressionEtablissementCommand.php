<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Input\ArrayInput
;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class ReferentielOsoleteSupressionEtablissementCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('referentiel:etablissement-del-obs-ref');
        $this->setDescription("Supprimme les référentiels désuets pour un établissement");
        $this->setHelp("Supprimme les référentiels désuets pour un établissement");
        $this->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de l'établissement",0);
        $this->addOption('all-etab',null,InputOption::VALUE_NONE,"!! Tous les établissements");
        $this->addOption('softdeleteable',null,InputOption::VALUE_NONE,"désactive softdeleteable");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $etablissementId = $input->getOption('etablissement_id');
        $softdeleteable = $input->getOption('softdeleteable');

        $etablissement = $em->getRepository("Pericles3Bundle:Etablissement")->findOneById($etablissementId);
        
        $trashController = new \Pericles3Bundle\Controller\BackOffice\TrashController();
        $trashController->SetOutput($output);
        $trashController->SetEm($em);
        
        if ($softdeleteable)
        {
            $em->getFilters()->disable('softdeleteable');
        }

        if ($input->getOption('all-etab'))
        {
            $etablissements =$em->getRepository('Pericles3Bundle:Etablissement')->FindWithReferentielDesuet();
            if (count($etablissements))
            {
                $output->writeln("<error>".count($etablissements)." Etablissements à traiter ! </error>");

                foreach ($etablissements as $etablissement)
                {
                    $trashController->indexEtablissementDelOldRed($etablissement);
                }
            }
            else
            {
                $output->writeln("<info>Aucun établissement à traiter ! </info>");
            }
        }
        elseif (! $etablissement)
        {
            $output->writeln("<error>L'établissement ".$etablissement." n'exites pas</error>");
            return(0);
        }
        else
        {
            $output->writeln("Etablissement choisi : ");
            $output->writeln("--->".$etablissement);
            $trashController->indexEtablissementDelOldRed($etablissement);
        }
        
      
        
        
        
    }
}
