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


class PatchTodoDeleteAllCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:patch-todo-delete-all');
        $this->setDescription("Supprime tous les pâtchs à faire");
        $this->setHelp("Supprime tous les pâtchs à faire");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $PatchsToDo = $em->getRepository("Pericles3Bundle:PatchToDo")->findToDo();
        
        $i=0;
        foreach ($PatchsToDo as $PatchToDo)
        {
            $output->writeln("Etablissement choisi : ".$PatchToDo->GetEtablissement());
            $output->writeln("Patch choisi : ".$PatchToDo->GetPatch());
            $em->remove($PatchToDo);
            $em->flush();
        }
        $output->writeln($i." patchs supprimés");
    }
}
