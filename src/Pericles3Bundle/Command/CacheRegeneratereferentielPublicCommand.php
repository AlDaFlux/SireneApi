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


class CacheRegeneratereferentielPublicCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cache:regenerate:publics');
        $this->setDescription('regenere le cache pour les referentiels publics');
        $this->setHelp("regenere le cache pour les referentiels publics");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
       
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
        $publics = $em->GetRepository('Pericles3Bundle:ReferentielPublic')->findAll();
        $output->writeln("  -------- regenere le cache !! ");

        foreach ($publics  as $referentielPublic)
        {
            $modif=false;

            $output->writeln("---".$referentielPublic);
            
            if ($referentielPublic->getNbCriteres()==$referentielPublic->getNbCriteresCache())
            {
                $output->writeln("<info>Critere : ".$referentielPublic->getNbCriteres()."==".$referentielPublic->getNbCriteresCache()."</info>");
            }
            else
            {
                $output->writeln("<error>questions : ".$referentielPublic->getNbCriteres()."<>".$referentielPublic->getNbCriteresCache()."</error>");
                $referentielPublic->setNbCriteresCache($referentielPublic->getNbCriteres());
                $modif=true;
            }
            
            if ($referentielPublic->getNbQuestions()==$referentielPublic->getNbQuestionsCache())
            {
                $output->writeln("<info>questions : ".$referentielPublic->getNbQuestions()."==".$referentielPublic->getNbQuestionsCache()."</info>");
            }
            else
            {
                $output->writeln("<error>Critere : ".$referentielPublic->getNbQuestions()."<>".$referentielPublic->getNbQuestionsCache()."</error>");
                $referentielPublic->setNbQuestionsCache($referentielPublic->getNbQuestions());
                $modif=true;
            }

            if ($modif)
            {
                $em->persist($referentielPublic);
                $em->flush();
            } 

        }
//            $em->clear();
        
        $output->writeln("<info>cache regénéré ! </info>");

    }
}
