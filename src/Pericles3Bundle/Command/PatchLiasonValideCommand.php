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


class PatchLiasonValideCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:liason-valide');
        $this->setDescription("Valide toutes les liasons d 'un patch");
        $this->setHelp("Valide toutes les liasons d 'un patch. \n   Argument : --patch_id:id     numéro du patch");
        
        $this->addOption('patch_id',null,InputOption::VALUE_REQUIRED,"L'identifiant du patch ",0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $patchId = $input->getOption('patch_id');
        $patch = $em->getRepository("Pericles3Bundle:Patch")->findOneById($patchId);
        
        if (! $patch)
        {
            $output->writeln("<error>Le patch ".$patchId." n'exites pas<error>");
            
        }
        else
        {
            $output->writeln("Patch choisi : ");
            $output->writeln("--->".$patch);
            foreach ($patch->getPatchReferentiels() as $patchRef)
            {
                if ($patchRef->GetValide())
                {
                        $output->writeln("<info>".$patchRef."<info>");
                }
                else
                {
                    $output->writeln("<error>".$patchRef."<error>");
                    $patchRef->SetValide(true);
                    $em->persist($patchRef);
                    $em->flush();
                }
            }
             
        }
    }
}
