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


class ReferentielAddCritereCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('referentiel:add-critere');
        $this->setDescription("Ajoute une critere à un référentiel et tous ces établissement");
        $this->setHelp("Ajoute un critere à un référentiel et tous ces établissement");
        $this->addOption('referentiel_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel",0);
        $this->addOption('referentiel_externe_numero',null,InputOption::VALUE_REQUIRED,"Le numero du referentiel externe",0);
        $this->addOption('dimension_numero',null,InputOption::VALUE_REQUIRED,"Le numéro de la dimension ",0);
        $this->addOption('libelle',null,InputOption::VALUE_REQUIRED,"Libelle du critere",0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $referentielId= $input->getOption('referentiel_id');
        $referentielExterneNum= $input->getOption('referentiel_externe_numero');
        $referentielPublic = $em->getRepository("Pericles3Bundle:ReferentielPublic")->findOneById($referentielId);
        
        $refExterneChoisi=null;

                            

        if (! $referentielPublic)   
        {
            $output->writeln("<error>Vous devez choisir un refentiel public : --referentiel_id=? <error>");
            $referentielsPublic = $em->getRepository("Pericles3Bundle:ReferentielPublic")->findAll();
            foreach ($referentielsPublic as $referentielPublic)
            {
                $output->writeln("<info>".$referentielPublic->GetId()." : ".$referentielPublic."</info>");
            }
            return(0);
        }
        else
        {
            $output->writeln("Public : ");
            $output->writeln("--->".$referentielPublic);
            
            
            if ($referentielPublic->GetReferentielExterne())
            {
                $output->writeln("<info>Référentiel externe : ".$referentielPublic->GetReferentielExterne()."</info>");
                if (! $referentielExterneNum)
                {
                    $output->writeln("<error>Vous devez choisir une referentiel externe pour ce critere : --referentiel_externe_numero=? </error>");
                    foreach ($referentielPublic->GetReferentielExterne()->GetReferentielExterneNiv1() as $refExterne)
                    {
                        $output->writeln($refExterne->numeroOrdre()." : ".$refExterne);
                    }
                    return(0);
                }
                
                else
                {
                    foreach ($referentielPublic->GetReferentielExterne()->GetReferentielExterneNiv1() as $refExterne)
                    {
                        if ($refExterne->numeroOrdre()==$referentielExterneNum)
                        {
                            $refExterneChoisi=$refExterne;
                        }
                    }
                    if ($refExterneChoisi)
                    {
                        $output->writeln("<info>Le domaine externe choisi : ".$refExterneChoisi."</info>");
                    }
                    else
                    {
                        $output->writeln("<error>Le domaine externe n'existe pas : --referentiel_externe_numero=? </error>");
                        foreach ($referentielPublic->GetReferentielExterne()->GetReferentielExterneNiv1() as $refExterne)
                        {
                            $output->writeln($refExterne->numeroOrdre()." : ".$refExterne);
                        }
                        return(0);
                    }
                }
                
            }
            
            
            
            if (! $input->getOption('dimension_numero'))
            {
                $output->writeln("<error>Vous devez ajouter une dimension --dimension_numero=?.? </error>");
                return(0);
            }
            $dimension_numero_tab= explode(".", $input->getOption('dimension_numero'));
            $dimension_numero= $input->getOption('dimension_numero');
                    
            
            $order_domaine=$dimension_numero[0];
            $order_dimension=$dimension_numero[1];

            


                       
     
                $dimensionRefchoisi=null;
                foreach ($referentielPublic->getReferentielDimensions() as $dim)
                {
                    if ($dim->GetNumero()==$dimension_numero) 
                    {
                        $dimensionRefchoisi=$dim;
                    }
                }
                
                if ($dimensionRefchoisi)
                {
                    $output->writeln("<info> Dimensions : ".$dimensionRefchoisi->GetNumero()." : ".$dimensionRefchoisi." (".$dimensionRefchoisi->GetId().")</info>");
                    $ordre=1;
                    foreach ($dimensionRefchoisi->getChildren() as $crit)
                    {
                        $output->writeln("Criteres exitants : ".$crit->GetNumero()." : ".$crit);
                        $ordre++;
                    }
                    if (! $input->getOption('libelle'))
                    {
                        $output->writeln("<error>Vous devez choisir un intitulé pour le critere : --libelle=? <error>");
                        return(0);
                    }
                    $output->writeln("<info> Nouvelle dimensions : ".$dimensionRefchoisi->GetNumero().".".$ordre." : ".$input->getOption('libelle')."</info>");
                    $crirtereRef = new \Pericles3Bundle\Entity\Referentiel();
                    $crirtereRef->setReferentielPublic($referentielPublic);
                    $crirtereRef->setNom($input->getOption('libelle'));
                    $crirtereRef->setNomCourt($input->getOption('libelle'));
                    $crirtereRef->setOrdre($ordre);
                    
                    if ($refExterneChoisi) { $crirtereRef->setReferentielExterneNiv1($refExterneChoisi); }
                    
                    $crirtereRef->setParent($dimensionRefchoisi);
                    $crirtereRef->setTypeReferentiel($em->getRepository('Pericles3Bundle:TypeReferentiel')->findOneById(3));
                    $crirtereRef->setVerifie(true);
                    $crirtereRef->setNonConcerne(false);
                    $em->persist($crirtereRef);
                    $em->flush();
                    
                    $output->writeln("<info>Ajout dans les établissements : </info>");
                    // $newDimensionRef=;
                    foreach ($dimensionRefchoisi->GetDimensions() as $dim)
                    {
                        $critere=new \Pericles3Bundle\Entity\Critere();
                        $critere->setDimension($dim);
                        $critere->setReferentiel($crirtereRef);
                        $critere->setArevoir(4);
                        $em->persist($critere);
                        $em->flush();
                        $output->writeln("-->   ".$critere->GetEtablissement().")");

                        if ($refExterneChoisi)
                        { 
                              //  $output->writeln("---------------------------->  A FAIRE ! rajouter domaine externe !!! <----------------------------");
                        }

                    }
                    if ($referentielPublic->GetReferentielExterne())
                    {
                        $command = $this->getApplication()->find('referentiel:externe-verif');
                        $arguments = array('command' => 'referentiel:externe-verif','--referentiel_public_id' => $referentielPublic->GetId(),'--force-write' => true);
                        $args = new ArrayInput($arguments);
                        $command->run($args, $output);
                    }

                    //$output->writeln("Dimensions exitantes : ".$domaineRefChoisi->GetOrdre().".".$ordre." : !! NEW !! ");
                }
                else
                {
                    $output->writeln("<error>La dimension avec le numero ".$dimension_numero." n'existe pas</error>");
                }
                
                 
            
        }
         
    }
}
