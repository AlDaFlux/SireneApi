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


class ReferentielClearCharsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('referentiel:clear-chars');
        $this->setDescription('Nettoie les caractères des référentiel');
        $this->setHelp("Remplace les ' par '  et ? par &nbsp;");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
         
            $output->writeln("referentiel choisi : ");
            
            $controller = new \Pericles3Bundle\Controller\BackOffice\ReferentielPublicController();
            $controller->SetOutput($output);
            $controller->SetEm($em);
            $controller->nettoieReferentiel();
            $output->writeln("FIN");
            $output->writeln("<info>supression terminée</info>");

    }
}
