<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchDeleteLiasonCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:delete-liason');
        $this->setDescription('Supprimme le liasons pour le patch.');
        $this->setHelp("Supprimme le liasons pour le patch.");
        $this->addOption('patch_id',null,InputOption::VALUE_REQUIRED,"L'identifiant du patch ",0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $patchId = $input->getOption('patch_id');
        $patch = $em->getRepository("Pericles3Bundle:Patch")->findOneById($patchId);
        
        if (! $patch)
        {
            $output->writeln("<error>Le patch ".$patchId." n'exites pas<error>");
            
        }
        else
        {
            $output->writeln("Patch choisi : ");
            $output->writeln("--->".$patch);
            $controller = new \Pericles3Bundle\Controller\BackOffice\PatchController();
            $controller->SetOutput($output);
            $controller->SetEm($em);
            $controller->DeleteRef($patch);
            $output->writeln("FIN");
            $output->writeln("<info>supression terminée</info>");

        }
    }
}
