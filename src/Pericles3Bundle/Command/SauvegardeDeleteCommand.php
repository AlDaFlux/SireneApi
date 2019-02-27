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


class SauvegardeDeleteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sauvegarde:delete');
        $this->setDescription("Supprimme une sauvegarde.. attention dangereux !");
        $this->setHelp("Supprimme une sauvegarde.. attention dangereux !");
        $this->addOption('sauvegarde_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de la sauvegarde",0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $sauvegardeId = $input->getOption('sauvegarde_id');
            
        $em = $doctrine->getEntityManager();
        if (! $sauvegardeId )
        {
            $output->writeln("<error>sauvegarde:delete --sauvegarde_id=?   </error>");
            return(null);
        }
        

        $sauvegarde = $em->getRepository("Pericles3Bundle:Sauvegarde")->findOneById($sauvegardeId);
        if (! $sauvegarde)
        {
            $output->writeln("<error>La sauvegarde ".$sauvegardeId." n'existe pas </error>");
            return(null);
        }
        else 
        {
            $etablissement=$sauvegarde->getEtablissement();
            $output->writeln("<info>Sauvegarde choisi: ".$sauvegarde."</info>");
            $output->writeln("<info>Etablissement choisi : ".$etablissement."</info>");
        }
        
        $etablissementController = new \Pericles3Bundle\Controller\BackOffice\SauvegardeController();
        $etablissementController->SetOutput($output);
        $etablissementController->SetEm($em);
        $etablissementController->deleteSauvegarde($sauvegarde);
         
        $output->writeln(" La sauvegarde a été bien été suprimée");
    }
}
