<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Pericles3Bundle\Entity\PatchToDoBatch;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Input\ArrayInput;
use DateTime;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchTodoBatchCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('patch:patch-todo-batch');
        $this->setDescription("Lance ou arrete un processus de batch");
        $this->setHelp("Lance ou arrete un processus de batch");
        $this->addOption('start',null,InputOption::VALUE_NONE,"Démmare un patch batch si aucun est lancé");
        $this->addOption('stop',null,InputOption::VALUE_NONE,"Stop un patch batch si un est lancé");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input=$input;
        $this->output=$output;
                
        $start = $input->getOption('start');
        $stop = $input->getOption('stop');
        
        if ($start)
        {
            $this->PatchTodoBatchStart();
        }
        elseif ($stop)
        {
            $this->PatchTodoBatchStop();
        }
        else
        {
            $this->GetPatchTodoBatchEnCours();
        }
    }

}
