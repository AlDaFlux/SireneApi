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


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class CheckReferentielCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:etablissement-check-refentiel');
        $this->setDescription("Vérifies le référentiels");
        $this->setHelp("Vérifies le référentiels");
//        $this->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de l'établissement",0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $etablissements = $em->getRepository("Pericles3Bundle:Etablissement")->findAll();
        
//            $output->writeln("Etablissement choisi : ");
        foreach ($etablissements as $etablissement)
        {
                $etablissement_NbDomaines = $etablissement->GetNbDomaines();
                $etablissementNbDomaines_ref = $etablissement->getReferentielPublic()->getNbDomaines();               
                
                $etablissementNbCriteres = $etablissement->getNbCriteresCount();
                $etablissementNbCriteres_ref = $etablissement->getReferentielPublic()->getNbCriteres();              
                
                $etablissementNbQuestions = $etablissement->getNbQuestionsCount();
                $etablissementNbQuestions_ref = $etablissement->getReferentielPublic()->getNbQuestions();               
                
                if ($etablissement_NbDomaines<> $etablissementNbDomaines_ref or $etablissementNbCriteres<> $etablissementNbCriteres_ref or $etablissementNbQuestions<> $etablissementNbQuestions_ref )
                {
                    $output->writeln("<error>L'établissement ".$etablissement." est en erreur</error>");

                    if ($etablissement_NbDomaines<> $etablissementNbDomaines_ref)
                    {
                        $output->writeln("<error> | - Domaines : ".$etablissement_NbDomaines."<>".$etablissementNbDomaines_ref."</error>");
                    }
                    if ($etablissementNbCriteres<> $etablissementNbCriteres_ref ) 
                    {
                        $output->writeln("<error> | - Critères : ".$etablissementNbCriteres."<>".$etablissementNbCriteres_ref."</error>");
                        
                    }
                    if ($etablissementNbQuestions<> $etablissementNbQuestions_ref )
                    {
                        $output->writeln("<error> | - Questions : ".$etablissementNbQuestions."<>".$etablissementNbQuestions_ref."</error>");
                    }

                    $output->writeln("<error>L'établissement ".$etablissement." est en erreur</error>");
                }
                else
                {
                    $output->writeln("<info>L'établissement ".$etablissement." est OK</info>");
                }

                
                
        }
    }
}
