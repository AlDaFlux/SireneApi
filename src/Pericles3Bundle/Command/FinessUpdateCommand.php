<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    

use Symfony\Component\Console\Input\ArrayInput;
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class FinessUpdateCommand extends ArseneCommand
{
    protected function configure()
    {
        $this->setName('finess:update:database');
        $this->setDescription('Mets a jour les tables finess avec les tables finess imports');
        $this->setHelp("Mets a jour les tables finess avec les tables finess imports");
        $this->addOption('force',null,InputOption::VALUE_NONE,"supprimme les liasons avec les finess pour ceux supprimés");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        gc_collect_cycles();
        $em->clear();
        
        $force = $input->getOption('force');

        $deletedWithEtab=$em->getRepository('Pericles3Bundle:Finess')->findSupprimerDansImportAvecEtablissement();
        if ($deletedWithEtab)
        {
            foreach ($deletedWithEtab as $finess)
            {
                $output->writeln("<error>Etablissement : ".$finess."</error>");
                if ($force)
                {
               
                    $demande=$finess->getDemandesEtablissement();
                    if ($demande)
                    {
                        $demande->setCommentaireAncreai($demande->getCommentaireAncreai()." - Finess desuet : ".$finess);
                        $demande->setFiness(null);
                        $finess->setDemandesEtablissement(null);
                        $em->persist($finess);
                        $em->persist($demande);
                        $em->flush();
                    }
                    $pericles=$finess->getPericles();
                    
                    print_r(count($pericles));
                            
                    if ($pericles)
                    {
                        $output->writeln("<error>Pericles: ".$finess."</error>");
                        foreach ($pericles as $pericle)
                        {
                            print_r(count($pericle));
                            $output->writeln("<error>Pericle: ".$pericle."</error>");
                            $pericle->setFinessEtablissement(null);
                            $finess->removePericle($pericle);
                            $em->persist($finess);
                            $em->persist($pericle);
                            $em->flush();
                        }
                    }
                    else
                    {
                        $output->writeln("<error>PAS pericles: </error>");

                    }
                    
                    /*     $etablissement=$finess->GetEtablissement();
                    $etablissement->setFiness(null);
                    $finess->setEtablissement(null);
                    $em->persist($finess);
                    $em->persist($etablissement);
                    $em->flush();
*/
                    
                }
            }
            return(0);
        }

        
        return(0);

        $deletedWithGestionnaire=$em->getRepository('Pericles3Bundle:FinessGestionnaire')->findSupprimerDansImportAvecGestionnaire();
        
        if ($deletedWithGestionnaire)
        {
        foreach ($deletedWithGestionnaire as $finess)
            {
                $output->writeln("<error>Gestionnaire : ".$finess."</error>");
                if ($force)
                {
                    $gestionnaire=$finess->GetGestionnaire();
                    $gestionnaire->setFiness(null);
                    $finess->setGestionnaire(null);
                    $em->persist($finess);
                    $em->persist($gestionnaire);
                    $em->flush();
                }
            }
            return(0);
        }            
        


//        $sql="SELECT * FROM etablissement";
        $this->input=$input;
        $this->output=$output;
        
        
       $sql="INSERT INTO departement (id, lib_departement) SELECT DISTINCT  finess_gestionnaire_import.departement_id, '???' FROM finess_gestionnaire_import  
LEFT JOIN departement ON departement_id=departement.id
WHERE departement.id IS NULL
ORDER BY `finess_gestionnaire_import`.`departement_id`  DESC";
        $output->writeln("<info>Insertion des départements inconnus .. </info>");
        $this->runSQL($sql);
                
                
                
 $sql="INSERT INTO finess_gestionnaire( code_finess,departement_id, raison_sociale, complement_adresse,code_postal,ville,tel)  
SELECT finess_gestionnaire_import.code_finess, finess_gestionnaire_import.departement_id, finess_gestionnaire_import.raison_sociale, finess_gestionnaire_import.complement_adresse,finess_gestionnaire_import.code_postal,finess_gestionnaire_import.ville,finess_gestionnaire_import.tel
FROM finess_gestionnaire_import LEFT JOIN finess_gestionnaire ON finess_gestionnaire_import.code_finess = finess_gestionnaire.code_finess
WHERE finess_gestionnaire.code_finess IS NULL;";
        $output->writeln("<info>Insertion des nouveaux gestionnaire !  </info>");
        $this->runSQL($sql);

 $sql="INSERT INTO finess( code_finess,code_categorie ,departement_id, raison_sociale, complement_adresse,code_postal,ville,capacite_totale1,tel,gestionnaire_id)  
SELECT finess_import.code_finess, finess_import.code_categorie ,finess_import.departement_id, finess_import.raison_sociale, finess_import.complement_adresse,finess_import.code_postal,finess_import.ville,finess_import.capacite_totale1,finess_import.tel,finess_import.gestionnaire_id
FROM finess_import LEFT JOIN finess ON finess_import.code_finess = finess.code_finess
WHERE finess.code_finess IS NULL;";
        $output->writeln("<info>Insertion des nouveaux établissements !  </info>");
        $this->runSQL($sql);

 $sql="UPDATE `finess`,finess_import 
SET finess.code_categorie = finess_import.code_categorie, finess.departement_id=finess_import.departement_id, finess.raison_sociale=finess_import.raison_sociale, finess.complement_adresse=finess_import.complement_adresse,
finess.code_postal = finess_import.code_postal,finess.ville = finess_import.ville,finess.capacite_totale1 = finess_import.capacite_totale1,finess.tel = finess_import.tel, finess.gestionnaire_id = finess_import.gestionnaire_id
WHERE finess_import.code_finess = finess.code_finess;
";
        $output->writeln("<info>Mise à jour des établissements</info>");
        $this->runSQL($sql);

$sql="UPDATE `finess_gestionnaire`,finess_gestionnaire_import 
SET  finess_gestionnaire.departement_id=finess_gestionnaire_import.departement_id, finess_gestionnaire.raison_sociale=finess_gestionnaire_import.raison_sociale, finess_gestionnaire.complement_adresse=finess_gestionnaire_import.complement_adresse,
finess_gestionnaire.code_postal = finess_gestionnaire_import.code_postal,finess_gestionnaire.ville = finess_gestionnaire_import.ville, finess_gestionnaire.tel = finess_gestionnaire_import.tel
WHERE finess_gestionnaire_import.code_finess  = finess_gestionnaire.code_finess ;
";
        $output->writeln("<info>Mise à jour des gestionnaires !  </info>");
        $this->runSQL($sql);

 $sql="DELETE finess.* FROM finess LEFT JOIN etablissement ON finess.code_finess = etablissement.finess LEFT JOIN finess_import ON finess.code_finess = finess_import.code_finess
WHERE etablissement.finess IS NULL AND finess_import.code_finess IS NULL;
";
        $output->writeln("<info>supression des établissements !  </info>");
        $this->runSQL($sql);

 $sql="DELETE finess_gestionnaire.* FROM finess_gestionnaire LEFT JOIN gestionnaire ON finess_gestionnaire.code_finess = gestionnaire.finess_id 
LEFT JOIN finess_gestionnaire_import ON finess_gestionnaire.code_finess = finess_gestionnaire_import.code_finess
WHERE gestionnaire.finess_id IS NULL AND finess_gestionnaire_import.code_finess IS NULL;";
        $output->writeln("<info>supression des  gestionnaires !  </info>");
        $this->runSQL($sql);
 
        $output->writeln("Fin de la mise a jour des finess ! ");

    }
    
    
    
}
