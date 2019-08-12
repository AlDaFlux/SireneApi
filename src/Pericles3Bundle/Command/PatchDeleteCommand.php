<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchDeleteCommand extends ArseneCommand
{
    
    protected function configure()
    {
        $this->setName('patch:delete-full');
        $this->setDescription("Supprimme un patch !!! TRES DANGEREUX ");
        $this->setHelp("Supprimme un patch");
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
            $output->writeln("<info>patch ".$patch." <info>");
            $output->writeln("<info>Etablissements : <info>");
            foreach ($patch->GetEtablissements() as $etab)
            {
                $etab->setPatch(null);
                $output->writeln("-->".$etab." ");
                $em->persist($etab);
                $em->flush();
            }
            
            $output->writeln("<info>PatchTodos<info>");
            foreach ($patch->GetPatchToDo() as $patchToDo)
            {
                $output->writeln("-->".$patchToDo." ");
                $em->Remove($patchToDo);
                $em->flush();
            } 
            
            $output->writeln("----- Supression des liasons -----");
            $command = $this->getApplication()->find('patch:delete-liason');
            $arguments = array('command' => 'patch:delete-liason','--patch_id'  => $patch->GetId());
            $PatchEtabInput = new ArrayInput($arguments);
            $command->run($PatchEtabInput, $output);

            
            $output->writeln("----- Supression du patch  -----");
            $em->Remove($patch);
            $em->flush();
            $output->writeln("-----> terminée ");
        }
    }
}
