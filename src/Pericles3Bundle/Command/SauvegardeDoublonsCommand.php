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

use Pericles3Bundle\Entity\Etablissement;




use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class SauvegardeDoublonsCommand extends ArseneCommand
{
    
    
    protected function configure()
    {
        $this->setName('sauvegarde:doublons');
        $this->setDescription("Liste les sauvegardes en doublons, peut supprimmer les sauvegardes en doublons");
        $this->setHelp(<<<'HELP'
The <info>%command.name%</info> command lists all the users registered in the application:

  <info>php %command.full_name%</info>

By default the command only displays the 50 most recent users. Set the number of
results to display with the <comment>--max-results</comment> option:
 <info>php %command.full_name%</info> 
 <info>php %command.full_name%</info> <comment>--delete_doublons</comment>
 <info>php %command.full_name%</info> <comment>--etablissement_id=???</comment>
 <info>php %command.full_name%</info> <comment>--etablissement_id=??? --delete_doublons</comment>
 
HELP
            );
            $this->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de la sauvegarde",0);
            $this->addOption('delete_doublons',null,InputOption::VALUE_NONE,"L'identifiant de la sauvegarde");
    }

    protected $deleteDoublon;
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $this->input=$input;
        $this->output=$output;
        
        if ($input->getOption('delete_doublons'))
        {
            $this->deleteDoublon=true;
        }

        //$this->output->isVeryVerbose()
        
        
        if ($input->getOption('etablissement_id'))
        {
            $etablissement=$this->GetEtablissementById($input->getOption('etablissement_id'));
            if ($etablissement)
            {
                $output->writeln(" Etablissement choisi : ".$etablissement);
                $this->analyseEtablissement($etablissement);
            }
        }
        else
        {
            $etablissements=$this->GetAllEtablissements();
            foreach ($etablissements as $etablissement)
            {
                $this->analyseEtablissement($etablissement);
            }
            
        }
    }
    
    
    
    
    function analyseEtablissement(Etablissement $etablissement)
    {
        $sauvegardes = $this->GetEm()->getRepository('Pericles3Bundle:Sauvegarde')->findByEtablissement($etablissement);
        $sauvegardesDoublonsDay = $this->GetEm()->getRepository('Pericles3Bundle:Sauvegarde')->findDateDoublonsByEtablissement($etablissement);
        if ($this->output->isVerbose())
        {
                $this->output->writeln(count($sauvegardes)." sauvegardes");
        }
        if ($sauvegardesDoublonsDay)
        {
            $this->output->writeln("<error> Doublons !!! pour ".$etablissement." (".$etablissement->GetId().")</error>");
            foreach ($sauvegardesDoublonsDay as $sauvegardeDoublonsDay)
            {
                $this->output->writeln(" -- ".$sauvegardeDoublonsDay["dateCreateGrp"]." -> ".$sauvegardeDoublonsDay["nb"]);
                $sauvegardesDoublons= $this->GetEm()->getRepository('Pericles3Bundle:Sauvegarde')->findByEtablissementDay($etablissement,$sauvegardeDoublonsDay["dateCreateGrp"]);
                $nbSauvegardesDoublons=count($sauvegardesDoublons);
                $i=0;
                foreach ($sauvegardesDoublons as $sauvegardeDoublons)
                {
                    $i++;
                    if ($this->deleteDoublon)
                    {
                        if ($i<$nbSauvegardesDoublons)
                        {
                            $this->output->writeln("<error> On supprimme ----> ".$sauvegardeDoublons." -> ".$sauvegardeDoublons->getEtablissement()."</error>");
                            $this->deleteSauvegarde($sauvegardeDoublons);
                        }
                        else
                        {
                            $this->output->writeln("<info> On garde ----> ".$sauvegardeDoublons." -> ".$sauvegardeDoublons->getEtablissement()."</info>");
                            
                        }
                    }
                    else
                    {
                        $this->output->writeln(" ----> ".$sauvegardeDoublons." -> ".$sauvegardeDoublons->getEtablissement());
                    }
            }
            }
        }
        else
        {
            if ($this->output->isVerbose())
            {
                $this->output->writeln("<info>".$etablissement." (".$etablissement->GetId().") n'as pas de doublons</info>");
            }
        }
                
                
    }
    
}
