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


class ReferentielPublicListCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('referentiel:public:list');
        $this->setDescription('Liste les référentiel ');
        $this->setHelp(<<<'HELP'
<info>%referentiel:public:list%</info> Liste les référentiel 
HELP
            )
            
        ;
            
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $this->output=$output;
        
                
             $this->listAllReferentielPublic();
   
    }
}
