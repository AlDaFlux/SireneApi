<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Input\ArrayInput;
use DateTime;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchOldSauvegardeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:sauvegarde-old-do');
        $this->setDescription("Patchs toutes les sauvegarddes obsoletes... attention dangereux !");
        $this->setHelp("Patchs toutes les sauvegarddes obsoletes... attention dangereux !");
        $this->addOption('execution',null,InputOption::VALUE_NONE,"Affcihe juste les commandes");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $em->getConfiguration()->setSQLLogger(null);

         
        
        $sauvegardes= new \Doctrine\Common\Collections\ArrayCollection(); 
        foreach ($em->getRepository('Pericles3Bundle:Sauvegarde')->findAll() as $sauvegarde )
        {
            if ($sauvegarde->getReferentielDesuet()) $sauvegardes->add($sauvegarde);
        }

        foreach ($sauvegardes as $sauvegarde )
        {
            $em->clear();
            $output->writeln("--->".$sauvegarde);
            $etablissement=$sauvegarde->GetEtablissement();
            $output->writeln("--Etablissement ->".$etablissement);
            $publicCible=$etablissement->GetReferentielPublic();
            $publicSource=$sauvegarde->GetReferentiel();
            $output->writeln("----- Public Source ---->".$publicSource);
            $output->writeln("----- Public Cible ---->".$publicCible);
            $patch=$publicSource->getPatchCiblePatch($publicCible);
            $output->writeln("-------Patch-->".$patch);

            if ($input->getOption('execution'))
            {
                $command = $this->getApplication()->find('patch:sauvegarde-do');
                $arguments = array('command' => 'patch:sauvegarde-do','--sauvegarde_id'  => $sauvegarde->GetId(),'--patch_id'  => $patch->GetId());
                $PatchEtabInput = new ArrayInput($arguments);
                $command->run($PatchEtabInput, $output);
                
            }
            else
            {
                $output->writeln( "php bin/console patch:sauvegarde-do --sauvegarde_id=".$sauvegarde->GetId()." --patch_id=".$patch->GetId());
            }
            
        }

        
        $em->clear();
        if ($input->getOption('execution'))
        {
                $output->writeln(" Les sauvegardes ont été patchés");
        }
    }
}
