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

use Pericles3Bundle\Entity\PatchToDo;
use DateTime;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchEtablissementCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('patch:patch-etablissement');
        $this->setDescription("Patch l'établissement.");
        $this->setHelp("Patch l'établissement... attention dangereux !");
        $this->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de l'établissement",0);
        $this->addOption('patch_id',null,InputOption::VALUE_OPTIONAL,"L'identifiant du patch ",0);
        $this->addOption('patch_todo_id',null,InputOption::VALUE_OPTIONAL,"L'identifiant du patch todo, si vide, en crée un ! ",0);
        $this->addOption('whitout_backups',null,InputOption::VALUE_NONE,"Ne patche pas les sauvegardes ! !attention, les patcher manuellement ensuite");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        
        $this->input=$input;
        $this->output=$output;

        
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $em->getConfiguration()->setSQLLogger(null);

        if ($this->GetPatchTodoEnCours())
        {
            $output->writeln("<error>Annulation du CRON</error>");
            exit();
        }
        
       

        
        if ($input->getOption('whitout_backups'))
        {
            $sauvegardes=false;
        }
        else
        {
            $sauvegardes=true;
        }
        
        
        $etablissementId = $input->getOption('etablissement_id');
        $patchTodoId = $input->getOption('patch_todo_id');
        $etablissement = $em->getRepository("Pericles3Bundle:Etablissement")->findOneById($etablissementId);
        
        $patchTodo = $em->getRepository("Pericles3Bundle:PatchTodo")->findOneById($patchTodoId);
        
        
        
        
        
        
        
        gc_collect_cycles();
        if (! $etablissement)
        {
            $output->writeln("<error>L'établissement ".$etablissement." n'exites pas</error>");
            return(0);
        }
        else
        {
            $output->writeln("Etablissement choisi : ");
            $output->writeln("--->".$etablissement);
            $output->writeln("Etablissement Reférentiel : ");
            $output->writeln("--->".$etablissement->GetReferentielPublic());
        }
        
        $patchId = $input->getOption('patch_id');
        if ($patchId )
        {
            $patch = $em->getRepository("Pericles3Bundle:Patch")->findOneById($patchId);
        }
        else
        {
            $output->writeln("<error>Veuillez selectionner un patche</error>");
            
            foreach ($etablissement->GetReferentielPublic()->GetPatchSources() as $patch)
            {
                $output->writeln("<info>".$patch->GetId()."</info> : ".$patch);
            }
            return(null);
            
        }
        
        if (! $patch)
        {
            $output->writeln("<error>Le patch ".$patchId." n'exites pas</error>");
            return(0);
        }
       
        
        
        
        if (! $patchTodo)
        {
            
            $patchTodo = $em->getRepository("Pericles3Bundle:PatchTodo")->findToDoEtablissementPatch($etablissement,$patch);
            if (! $patchTodo)
            {
                $output->writeln("pas de patch todo choisi !  /création : ");
                $patchTodo=new PatchToDo();
                $patchTodo->setPatch($patch);
                $patchTodo->setEtablissement($etablissement);
            }
        }
        $patchTodo->setDateDebutPatch(new DateTime());
        $em->persist($patchTodo);
        $em->flush();
         
            
        $output->writeln("Patch choisi : ");
        $output->writeln("--->".$patch);
        $etablissementController = new \Pericles3Bundle\Controller\BackOffice\EtablissementController();
        $etablissementController->SetOutput($output);
        $etablissementController->SetEm($em);

        if ($patch->Getsource()!=$etablissement->GetReferentielPublic())
        {
            $output->writeln("<error>Le patch et le référentiel de l'établissement ne corresponde pas</error>");
        }
        else
        { 
            
            $etablissementController->etablisssementPatchGo($etablissement,$patch,$sauvegardes);
            /*
            for ($etape=1;$etape<=4;$etape++)
            {
                $etablissementController->etablisssementPatchEtape($etablissement,$patch,$etape);
            }
             */
        }
        $patchTodo->setDateFinPatch(new DateTime());
        $patchTodo->setMemoryUsage(memory_get_usage(true));
        
        $em->persist($patchTodo);
        $em->flush();
        $em->clear();
        gc_collect_cycles();

        $memoryUsage = memory_get_usage(true) / 1024 / 1024;
        $output->writeln("<info>++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ <info>");
        $output->writeln("<info>++++++++++++   Mémoire utilisée : ".$memoryUsage." Mégas ++++++++++++ <info>");
        $output->writeln("<info>++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ <info>");

        
        
    }
}
