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


class EtablissementListCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('etablissement:list');
        $this->setDescription('Liste les établissements');
        $this->setHelp("Liste les établissements");
        $this->addArgument('name', InputArgument::OPTIONAL, 'Recherrche par nom');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input=$input;
        $this->output=$output;
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        if ($input->getArgument('name'))
        {
            $output->writeln("<info>Recherche de ".$input->getArgument('name')."</info>");
            $etablissements=$em->getRepository('Pericles3Bundle:Etablissement')->FindByOccurence($input->getArgument('name'));
            if ($etablissements)
            {
                $this->listEtablissement($etablissements);
            }
            else
            {
                $this->output->writeln("<error>Aucun établissement trouvé !  </error>");
            }
            
        }
        else
        {
            $this->listEtablissement($this->GetAllEtablissements());
        }

    }
}
