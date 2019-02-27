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


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class ReferentielExterneOsoleteVerifCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $forceWrite;
    
    
    
    protected function configure()
    {
        $this->setName('referentiel:externe-verif');
        $this->setDescription("Attention dangereux !!! Vérifie les  référentiels externe's des établissements");
        $this->setHelp("Attention dangereux !!! utilsie un patch pour remplit en ref externe existant! le ref doit avoir le refexterne affecté mais vide");
        $this->addOption('referentiel_externe_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel externe",0);
        $this->addOption('referentiel_public_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel externe",0);
        $this->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de l'établissement",0);
        $this->addOption('force-write',null,InputOption::VALUE_NONE,"Corrige les erreurs dans la base");
        $this->addOption('all-etab',null,InputOption::VALUE_NONE,"!! Tous les établissements");

    }
    
    
    protected function verifEtabDomaines(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        
          foreach ($etablissement->getDomainesExterne() as $domaine)
        {
            if ($etablissement->getReferentielExterneNiv1()->contains($domaine->getReferentielExterneN1()))
            {
                if ($this->output->isVeryVerbose()) 
                {
                    $this->output->writeln("<info>Domaines Externe : ".$domaine."(".$domaine->getReferentielExterneN1()->GetId().")</info>");
                }
            }
            else 
            {
                    $this->output->writeln("<error>A faire get DomainesExterne Obsolete : ".$domaine."</error>");
                    foreach ($domaine->GetCriteres() as $critsObsolete)
                    {
                        $this->output->writeln("<error>Delinkage du critere obsoleteObsolete : ".$critsObsolete."</error>");
                        if ($this->forceWrite)
                        {
                            $domaine->removeCritere($critsObsolete);
                            $critsObsolete->setDomaineExterne(null);
                            $this->em->persist($critsObsolete);
                            $this->em->persist($domaine);
                            $this->em->flush();
                         }
                    }

                    if ($this->forceWrite)
                    {
                        $this->em->remove($domaine);
                        $this->em->flush();
                    }
            }
        }
         
    }
    
    
    protected function getTabDomaineExterne(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $domainesExterneTab=null;
        foreach ($etablissement->getDomainesExterne() as $domaineExterne)
        {
            $domainesExterneTab[$domaineExterne->getReferentielExterneN1()->getId()]=$domaineExterne;
            if ($this->output->isVerbose()) 
            {
                $this->output->writeln("  --  DomaineExterne : ".$domaineExterne->getReferentielExterneN1()->getId()." : ".$domaineExterne);
            }
            //return(0);
        }
        return($domainesExterneTab);
    }
    
    
    protected function createDomaideExterne(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $nb_a_rajouter=0;
        foreach ($etablissement->getReferentielExterneNiv1() as $n1)
        {
            if (! $this->getN1Affectes($etablissement)->contains($n1))
            {
                $nb_a_rajouter++;
                if ($this->output->isVerbose()) 
                {
                    $this->output->writeln("<error>A rajouter : ".$n1.'('.$n1->GetId().")</error>");
                }
                
                if ($this->forceWrite)
                {
                    $domaineExterne = new \Pericles3Bundle\Entity\DomaineExterne();
                    $domaineExterne->setEtablissement($etablissement);
                    $domaineExterne->setReferentielExterneN1($n1);
                    $this->em->persist($n1);
                    $this->em->persist($domaineExterne);
                    $this->em->flush();
                }
            }
            else 
            {
                if ($this->output->isVeryVerbose()) 
                {
                $this->output->writeln("<info>Existe Déja: ".$n1.'('.$n1->GetId().")</info>");
                }
            }
        }
        return($nb_a_rajouter);
    }
    
    protected function getN1Affectes(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {   
        $n1Affectes=  new \Doctrine\Common\Collections\ArrayCollection();
        if ($this->output->isVeryVerbose()) 
        {
            $this->output->writeln("DomainesExterne");
        }
        foreach ($etablissement->getDomainesExterne() as $n1)
        {
            $n1Affectes->Add($n1->getReferentielExterneN1());
            if ($this->output->isVeryVerbose()) 
            {
                $this->output->writeln("----".$n1."(".$n1.")");
            }
        }
        return ($n1Affectes);
    }             
    
    protected function verifEtab(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        
        ici:
        $this->output->writeln("<info>\n\n ------- Etablissement : ".$etablissement." (".$etablissement->getId().")-------  </info>");
        if (! $etablissement->getReferentielExterne())
        {
            $this->output->writeln("<error>  Pas de référentiel externe </error>");
            return(0);
        }
        else 
        {
            $this->output->writeln("<info>".$etablissement->getReferentielExterne()."</info>");
        }
 
        $this->verifEtabDomaines($etablissement);
         
        if (count($etablissement->getReferentielExterneNiv1())==0) {$this->output->writeln("<error>++++++++ getReferentielExterneNiv1</error>");}
        else { $this->output->writeln("<info>++++++++ getReferentielExterneNiv1 : ".count($etablissement->getReferentielExterneNiv1())."</info>"); }
            
  
        $nb_a_rajouter=$this->createDomaideExterne($etablissement);
        

        
        if ($nb_a_rajouter)
        {
            $this->verifEtabDomaines($etablissement);
        }
        
        
        $domainesExterneTab=$this->getTabDomaineExterne($etablissement);
        
        
        if (! $domainesExterneTab)
        {
            $this->output->writeln("<error>\nEtablissement : ".$etablissement." (".$etablissement->getId().") -- Pas de domainesExterneTab défini</error>");
            /*
            $nb_a_rajouter=$this->createDomaideExterne($etablissement);
            $this->verifEtabDomaines($etablissement);
            $domainesExterneTab=$this->getTabDomaineExterne($etablissement);
            */
            if (! $domainesExterneTab)
            {
                $this->em->flush();
                $this->output->writeln("<error>\nEtablissement : ".$etablissement." (".$etablissement->getId().") -- TOUJOURS PAS Pas de domainesExterneTab défini</error>");
                return(0);
            }
        }

        
        
        if ($nb_a_rajouter)
        {
            $this->output->writeln("<error>".$nb_a_rajouter." à rajouter </error>");
        }
        else
        {
                if ($this->output->isVerbose()) 
                {
                    $this->output->writeln("<info>tous les référentiels externe N1 existent déja.<info>");
                }
        }
 
        if ($this->output->isVeryVerbose())
        {
            $this->output->writeln("référentiels externe N1 : ");
        }
         
        
        $nb_a_modifier=0;
        $this->output->writeln("<info>Référentiel externe  : ".$etablissement->getReferentielExterne()."</info>");
        foreach ($etablissement->getCriteres() as $critere)
        {
            if ($critere->GetDomaineExterne())
            {
                if ($this->output->isVeryVerbose()) 
                {
                 $this->output->writeln("<info>-->".$critere->GetId()." --- ".$critere->GetNumero()." : ".$critere."</info>");
                }
            }
            else
            {
                if ($critere->getReferentielExterneN1OK())
                {
                    if ($this->output->isVerbose()) 
                    {
                         $this->output->writeln("<info>-->".$critere->GetId()." --- ".$critere->GetNumero()." : ".$critere."</info>");
                    }
                }
                else
                {
                    $nb_a_modifier++;

                    if ($this->output->isVeryVerbose()) 
                    {
                        $this->output->writeln("<error>-->".$critere->GetId()." --- ".$critere->GetNumero()." : ".$critere."</error>");
                        $this->output->writeln("|--->".$critere->getReferentielExterneN1Normal()->GetId()." : ".$critere->getReferentielExterneN1Normal());
                    }
                    if ($this->forceWrite)
                    {
                        $critere->setDomaineExterne($domainesExterneTab[$critere->getReferentielExterneN1Normal()->GetId()]);
                        $this->em->persist($critere);
                        $this->em->flush();
                    }
                }
            }
        }
        
        if ($nb_a_modifier)
        {
            $this->output->writeln("<error>-->".$nb_a_modifier." critères à modifier</error>");
        }
        else
        {
            $this->output->writeln("<info>-->Tous les critères sont OK</info>");
        }
        
       
        
        $this->verifEtabDomaines( $etablissement);

        
        
    }
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output=$output;
              
      
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $this->em=$em;
          
        $em->getConfiguration()->setSQLLogger(null);

        $forceWrite = $input->getOption('force-write');
        if ($forceWrite)
        {
            $this->forceWrite=true;
            $this->output->writeln("<info>Attention !! écrit dans la base</info>");
        }
        
        
        
        $referentielExterneId= $input->getOption('referentiel_externe_id');
        $referentielPublicId= $input->getOption('referentiel_public_id');
        $etablissementId= $input->getOption('etablissement_id');
        $referentielExterne = $this->em->getRepository("Pericles3Bundle:ReferentielExterne")->findOneById($referentielExterneId);
        $etablissement = $this->em->getRepository("Pericles3Bundle:Etablissement")->findOneById($etablissementId);
        $referentielPublic = $this->em->getRepository("Pericles3Bundle:ReferentielPublic")->findOneById($referentielPublicId);
        
        if ($referentielExterneId and ! $referentielExterne)
        {
            $referentielsExterne = $this->em->getRepository('Pericles3Bundle:ReferentielExterne')->FindAll();
            foreach ($referentielsExterne as $referentielExterne)
            {
                $this->output->writeln("-->".$referentielExterne->GetId()." : ".$referentielExterne);
            }
            return(0);
        }
        
        if (!$referentielExterneId and $etablissementId)
        {
            $this->verifEtab($etablissement);
        }
        elseif ($referentielExterneId and ! $etablissementId) 
        {
            $this->output->writeln("<info>-->Referentiel Externe". $referentielExterne."</info>");
            $etablissements = $this->em->getRepository('Pericles3Bundle:Etablissement')->FindByrefExterne($referentielExterne);
            foreach ($etablissements as $etablissement)
            {
                $this->verifEtab($etablissement);
            }
        }
        elseif ($input->getOption('all-etab'))
        {
            $etablissements = $this->em->getRepository('Pericles3Bundle:Etablissement')->findFiniWithRefExterne();
            foreach ($etablissements as $etablissement)
            {
                $this->verifEtab($etablissement);
            }
        }
        elseif ($referentielPublic)
        {
            
            $this->output->writeln("<info>-->Referentiel Public". $referentielPublic."</info>");

            foreach ($referentielPublic->GetEtablissements() as $etablissement)
            {
                $this->verifEtab($etablissement);
            }
        }
        else
        {
            $this->output->writeln("<error>Rien a vérifier</error>");
        }
    }
}
