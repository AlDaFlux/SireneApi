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


class ReferentielAddDimensionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('referentiel:add-dimension');
        $this->setDescription("Ajoute une dimension à un référentiel et tous ces établissement");
        $this->setHelp("Ajoute une dimension à un référentiel et tous ces établissement");
        $this->addOption('referentiel_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel",0);
        $this->addOption('domaine_ordre',null,InputOption::VALUE_REQUIRED,"Le numéro d'ordre du domaine",0);
        $this->addOption('libelle',null,InputOption::VALUE_REQUIRED,"Libelle de la dimensions",0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $referentielId= $input->getOption('referentiel_id');
        $referentielPublic = $em->getRepository("Pericles3Bundle:ReferentielPublic")->findOneById($referentielId);
        
        if (! $referentielPublic)
        {
            $output->writeln("<error>Vous devez choisir un refentiel public : --referentiel_id=? <error>");
            $referentielsPublic = $em->getRepository("Pericles3Bundle:ReferentielPublic")->findAll();
            foreach ($referentielsPublic as $referentielPublic)
            {
                $output->writeln("<info>".$referentielPublic->GetId()." : ".$referentielPublic."<info>");
            }
            return(0);
        }
        else
        {
            $output->writeln("Public : ");
            $output->writeln("--->".$referentielPublic);
            $order_domaine=$input->getOption('domaine_ordre');
            
                $domaineRefChoisi=null;
                foreach ($referentielPublic->getReferentielDomaines() as $domaine)
                {
                    if ($domaine->GetOrdre()==$order_domaine) 
                    {
                        $domaineRefChoisi=$domaine;
                    }
                }
                if ($domaineRefChoisi)
                {
                    $output->writeln("<info> Domaine : ".$domaineRefChoisi->GetOrdre()." : ".$domaineRefChoisi." (".$domaineRefChoisi->GetId().")</info>");
//                    print_r($domaineRefChoisi->getChildren());
                    
                    $ordre=1;
                    foreach ($domaineRefChoisi->getChildren() as $dim)
                    {
                        $output->writeln("Dimensions exitantes : ".$dim->GetNumero()." : ".$dim);
                        $ordre++;
                    }
                    if (! $input->getOption('libelle'))
                    {
                        $output->writeln("<error>Vous devez choisir un refentiel intitulé pour la dimensions : --libelle=? <error>");
                        return(0);
                    }
                    $output->writeln("<info> Nouvelle dimensions : ".$domaineRefChoisi->GetOrdre().".".$ordre." : ".$input->getOption('libelle')."</info>");
                    $dimensionRef = new \Pericles3Bundle\Entity\Referentiel();
                    
                    $dimensionRef->setReferentielPublic($referentielPublic);
                    $dimensionRef->setNom($input->getOption('libelle'));
                    $dimensionRef->setNomCourt($input->getOption('libelle'));
                    $dimensionRef->setOrdre($ordre);
                    $dimensionRef->setParent($domaineRefChoisi);
                    $dimensionRef->setTypeReferentiel($em->getRepository('Pericles3Bundle:TypeReferentiel')->findOneById(2));
                    $dimensionRef->setVerifie(true);
                    $dimensionRef->setNonConcerne(false);
                    $em->persist($dimensionRef);
                    $em->flush();
                    
                    $output->writeln("<info>Ajout dans les établissements : </info>");
                    // $newDimensionRef=;
                    foreach ($domaineRefChoisi->GetDomaines() as $dom)
                    {
                        $dimension=new \Pericles3Bundle\Entity\Dimension();
                        $dimension->setDomaine($dom);
                        $dimension->setReferentiel($dimensionRef);
                        $em->persist($dimension);
                        $em->flush();
                        $output->writeln("-->  ".$dom->GetEtablissement().")");
                    }
                    //$output->writeln("Dimensions exitantes : ".$domaineRefChoisi->GetOrdre().".".$ordre." : !! NEW !! ");
                }
                else
                {
                    $output->writeln("<error>Le domaine avec le numero d'ordre ".$order_domaine." n'existe pas</error>");
                    $output->writeln("<error>Vous devez choisir le numéro d'ordre d'un domaine: --domaine_ordre=? <error>");
                    foreach ($referentielPublic->getReferentielDomaines() as $domaine)
                    {
                        $output->writeln("<info> ".$domaine->GetOrdre()." : ".$domaine." (".$domaine->GetId().")<info>");
                    }
                }
                
                
 
            
        }
         
    }
}
