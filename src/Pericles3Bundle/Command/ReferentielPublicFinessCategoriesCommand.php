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


class ReferentielPublicFinessCategoriesCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('referentiel:public:migre');
        $this->setDescription('Affiche ou modifies les catégories finess associées à un réfrentiel public');
        $this->setHelp(<<<'HELP'
<info>%referentiel:public:migre%</info> supprime un référentiel 

  <info>referentiel:public:migre</info> <comment> --referentiel_public_id=?</comment> Liste le scatégories
  <info>referentiel:public:migre </info> <comment> --referentiel_public_id=? --referentiel_public_cible=?</comment>   Migre les catécories vers le ref cible
 
HELP
            )
        ;
            
        $this->addOption('referentiel_public_id',null,InputOption::VALUE_REQUIRED,"L'identifiant du référentiel public ",0);
        $this->addOption('referentiel_public_cible',null,InputOption::VALUE_OPTIONAL,"L'identifiant du reférentiel pour migrer ",0);
        $this->addOption('softdeleteable',null,InputOption::VALUE_NONE,"désactive softdeleteable");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
                
        $softdeleteable = $input->getOption('softdeleteable');

        $this->output=$output;
        
        $em = $doctrine->getEntityManager();
        $refPublicId = $input->getOption('referentiel_public_id');
        $refPublicIdCible = $input->getOption('referentiel_public_cible');
         
        $refPublic = $this->GetReferentielPublicById($refPublicId);
        if ($refPublicIdCible)
        {
            $refPublicCible = $this->GetReferentielPublicById($refPublicIdCible);
            if (! $refPublicCible)
            {
                $output->writeln("<error>Le référentiel Public  ".$refPublicIdCible." n'exites pas</error>");
                $output->writeln($this->GetHelp());
                return(0);
                
            }
            else
            {
                
            }
        }
        
        
        if (! $refPublic)
        {
            $output->writeln("<error>Le référentiel Public  ".$refPublicId." n'exites pas</error>");
            $output->writeln($this->GetHelp());
        }
        else
        {         
            $output->writeln("referentiel choisi : ");
            $output->writeln("<info>".$refPublic."</info>");

            if ($refPublicCible)
            {
                $output->writeln("Migration des catégories vers : ");
                $output->writeln("<info>".$refPublicCible."</info>");
                
            }

            foreach ($refPublic->getFinessCategories() as $categorie)
            {
                $output->writeln(" - ".$categorie."");
                if ($refPublicCible)
                {
                    $categorie->SetReferentielPublicDefault($refPublicCible);
                    $em->persist($categorie);
                    $em->flush();
                    }
            }
            
            foreach ($refPublic->getDemandesEtablissement() as $demande)
            {
                $output->writeln(" - ".$demande."");
                if ($refPublicCible)
                {
                    $demande->SetReferentielPublic($refPublicCible);
                    $em->persist($demande);
                    $em->flush();
                    }
            }
            
        }
    }
}
