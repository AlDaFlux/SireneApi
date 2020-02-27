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


class GestionnaireDeleteCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('gestionnaire:delete');
        $this->setDescription('suprimme un gestionnaire et tous ces établissements !!! !Atention ');
        $this->setHelp("Suprimme un gestionnaire et tous ces établissements !! !Atention ");
        $this->addOption('gestionnaire_id',null,InputOption::VALUE_REQUIRED,"L'identifiant du gestionnaire ",0);
        $this->addOption('delete-cascade',null,InputOption::VALUE_NONE,"supprime en cascade les etablissements....");
        $this->addOption('force-softdeleteable',null,InputOption::VALUE_NONE,"désactive softdeleteable");
        $this->setHelp(<<<'HELP'
            <info>gestionnaire:delete</info> <comment> --gestionnaire_id=?</comment> Supprimme un gestionnaire sans établissement (soft delete)
            <info>gestionnaire:delete</info> <comment> --force-softdeleteable  --gestionnaire_id=?</comment> Supprimme un gestionnaire sans établissement (hard delete)
            <info>gestionnaire:delete</info> <comment> --delete-cascade --force-softdeleteable  --gestionnaire_id=?</comment> Supprimme un gestionnaire <error>ET TOUS CES ETABLISSEMENTS  </error>(hard delete)
HELP
            )
        ;
           
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
                $this->input=$input;
        $this->output=$output;

        
        $doctrine = $this->getContainer()->get('doctrine');
        $force_softdeleteable = $input->getOption('force-softdeleteable');
        $deleteCascade = $input->getOption('delete-cascade');
        $em = $doctrine->getEntityManager();
        $gestionnaireId = $input->getOption('gestionnaire_id');
        
        $gestionnaire = $this->GetGestionnaireById($gestionnaireId);
   
        
        if ($force_softdeleteable)
        {
            $em->getFilters()->disable('softdeleteable');
        }
             
        
        if (! $gestionnaire)
        {
            $output->writeln("<error>L'établissement ".$gestionnaireId." n'exites pas<error>");
        }
        else
        {         
            
            $output->writeln("Etablissement  choisi : ".$gestionnaire.'('.$gestionnaire->GetId().')');
            $output->writeln("<info>Nombre d'éatblissement : ".$gestionnaire->getNbEtablissements()."</info>");
            if ((!$deleteCascade) && $gestionnaire->getNbEtablissements())
            {
                $output->writeln("<error>Vous ne pouvez pas supprimmer cet gestionnaires car il a des établissements (".$gestionnaire->getNbEtablissements().")</error>");
                $output->writeln("<info>gestionnaire:delete</info> <comment> --delete-cascade --force-softdeleteable  --gestionnaire_id=".$gestionnaire->GetId()."</comment> Supprimme un gestionnaire <error>ET TOUS CES ETABLISSEMENTS  </error>(hard delete)");
                return(0);
            }
            
            if($gestionnaire->getHasFacturePrestas())
            {
                $output->writeln("<error>Vous ne pouvez pas supprimmer cet gestionnaires car il a des factures</error>");
                return(0);
            }
            
            if ($deleteCascade)
            {
                foreach ($gestionnaire->GetEtablissements() as $etablissement)
                {
                    $output->writeln("<info> ----SUPPRESION ETABLISSEMENT : ".$etablissement."</info>");
                    $command = $this->getApplication()->find('etablissement:delete');
                    $arguments = array('command' => 'etablissement:delete','--etablissement_id'  => $etablissement->GetId(), "--delete-cascade"=> true);
                    $EtabInput = new ArrayInput($arguments);
                    $command->run($EtabInput, $output);
                    $output->writeln("<info> ----SUPPRESION ETABLISSEMENT : OK !!!   </info>");
                }                
            }
            
            
            foreach ($gestionnaire->getPericles() as $pericles)
            {
                $pericles->SetGestionnaire(null);
                $em->persist($pericles);
                $em->flush();
            }
            foreach ($gestionnaire->getDemandesEtablissementGestionnaireExistant() as $demande)
            {
                $demande->SetGestionnaire(null);
                $em->persist($demande);
                $em->flush();
            }
            /*
            foreach ($gestionnaire->getDemandeGestionnaire() as $demande)
            {
                $demande->SetGestionnaire(null);
                $em->persist($demande);
                $em->flush();
            }
                    
 
            foreach ($gestionnaire->getFiness() as $finess)
            {
                $finess->SetGestionnaire(null);
                $em->persist($finess);
                $em->flush();
            }
                    */
            foreach ($gestionnaire->getBibliotheques() as $biblio)
            {
                $em->remove($biblio);
                $em->flush();
            }
            foreach ($gestionnaire->getUsers() as $user)
            {
                foreach ($user->getStatsUserConnect() as $stats)
                {
                      $output->writeln("<info>Stats</info>");
                      $em->remove($stats);
                      $em->flush();
                }
                $em->remove($user);
                $em->flush();
            }
            
            $em->remove($gestionnaire);
            $em->flush();
            
            
            
        }
    }
}
