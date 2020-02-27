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

use Symfony\Component\Console\Input\ArrayInput;


class PatchLiasonValideZeroCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:liason-valide-zero');
        $this->setDescription("Valide toutes les liasons des patchs n'ayant plus de liasons possible");
        $this->setHelp("Valide toutes les liasons des patchs n'ayant plus de liasons possible");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
 
         $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $patchs = $em->getRepository("Pericles3Bundle:Patch")->findAll();
        

            foreach ($patchs  as $patch)
            {
                if (($patch->GetStats()['cible_a_verifie'] && ! $patch->GetStats()['source_a_verifie']) or (! $patch->GetStats()['cible_a_verifie'] &&  $patch->GetStats()['source_a_verifie']))
                {
                    $output->writeln("<error>".$patch." : ".$patch->GetId()."<error>");
                    $output->writeln("<info>".$patch->GetStats()['cible_a_verifie']."<info>");
                    $output->writeln("<info>".$patch->GetStats()['source_a_verifie']."<info>");
                    
                    $command = $this->getApplication()->find('patch:liason-valide');
                    
                    $arguments = array('command' => 'patch:liason-valide','--patch_id' => $patch->GetId());
                    $args = new ArrayInput($arguments);
                    $command->run($args, $output);
                }
                else
                {
                    $output->writeln("<info>".$patch."<info>");
                }
            }
    }
}


