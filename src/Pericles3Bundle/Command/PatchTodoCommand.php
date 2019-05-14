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
use DateTime;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchTodoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:patch-todo');
        $this->setDescription("Patch tous les établissement.");
        $this->setHelp("Patch tous les établissement... attention dangereux !");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $em->getConfiguration()->setSQLLogger(null);
        
        
        $PatchsToDo = $em->getRepository("Pericles3Bundle:PatchToDo")->findToDo();
        
        $i=0;
        foreach ($PatchsToDo as $PatchToDo)
        {
            $output->writeln("Etablissement choisi : ".$PatchToDo->GetEtablissement());
            $output->writeln("Patch choisi : ".$PatchToDo->GetPatch());
            $command = $this->getApplication()->find('patch:patch-etablissement');
            $arguments = array('command' => 'patch:patch-etablissement','--patch_todo_id'=>$PatchToDo->GetId(),'--etablissement_id'    => $PatchToDo->GetEtablissement()->GetId(),'--patch_id'  => $PatchToDo->GetPatch()->GetId());
            $PatchEtabInput = new ArrayInput($arguments);
            $command->run($PatchEtabInput, $output);
            $em->clear();
            gc_collect_cycles();
        }
        $output->writeln($i." établissements patchés: ");
    }
}


