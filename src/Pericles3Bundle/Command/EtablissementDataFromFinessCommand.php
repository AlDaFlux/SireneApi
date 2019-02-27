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


class EtablissementDataFromFinessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('etablissement:fromfiness');
        $this->setDescription('Récupere les informations depuis le finess');
        $this->setHelp("Récupere les informations depuis le finess");
        $this->addOption('finess',null,InputOption::VALUE_REQUIRED,"Le finess de l'établissement",0);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $code_finess = $input->getOption('finess');
        $output->writeln($code_finess);
        $etablissement = $em->GetRepository('Pericles3Bundle:Etablissement')->findOneByFiness($code_finess);
        $Finess = $em->GetRepository('Pericles3Bundle:Finess')->findOneByCodeFiness($code_finess);
        
                      
        if (! $code_finess)
        {
            $output->writeln("<error>L'établissement n'existe pas\n</error>");
            
        }
        elseif (! $etablissement)
        {
            $output->writeln("<error>L'établissement n'existe pas\n</error>");
        }
        elseif (! $Finess)
        {
            $output->writeln("<error>le FINESS n'existe pas\n</error>");
        }
        else 
        {
            $etablissement->setAdresse($Finess->GetAdresse());
            $etablissement->setCodePostal($Finess->getCodePostal());
            $etablissement->setVille($Finess->getVille());
            $etablissement->setTel($Finess->getTel());
            $etablissement->setFax($Finess->getFax());

            $em->persist($etablissement);
            $em->flush();
            $output->writeln("<info>:".$Finess->GetAdresse()." - ".$Finess->getCodePostal()."<info>");
            $output->writeln("Etablissement :".$etablissement);
            $output->writeln("FINESS :".$Finess);
        }
                            
        
    }
}
