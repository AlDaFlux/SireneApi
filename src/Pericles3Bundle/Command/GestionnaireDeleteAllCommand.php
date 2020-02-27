<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class GestionnaireDeleteAllCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('gestionnaire:delete:all');
        $this->setDescription('suprimme tous les gestionnaires sans établissements !!! !Atention ');
        $this->addOption('force-softdeleteable',null,InputOption::VALUE_NONE,"désactive softdeleteable");
        $this->setHelp("<info>gestionnaire:delete:all</info> <comment> --force-softdeleteable</comment> suprimme tous les gestionnaires sans établissements !!! !Atention ");
           
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input=$input;
        $this->output=$output;

        $force_softdeleteable = $input->getOption('force-softdeleteable');
        if ($force_softdeleteable)
        {
            $output->writeln("<info>SOFTDELETE DESACTIVE</info>");
          
        }
       
        
  
        
        foreach ($this->GetAllGestionnaires() as $gestionnaire)
        {
            
                if ($gestionnaire->GetNbEtablissements())
                {
                    $output->writeln("<error> ----GESTIONNAIRE : ".$gestionnaire." à ".$gestionnaire->GetNbEtablissements()." établissements</error>");
                }
                else
                {
                    $output->writeln("<info> ----SUPPRESION GESTIONNAIRE : ".$gestionnaire."</info>");
                    $command = $this->getApplication()->find('gestionnaire:delete');
                    $arguments = array('command' => 'gestionnaire:delete','--gestionnaire_id'  => $gestionnaire->GetId(), "--force-softdeleteable"=> $force_softdeleteable);
                    $input = new ArrayInput($arguments);
                    $command->run($input, $output);
                    $output->write(" ----: OK !!!");
                }
        }
    }
}
