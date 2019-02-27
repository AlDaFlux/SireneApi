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


class BiblioAncreaiTestDeadLinkCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('biblio:test-dead-link');
        $this->setDescription("test les liens morts de la bibliothèque de l'application");
        $this->setHelp("test les liens morts de la bibliothèque de l'application");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findAll();
        $output->writeln("  -------- Test les liens morts");
        foreach ($bibliothequeAncreais  as $bibliothequeAncreai)
        {
            $a = @get_headers($bibliothequeAncreai->GetHref());
            $code=substr($a[0],9,3);
            
            if ($code==200)
            {
                $output->writeln("<info>".$code." : ".$bibliothequeAncreai." : </info>"); 
            }
            else
            {
                $output->writeln("<error>".$code." : ".$bibliothequeAncreai." : </error>"); 
            }
            $bibliothequeAncreai->SetCodeRetour($code);
            $em->persist($bibliothequeAncreai);
            $em->flush();
            gc_collect_cycles();
        }
        $em->clear();
        $output->writeln("<info>tous les liens ont été testés</info>");
    }
}
