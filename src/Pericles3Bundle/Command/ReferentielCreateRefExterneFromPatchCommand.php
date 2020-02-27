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


class ReferentielCreateRefExterneFromPatchCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this->setName('referentiel:create-externe-from-patch');
        $this->setDescription("Attention dangereux !!! utilsie un patch pour remplit en ref externe existant! !!! ");
        $this->setHelp("Attention dangereux !!! utilsie un patch pour remplit en ref externe existant! le ref doit avoir le refexterne affecté mais vide");
        $this->addOption('referentiel_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel",0);
        $this->addOption('patch_id',null,InputOption::VALUE_REQUIRED,"Le numero du patch",0);
    }
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $referentielId= $input->getOption('referentiel_id');
        $patchId= $input->getOption('patch_id');
        $referentielPublic = $em->getRepository("Pericles3Bundle:ReferentielPublic")->findOneById($referentielId);
        $patch = $em->getRepository("Pericles3Bundle:Patch")->findOneById($patchId);
        
             

        if (! $referentielPublic)   
        {
            $output->writeln("<error>Vous devez choisir un refentiel public : --referentiel_id=? <error>");
            $referentielsPublic = $em->getRepository("Pericles3Bundle:ReferentielPublic")->findAll();
            foreach ($referentielsPublic as $referentielPublic)
            {
                $output->writeln("<info>".$referentielPublic->GetId()." : ".$referentielPublic."</info>");
            }
            return(0);
        }
        elseif (! $patch )
        {
            $output->writeln("<error>Vous devez choisir un patch : --patch_id=? <error>");
            foreach ($referentielPublic->getPatchCibles() as $patch)
            {
                $output->writeln("<info>".$patch->GetId()." : ".$patch."</info>");
            }
            return(0);
        }
        
        
        if ($patch->GetSource()->getReferentielExterne())
        {
            $output->writeln("<info> ref externe : ".$patch->GetSource()->getReferentielExterne()." </info>");
        }
        else
        {
            $output->writeln("<info> ref externe : ".$patch->GetSource()." Pas de référentiel externe !!  </info>");
        }
        
        if (! $referentielPublic->getReferentielExterne())
        {
            $referentielPublic->SetReferentielExterne($patch->GetSource()->getReferentielExterne());
        }
        elseif ($referentielPublic->getReferentielExterne()<>$patch->GetSource()->getReferentielExterne())
        {
            $output->writeln("<error> ref externe : ".$patch->GetSource()." les reférentiels ne correspondent pas ! !!  </error>");
            $output->writeln("<error> ->".$patch->GetSource()->getReferentielExterne()."</error>");
            $output->writeln("<error> ->".$referentielPublic->getReferentielExterne()."</error>");
            return(0);
        }
        
        
        $nb_sans_lien=0;
        foreach ($referentielPublic->getReferentielCriteres() as $critere)
        {
                $sourceRef=$patch->getReferentielSourceFromCible($critere);
                $output->writeln("<info>".$critere->GetNumero()." : ".$critere."</info>");
                if ($sourceRef) 
                {
                    $output->writeln("-->".$sourceRef->GetNumero()." : ".$sourceRef.""); 
                    if ($sourceRef->getReferentielExterneNiv1())
                    {
                        $output->writeln("---->".$sourceRef->getReferentielExterneNiv1().""); 
                        $critere->setReferentielExterneNiv1($sourceRef->getReferentielExterneNiv1());
                        $em->persist($critere);
                        $em->flush();
                    }
                    
                }
                else
                { 
                    $output->writeln("<error> : Pas trouvé </error>");
                    $nb_sans_lien++;
                }
        }
                
        if ($nb_sans_lien)
        {
            $output->writeln("<info>".$nb_sans_lien." : sans lien</info>");
        }
        
        
        
        
        
        
    }
}
