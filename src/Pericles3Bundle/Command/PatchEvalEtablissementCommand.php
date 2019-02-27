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

use Dompdf\Dompdf;




use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchEvalEtablissementCommand extends ContainerAwareCommand
{
      private $templating;
      
      
    protected function configure()
    {
        $this->setName('patch:patch-eval-etablissement');
        $this->setDescription("Génére le PDF de l'évaluation du Patch ...");
        $this->setHelp("Génére le PDF de l'évaluation du Patch ...");
        $this->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de l'établissement",0);
        $this->addOption('patch_id',null,InputOption::VALUE_OPTIONAL,"L'identifiant du patch ",0);
    }

    
    public  function render($template,$options)
    {
        return($this->templating->render($template,$options));
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $this->templating = $this->getContainer()->get('templating');
        


        
        $etablissementId = $input->getOption('etablissement_id');
        $etablissement = $em->getRepository("Pericles3Bundle:Etablissement")->findOneById($etablissementId);
        
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
       
        $output->writeln("Patch choisi : ");
        $output->writeln("--->".$patch);
        $etablissementController = new \Pericles3Bundle\Controller\BackOffice\EtablissementController();
        
        
        
         //   $etablissementController->SetOutput($output);
         //   $etablissementController->SetEm($em);

        if ($patch->Getsource()!=$etablissement->GetReferentielPublic())
        {
            $output->writeln("<error>Le patch et le référentiel de l'établissement ne corresponde pas</error>");
        }
        else
        { 
            $ReferentielPublicSource=$etablissement->getReferentielPublic();
            $ReferentielPublicCible=$patch->GetCible();
            $filename=$etablissementController->EvalPatchGetFilename($etablissement,$patch);
//            $filename=$etablissementController->evalPatchEtablissementGenereFichier($etablissement,$patch);
            $view = $this->render('BackOffice/patch/eval_patch_etablissement_test.html.twig',
                array("etablissement" => $etablissement,
                    "ReferentielPublicSource" => $ReferentielPublicSource,
                    "ReferentielPublicCible" => $ReferentielPublicCible,
                    "patch" => $patch,
                ));
            
            $dompdf = new DOMPDF();
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->load_html($view);
            $dompdf->render();

            file_put_contents($this->getContainer()->getParameter('patch_eval_directory')."/".$filename, $dompdf->output());

            $output->writeln("<success>Le fichier ".$filename." est crée");
        }
    }
}
