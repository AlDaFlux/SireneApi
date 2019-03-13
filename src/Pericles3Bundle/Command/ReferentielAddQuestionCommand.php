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


class ReferentielAddQuestionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('referentiel:add-question');
        $this->setDescription("Ajoute une question à un référentiel et tous ces établissement");
        $this->setHelp("Ajoute une question  à un référentiel et tous ces établissement");
        $this->addOption('referentiel_id',null,InputOption::VALUE_REQUIRED,"L'identifiant de du régérentiel",0);
        $this->addOption('critere_numero',null,InputOption::VALUE_REQUIRED,"Le numéro du critre",0);
        $this->addOption('libelle',null,InputOption::VALUE_REQUIRED,"Libelle de la question",0);
        $this->addOption('reponse-oui',null,InputOption::VALUE_REQUIRED,"Libelle de la question",0);
        $this->addOption('reponse-non',null,InputOption::VALUE_REQUIRED,"Libelle de la question",0);
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
                $output->writeln("<info>".$referentielPublic->GetId()." : ".$referentielPublic."</info>");
            }
            return(0);
        }
        else
        {
            $output->writeln("Public : ");
            $output->writeln("--->".$referentielPublic);
            if (! $input->getOption('critere_numero'))
            {
                $output->writeln("<error>Vous devez ajouter une critere --critere_numero=?.? </error>");
                return(0);
            }
            $critere_numero= $input->getOption('critere_numero');
     
                $critereRefchoisi=null;
                foreach ($referentielPublic->getReferentielCriteres() as $crit)
                {
                    if ($crit->GetNumero()==$critere_numero) 
                    {
                        $critereRefchoisi=$crit;
                    }
                }
                
                if ($critereRefchoisi)
                {
                    $output->writeln("<info> Dimensions : ".$critereRefchoisi->GetNumero()." : ".$critereRefchoisi." (".$critereRefchoisi->GetId().")</info>");
                    $ordre=1;
                    foreach ($critereRefchoisi->getChildren() as $quest)
                    {
                        $output->writeln("Criteres exitants : ".$quest->GetNumero()." : ".$quest);
                        $ordre++;
                    }
                    if (! $input->getOption('libelle'))
                    {
                        $output->writeln("<error>Vous devez choisir un intitulé pour le critere : --libelle=? <error>");
                        return(0);
                    }
                    $output->writeln("<info> Nouvelle dimensions : ".$critereRefchoisi->GetNumero().".".$ordre." : ".$input->getOption('libelle')."</info>");

                    $questionRef = new \Pericles3Bundle\Entity\Referentiel();
                    $questionRef->setReferentielPublic($referentielPublic);
                    $questionRef->setNom($input->getOption('libelle'));
                    $questionRef->setNomCourt($input->getOption('libelle'));
                    $questionRef->setOrdre($ordre);
                    $questionRef->setReponseOui($input->getOption('reponse-oui'));
                    $questionRef->setReponseNon($input->getOption('reponse-non'));
                    $questionRef->setParent($critereRefchoisi);
                    $questionRef->setTypeReferentiel($em->getRepository('Pericles3Bundle:TypeReferentiel')->findOneById(4));
                    $questionRef->setVerifie(true);
                    $questionRef->setNonConcerne(false);
                    $em->persist($questionRef);
                    $em->flush();
                    
                    $output->writeln("<info>Ajout dans les établissements : </info>");
                    // $newDimensionRef=;
                    foreach ($critereRefchoisi->GetCriteres() as $crit)
                    {
                        $question=new \Pericles3Bundle\Entity\Question();
                        $question->setCritere($crit);
                        $question->setReferentiel($questionRef);
                        $question->setReponse(null);
                        $em->persist($question);
                        $em->flush();
                        $output->writeln("-->  (".$question->GetEtablissement().")");
                    }
                    //$output->writeln("Dimensions exitantes : ".$domaineRefChoisi->GetOrdre().".".$ordre." : !! NEW !! ");
                }
                else
                {
                    $output->writeln("<error>La dimension avec le numero ".$critere_numero." n'existe pas</error>");
                }
                 
        }
         
    }
}