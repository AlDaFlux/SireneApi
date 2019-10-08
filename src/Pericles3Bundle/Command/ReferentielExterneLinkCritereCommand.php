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


class ReferentielExterneLinkCritereCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $forceWrite;
    
    
    
    protected function configure()
    {
        $this->setName('referentiel:externe-link-critere');
        $this->setDescription("Attention dangereux !!! Affecte un critere du référentiel externe avec un domaine Externe N1");
        $this->setHelp("Attention dangereux !!! Affecte un critere du référentiel externe avec un domaine Externe N1");
        $this->addOption('referentiel_externe_numero',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel externe",0);
        $this->addOption('referentiel_externe_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel externe",0);
        $this->addOption('referentiel_public_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel externe",0);
        $this->addOption('referentiel_critere_numero',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel externe",0);
        $this->addOption('force-write',null,InputOption::VALUE_NONE,"Force meme si le critere est déja affacté a un domaine");
    }
    
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output=$output;
              
      
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $this->em=$em;
          
        $em->getConfiguration()->setSQLLogger(null);

        $forceWrite = $input->getOption('force-write');
        if ($forceWrite)
        {
            $this->forceWrite=true;
            $this->output->writeln("<info>Attention !! écrit dans la base</info>");
        }
        
        
        
        $referentielCritereNumero= $input->getOption('referentiel_critere_numero');
        $referentielExterneId= $input->getOption('referentiel_externe_id');
        $referentielPublicId= $input->getOption('referentiel_public_id');
        $referentielExterneNumero= $input->getOption('referentiel_externe_numero');
        
        $referentielExterne = $this->em->getRepository("Pericles3Bundle:ReferentielExterne")->findOneById($referentielExterneId);
        $referentielPublic = $this->em->getRepository("Pericles3Bundle:ReferentielPublic")->findOneById($referentielPublicId);

        
        
        
        if (! $referentielPublic)   
        {
            $output->writeln("<error>Vous devez choisir un refentiel public : --referentiel_public_id=? </error>");
            $referentielsPublic = $em->getRepository("Pericles3Bundle:ReferentielPublic")->findAll();
            foreach ($referentielsPublic as $referentielPublic)
            {
                $output->writeln("<info>".$referentielPublic->GetId()." : ".$referentielPublic."</info>");
            }
            return(0);
        }
        

        $output->writeln("<info>référentiel Public : ".$referentielPublic->GetId()." : ".$referentielPublic."</info>");
        
        if (! $referentielExterne)   
        {
            $output->writeln("<error>Vous devez choisir un refentiel externe : --referentiel_externe_id=? </error>");
            $referentielsExterne = $em->getRepository("Pericles3Bundle:ReferentielExterne")->findAll();
            foreach ($referentielsExterne as $referentielExterne)
            {
                if ($referentielPublic->GetReferentielExterne()==$referentielExterne)
                {
                    $output->writeln("<info>".$referentielExterne->GetId()." : ".$referentielExterne."</info>");
                }
                else
                {
                    $output->writeln("".$referentielExterne->GetId()." : ".$referentielExterne."");
                }
            }
            return(0);
        }
        else
        {
            $output->writeln("<info>référentiel externe : ".$referentielExterne->GetId()." : ".$referentielExterne."</info>");

        }

        if ($referentielPublic->GetReferentielExterne()<>$referentielExterne)
        {
            $output->writeln("<error>Les référtentiel ne correspondent pas !! </error>");
            $output->writeln("<error>".$referentielExterne->GetId()." : ".$referentielExterne."</error>");
            $output->writeln("<error>".$referentielPublic." : ".$referentielExterne->GetId()." : ".$referentielExterne."</error>");
            return(0);
        }
        
        
        if (! $referentielCritereNumero )
        {
                $output->writeln("<error>Vous devez choisir un numero de critere  --referentiel_critere_numero=? : </error>");
                foreach ($referentielPublic->getReferentielCriteres() as $critere)
                {
                    if (! $critere->GetReferentielExterneNiv1()) { $output->writeln("<error>".$critere->GetNumero()." : ?????????????????</error>"); }
                }
                return(0);
        }
        
        $critereChoisi=null;
        
        foreach ($referentielPublic->getReferentielCriteres() as $critere)
        {
            if ($critere->GetNumero()==$referentielCritereNumero)
            {
                $critereChoisi=$critere;
            }
        }
        
        if (! $critereChoisi)
        {
                $output->writeln("<error>Le critere ".$referentielCritereNumero." choisi n'existe pas : --referentiel_critere_numero=? : </error>");
                foreach ($referentielPublic->getReferentielCriteres() as $critere)
                {
                    if (! $critere->GetReferentielExterneNiv1()) { $output->writeln("<error>".$critere->GetNumero()." : ?????????????????</error>"); }
                }
                return(0);
        }
        
        if ($critereChoisi->GetReferentielExterneNiv1() and  ! $forceWrite)
        {
                $output->writeln("<error>Le critere ".$referentielCritereNumero." choisi à déja un référentiel externne, --force-write pour passer outre </error>");
                return(0);
        }
        $output->writeln("<info>Critere : ".$critereChoisi->GetNumero()." : ".$critereChoisi."</info>");
        
        
        
        if (! $referentielExterneNumero)
        {
                 $output->writeln("<error>Vous devez choisir un domaine externe --referentiel_externe_numero=? : </error>");
                foreach ($referentielExterne->GetReferentielExterneNiv1() as $domExterne)
                {
                     $output->writeln("".$domExterne->numeroOrdre()." : ".$domExterne."");  
                }
                return(0);
        }
        
        $domExterneChoisi=null;
        foreach ($referentielExterne->GetReferentielExterneNiv1() as $domExterne)
        {
            if ($domExterne->numeroOrdre()==$referentielExterneNumero) {$domExterneChoisi=$domExterne;};  
        }
        
        if (! $domExterneChoisi)
        {
                $output->writeln("<error>Vous le domaine externe ".$referentielExterneNumero." existe pas !!  --referentiel_externe_numero=? : </error>");
                foreach ($referentielExterne->GetReferentielExterneNiv1() as $domExterne)
                {
                     $output->writeln("".$domExterne->numeroOrdre()." : ".$domExterne."");  
                }
                return(0);
        }
        else
        {
            $output->writeln("<info>Domaine externe  : ".$domExterneChoisi->GetNumero()." : ".$domExterneChoisi."</info>");
        }
        
        $critereChoisi->SetReferentielExterneNiv1($domExterneChoisi);
        $em->persist($critereChoisi);
        $em->flush();        
        
        
         
    }
}
