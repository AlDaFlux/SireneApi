<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Input\ArrayInput
;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class ReferentielOsoleteSupressionDomainesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:domaines-del-obs-ref');
        $this->setDescription("Supprimme les domaines obsoletes");
        $this->setHelp("Supprimme les domaines obsoletes");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();


    $domaines_desuet =$em->getRepository('Pericles3Bundle:Domaine')->FindReferentielDesuet();
    foreach ($domaines_desuet as $Domaine )
    {
        $output->writeln("<error>Supression : ".$Domaine->GetEtablissement()." : ".$Domaine->GetNumero()." ".$Domaine." </error>");
        if ($Domaine->GetEtablissement()->getNbSauvegardesReferentielDesuet()==0)
        {
               foreach ($Domaine->getDimensions() as $Dimension  )
               {
                   foreach ($Dimension->getCriteres() as $Critere )
                   {
                       foreach ($Critere->GetQuestions() as $Question)
                       {
                            $em->remove($Question);
                       }
                       $em->flush();
                       $em->remove($Critere);
                   }
                   $em->flush();
                   $em->remove($Dimension);
               }
               $em->flush();
               $em->remove($Domaine);
           }
        }
        $em->flush();
        $output->writeln("<info>Supression de tous les domaines desuets OK</info>");


        }
        
         
}
