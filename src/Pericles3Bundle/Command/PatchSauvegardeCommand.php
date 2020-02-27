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


class PatchSauvegardeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:sauvegarde-do');
        $this->setDescription("Patch une sauvegarde obsolete... attention dangereux !");
        $this->setHelp("Patch une sauvegarde obsolete... attention dangereux !");
        $this->addOption('sauvegarde_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de la sauvegarde",0);
        $this->addOption('patch_id',null,InputOption::VALUE_OPTIONAL,"L'identifiant du patch ",0);
        $this->addOption('patch_todo_id',null,InputOption::VALUE_OPTIONAL,"L'identifiant du patch todo ",0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $em->getConfiguration()->setSQLLogger(null);
        
        $em->clear();
        
        $sauvegardeId = $input->getOption('sauvegarde_id');
        $patchId = $input->getOption('patch_id');
        
        $patchTodoId = $input->getOption('patch_todo_id');
        
        if ($patchTodoId)
        {
            $patchTodo = $em->getRepository("Pericles3Bundle:PatchTodo")->findOneById($patchTodoId);
        }

        
             
        if (! $sauvegardeId or ! $patchId)
        {
            $output->writeln("<error>patch:sauvegarde-do --sauvegarde_id=? --patch_id=? </error>");
            return(null);
        }
        

        $sauvegarde = $em->getRepository("Pericles3Bundle:Sauvegarde")->findOneById($sauvegardeId);
        $patch = $em->getRepository("Pericles3Bundle:Patch")->findOneById($patchId);
        if (! $sauvegarde)
        {
            $output->writeln("<error>La sauvegarde ".$sauvegardeId." n'existe pas </error>");
            return(null);
        }
        else 
        {
            $etablissement=$sauvegarde->getEtablissement();
            $output->writeln("<info>Sauvegarde choisi: ".$sauvegarde."</info>");
            $output->writeln("<info>referentiel Sauvegarde: ".$sauvegarde->GetReferentiel()."</info>");
            $output->writeln("<info>Etablissement choisi : ".$etablissement."</info>");
            $output->writeln("<info>Etablissement referentiel : ".$etablissement->getReferentielPublic()."</info>");
        }
        if (! $patch)
        {
            $output->writeln("<error>Le patch  ".$patchId." n'existe pas </error>");
            return(null);
        }
        else 
        {
            if (! ($patch->GetSource()->GetId()==$sauvegarde->GetReferentiel()->GetId() && $patch->GetCible()->GetId()==$etablissement->getReferentielPublic()->GetId()))
            {
                $output->writeln("<error>Incompatibilité de patch : ".$patch."</error>");
                return(null);
            }
            else
            {
                $output->writeln("<info>Patch  choisi : ".$patch."</info>");
                $etablissementController = new \Pericles3Bundle\Controller\BackOffice\SauvegardeController();
                $etablissementController->SetOutput($output);
                $etablissementController->SetEm($em);
                $etablissementController->patchSauvegardeApply($sauvegarde, $patch);
                if ($patchTodo)
                {
                    $patchTodo->setDateFinPatch(new DateTime());
                    $patchTodo->addMemoryUsage(memory_get_usage(true));
                    $em->persist($patchTodo);
                    $em->flush();
                }
            }
        }
 
        $em->clear();
        gc_collect_cycles();
        $memoryUsage = memory_get_usage(true) / 1024 / 1024;
        $output->writeln("<info>++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ <info>");
        $output->writeln("<info>++++++++++++   Mémoire utilisée : ".$memoryUsage." Mégas ++++++++++++ <info>");
        $output->writeln("<info>++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ <info>");

         
        $output->writeln(" La sauvegarde a été patché");
    }
}
