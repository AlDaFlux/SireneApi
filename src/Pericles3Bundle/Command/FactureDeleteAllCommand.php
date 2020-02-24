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


class FactureDeleteAllCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('factures:delete-all');
        $this->setDescription('Supprimer toutes les factures !!');
        $this->setHelp("Supprimer toutes les factures !!");

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $factures_activees=$this->getContainer()->getParameter('activate.facturation');
        if ($factures_activees)
        {
            $output->writeln("<error>Vous ne pouvez supprimer toutes les factures, le module activate.facturation est actif<error>");
        }
        else
        {
            $output->writeln("<info>Supression des prestas : -".$factures_activees."- <info>");
            $prestas=$em->getRepository('Pericles3Bundle:FacturePresta')->FindAll();
            foreach ($prestas as $presta)
            {
                $em->remove($presta);
            }
            $em->flush();
            $output->writeln("<info>Supression des rappels : <info>");
            $factures=$em->getRepository('Pericles3Bundle:FactureRappel')->FindAll();
            foreach ($factures as $facture)
            {
                $em->remove($facture);
            }
            $em->flush();
            $output->writeln("<info>Supression des factures : <info>");
            $factures=$em->getRepository('Pericles3Bundle:Facture')->FindAll();
            foreach ($factures as $facture)
            {
                $em->remove($facture);
            }
            $em->flush();
        }
                            
        
    }
}
