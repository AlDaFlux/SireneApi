<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Input\ArrayInput
;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class ReferentielExterneOsoleteSupressionEtablissementCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:etablissement-del-obs-ref-externe');
        $this->setDescription("Supprimme les référentiels externe désuets pour un établissement");
        $this->setHelp("Supprimme les référentiels externe désuets pour un établissement");
        $this->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de l'établissement",0);
        $this->addOption('all-etab',null,InputOption::VALUE_NONE,"!! Tous les établissements");
    }

    protected function patchEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement,$output,$em)
    {
        $nbDomaineOboslete=0;
        foreach ($etablissement->getDomainesExterneObsolete() as $domaineOboslete)
        {
            $nbDomaineOboslete++;
            $output->writeln("<error>Supression de  : ".$domaineOboslete."</error>"); 
            foreach ($domaineOboslete->GetCriteres() as $critere)
            {
                $domaineOboslete->removeCritere($critere);
                $critere->setDomaineExterne(null);
                $output->writeln("<error>Supression de la liason avec le critere : ".$critere."</error>"); 
                $em->persist($domaineOboslete);
                $em->persist($critere);
                $em->flush();
            }
            $em->flush();
            $em->remove($domaineOboslete);
        }
        $em->flush();
        
        if ($nbDomaineOboslete)
        {
            $output->writeln("<info>".$nbDomaineOboslete." domaines effacés</info>");
        }
        else
        {
            $output->writeln("<error>Aucun domaines effacés</error>");
        }
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        
        if ($input->getOption('all-etab'))
        {
            $etablissements = $em->getRepository("Pericles3Bundle:Etablissement")->findAll();
            foreach ($etablissements as $etablissement)
            {
                if ($etablissement->getReferentielExterne( ))
                {
                        if ($etablissement->getNbDomainesExterne()  != $etablissement->getReferentielExterne()->getNbDomaines())
                        {
                            $output->writeln("<info>L'établissement ".$etablissement." </info>");
                            $this->patchEtablissement($etablissement,$output,$em);

                        }
                }
            }
            return(0);
        }
        
        $etablissementId = $input->getOption('etablissement_id');
        $etablissement = $em->getRepository("Pericles3Bundle:Etablissement")->findOneById($etablissementId);

        if (! $etablissement)
        {
            $output->writeln("<error>L'établissement ".$etablissement." n'exites pas<error>");
            return(0);
        }
        else
        {
            $output->writeln("Etablissement choisi : ");
            $output->writeln("--->".$etablissement);
            $this->patchEtablissement($etablissement,$output,$em);
        }
        
        
        

    }
}
