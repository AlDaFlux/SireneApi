<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Pericles3Bundle\Entity\BibliothequeAncreai;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class BiblioAncreaiDeleteUnusedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('biblio:delete-unused');
        $this->setDescription("Supprime les bibliotheuqe non utilisée!!!");
        $this->setHelp("Supprime les bibliotheuqe non utilisée!!!");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findAll();
        $output->writeln("  -------- ");
        $nb_delete=0;
        foreach ($bibliothequeAncreais  as $bibliothequeAncreai)
        {
            if ($output->isVerbose()) 
            {
                $output->writeln("".$bibliothequeAncreai."");
            }
            if ((! $bibliothequeAncreai->NbCriteres()) && (! $bibliothequeAncreai->NbReferentiels()))
            {
                $output->writeln("<info>Supression : ".$bibliothequeAncreai."</info>");
                $em->remove($bibliothequeAncreai);
                $em->flush();
                $nb_delete++;  
            }
        }
//        $em->clear();
        $output->writeln("<info>".$nb_delete." supprmés</info>");
    }
}
