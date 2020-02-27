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


class ReferentielChangeLibFromPatchCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this->setName('referentiel:change-libs-from-patch');
        $this->setDescription("Modifie les libelles d'un ref par rapport à un script ");
        $this->setHelp("Modifie les libelles d'un ref par rapport à un script");
        $this->addOption('patch_id',null,InputOption::VALUE_REQUIRED,"Le numero du patch",0);
    }
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $patchId= $input->getOption('patch_id');
        $patch = $em->getRepository("Pericles3Bundle:Patch")->findOneById($patchId);
        $patchsAll = $em->getRepository("Pericles3Bundle:Patch")->findAll();
        
             

     
        if (! $patch )
        {
            $output->writeln("<error>Vous devez choisir un patch : --patch_id=? <error>");
            foreach ($patchsAll  as $patch)
            {
                $output->writeln("<info>".$patch->GetId()." : ".$patch."</info>");
            }
            return(0);
        }
        
     
        if (! $patch->isVerif())
        {
            $output->writeln("<error>Le patch ".$patch." n'est pas vérifier a 100%<error>");
            return(0);
        }
         
         
        $PatchReferentielsLink = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findLinked($patch);
        
        $diff=false;
        foreach ($PatchReferentielsLink as $patch)
        {
            if (! ($patch->GetSource()->GetNom()==$patch->GetCible()->GetNom()))
            {
                $diff=true;
                $output->writeln("<error>".$patch->GetSource()->GetNumero()." ; ".$patch->GetSource()->GetNom()." ; ".$patch->GetCible()->GetNom()."</error>");
            }
        }
        if ($diff)
        {
            $output->writeln("<error>Le patch contient des éléments déplacés non gérés par le générateur de script<error>");
            return(0);
        }

        

                
          
        /*
        foreach ($patch->getReferentielSourceFromCible() as $t)
        {
            $output->writeln("<info> ---".$t." </info>");
        }
         * 
         */
            

        
        
        
        
        
    }
}
