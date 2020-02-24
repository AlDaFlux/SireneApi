<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class EtablissementDeleteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('etablissement:delete');
        $this->setDescription('suprimme un établissement !!! !Atention ');
        $this->setHelp("suprimme un établissement !!! !Atention ");
        $this->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"L'identifiant du référentiel public ",0);
        //$this->addOption('force-delete',null,InputOption::VALUE_NONE,"Passe en force");
        $this->addOption('force-softdeleteable',null,InputOption::VALUE_NONE,"désactive softdeleteable");
        $this->addOption('delete-cascade',null,InputOption::VALUE_NONE,"supprime en cascade les utilisateurs, les tats....");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
                
//        $force = $input->getOption('force-delete');
        $force_softdeleteable = $input->getOption('force-softdeleteable');
        $deleteCascade = $input->getOption('delete-cascade');

                
        $em = $doctrine->getEntityManager();
        $etablissementId = $input->getOption('etablissement_id');
        
        if ($force_softdeleteable)
        {
            $em->getFilters()->disable('softdeleteable');
        }
        
        $etablissement = $em->getRepository("Pericles3Bundle:Etablissement")->findOneById($etablissementId);
        if (! $etablissement)
        {
            $output->writeln("<error>L'établissement ".$etablissementId." n'exites pas<error>");
        }
        else
        {         
            $output->writeln("referentiel choisi : ");
            $output->writeln("<info>".$etablissement."</info>");
            
            
            
            $output->writeln("<info>".$etablissement->getNbDomainesExterne()."</info>");
            $output->writeln("<info>".$etablissement->getNbUsers()."</info>");
            $output->writeln("<info>".$etablissement->getNbSauvegardes()."</info>");
            
            
            $etablissementController = new \Pericles3Bundle\Controller\BackOffice\EtablissementController();
            $etablissementController->SetOutput($output);
            $etablissementController->SetEm($em);
            $etablissementController->deleteSaisiesEtablissement($etablissement,true);
            $etablissementController->deleteObjectifsOperationnelEtablissement($etablissement);
            
            $etablissementController->JustDeleteBiblio($etablissement);
            $etablissementController->unlinkDemande($etablissement);
            $etablissementController->deleteReferentielEtablissement($etablissement);
            $etablissementController->deleteReferentielObsoleteEtablissement($etablissement);
            
            
//            $etablissementController->deleteReferentielExterne($etablissement);
            $etablissementController->unlinkFiness($etablissement);
            $etablissementController->deleteOOUsers($etablissement,false);
//            $etablissementController->deleteUsers($etablissement,false);
            /*
            $em->remove($etablissement);
            
            $em->flush();
            */
            if ($deleteCascade or $force_softdeleteable)
            {
                $users = $em->getRepository("Pericles3Bundle:User")->findByEtablissement($etablissement);
                foreach ($users as $user)
                {
                    foreach ($user->GetBibliotheques() as $biblio)
                    {
                        $em->remove($biblio);
                        $em->flush();
                    }
                    foreach ($user->GetConstats() as $constat)
                    {
                        $em->remove($constat);
                        $em->flush();
                    }
                    foreach ($user->getStatsUserConnect() as $stats)
                    {
                        $output->writeln("<info>Stats</info>");
                        $em->remove($stats);
                        $em->flush();
                    }
                    
                    $em->remove($user);
                    $em->flush();
                    $output->writeln("<info>ICI</info>");
                }
                $em->flush();

                foreach ($etablissement->getPatchToDo() as $patchToDo)
                {
                    $em->remove($patchToDo);
                    $em->flush();
                }
                
                
                
                $etablissement = $em->getRepository("Pericles3Bundle:Etablissement")->findOneById($etablissementId);
            }
            $em->remove($etablissement);
            $em->flush();
        }
    }
}
