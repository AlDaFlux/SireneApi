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


class SauvegardeDeleteCorbeilleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sauvegarde:delete-corbeille');
        $this->setDescription("Supprimme les sauvegarde a la corbeille (ancien sofdelete)");
        $this->setHelp("Supprimme les sauvegarde a la corbeille (ancien sofdelete)");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();

        $oldsauvegardes = $em->getRepository('Pericles3Bundle:Sauvegarde')->findOldCorbeille();
        
        foreach ($oldsauvegardes as $oldSauvegarde)
        {
            $output->writeln(" Supression de ".$oldSauvegarde);
            $em->remove($oldSauvegarde);
            $em->flush();            
        }
        
        
        
        
            
         
        $output->writeln(" Les vielles sauvegardes ont été supprimés");
    }
}
