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


class CacheRegenerateEtablissementCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cache:regenerate:etablissement');
        $this->setDescription('regenere le cache pour les établissements');
        $this->setHelp("regenere le cache pour les établissements");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
       
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
        $etablissements = $em->GetRepository('Pericles3Bundle:Etablissement')->findAll();
        $output->writeln("  -------- regenere le cache !! ");

        foreach ($etablissements  as $etablissement)
        {
//             $output->writeln(sprintf('Memory usage (currently) %dKB/ (max) %dKB', round(memory_get_usage(true) / 1024), memory_get_peak_usage(true) / 1024));

            $modif=false;
            $output->writeln("<info>".$etablissement." : </info>");
            if ($etablissement->getNbQuestionsRepondues()<>$etablissement->getNbQuestionsReponduesCache())
            {
                $modif=true;
                $output->writeln("<error> Questions : ".$etablissement." : ".$etablissement->getNbQuestionsReponduesCache()." -> ".$etablissement->getNbQuestionsRepondues()."</error>");
                $etablissement->SetNbQuestionsReponduesCache($etablissement->getNbQuestionsRepondues());
            }
            
            if ($etablissement->getNbCriteresWithNote()<>$etablissement->getNbCriteresNotesCache())
            {
                $modif=true;
                $output->writeln("<error> Critères : ".$etablissement." : ".$etablissement->getNbCriteresNotesCache()." -> ".$etablissement->getNbCriteresWithNote()."</error>");
                $etablissement->SetNbCriteresNotesCache($etablissement->getNbCriteresWithNote());
            }

            if ($modif)
            {
                $em->persist($etablissement);
                $em->flush();
            }
            else
            {
                $output->writeln("<info>".$etablissement." : OK ! </info>");
            }
             gc_collect_cycles();
        }
            $em->clear();
        
        $output->writeln("<info>cache regénéré ! </info>");

    }
}
