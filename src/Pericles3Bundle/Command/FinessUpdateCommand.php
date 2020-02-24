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
        
        $this->input=$input;
        $this->output=$output;
        
        $exec=true;
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        gc_collect_cycles();
        $em->clear();
        
        $force = $input->getOption('force');

    
        
        $deletedWithDemande=$em->getRepository('Pericles3Bundle:Finess')->findSupprimerDansImportAvecDemande();
        $deletedWithPericles=$em->getRepository('Pericles3Bundle:Finess')->findSupprimerDansImportAvecPericles();
        $deletedWithEtab=$em->getRepository('Pericles3Bundle:Finess')->findSupprimerDansImportAvecEtablissement();

        
        $gestionnaireDeletedWithGestionnaire=$em->getRepository('Pericles3Bundle:FinessGestionnaire')->findSupprimerDansImportAvecGestionnaire();
        $gestionnaireDeletedWithDemande=$em->getRepository('Pericles3Bundle:FinessGestionnaire')->findSupprimerDansImportAvecDemande();
        $gestionnaireDeletedWithPericles=$em->getRepository('Pericles3Bundle:FinessGestionnaire')->findSupprimerDansImportAvecPericles();
        

        if ($deletedWithDemande)
        {
            $output->writeln("<error> Des finess ont des demandes</error>");
            $this->printFinesses($deletedWithDemande);
            $exec=false;
        }
        if ($deletedWithPericles)
        {
            $output->writeln("<error> Des finess sont lés a des pericles</error>");
            $this->printFinesses($deletedWithPericles);
            $exec=false;
        }
        
        if ($deletedWithEtab)
        {
            $output->writeln("<error> Des finess ont des établissements</error>");
            $this->printFinesses($deletedWithEtab);
            $exec=false;
        }
        
        
        
        
        
        
        if ($gestionnaireDeletedWithGestionnaire)
        {
            $output->writeln("<error> Des finess gestionnaire ont des gestionnaire</error>");
            $this->printFinesses($gestionnaireDeletedWithGestionnaire, "Gestionnaire");
            $exec=false;
        }
        
        if ($gestionnaireDeletedWithDemande)
        {
            $output->writeln("<error> Des finess gestionnaire ont des demandes</error>");
            $this->printFinesses($gestionnaireDeletedWithDemande, "Gestionnaire");
            $exec=false;
        }
        if ($gestionnaireDeletedWithPericles)
        {
            $output->writeln("<error> Des finess gestionnaire sont lés a des pericles</error>");
            $this->printFinesses($gestionnaireDeletedWithPericles, "Gestionnaire");
            $exec=false;
        }
        
        
        
        
        if (! $exec && ! $force)
        {
             $output->writeln("<error>Mise à jour arreté ... uiliser --force</error>");
            return(0);
        }
         
        
        foreach ($deletedWithDemande as $finess) {  $this->unlinkDemande($finess); }
        foreach ($deletedWithPericles as $finess) { $this->unlinkPericles($finess); }
        foreach ($deletedWithEtab as $finess) { $this->unlinkEtablissement($finess); }

        
        foreach ($gestionnaireDeletedWithDemande as $finess) {  $this->unlinkDemandeGestionnaire($finess); }
        foreach ($gestionnaireDeletedWithPericles as $finess) { $this->unlinkPericlesGestionnaire($finess); }
        foreach ($gestionnaireDeletedWithGestionnaire as $finess) { $this->unlinkGestionnaire($finess); }
         

        /*
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
        }            */
  
        
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
 
        
  $sql="DELETE FROM `finess_gestionnaire` WHERE (finess_gestionnaire.code_finess NOT IN (SELECT finess.gestionnaire_id FROM finess) AND finess_gestionnaire.code_finess NOT IN (SELECT gestionnaire.finess_id FROM gestionnaire WHERE gestionnaire.finess_id IS NOT NULL));";
    $this->runSQL($sql);
    $output->writeln("Supression des pharmacies, etc...");

        $output->writeln("Fin de la mise a jour des finess ! ");

    }
    
    
    
    function unlinkDemande($finess)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
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
    }
    
    function unlinkEtablissement($finess)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $etablissememt=$finess->getEtablissement();
        if ($etablissememt)
        {
            $etablissememt->setFiness(null);
            $finess->setEtablissement(null);
            $em->persist($finess);
            $em->persist($etablissememt);
            $em->flush();
        }
    }
    
    
    function unlinkPericles($finess)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $pericles=$finess->getPericles();
                    
        if ($pericles)
        {
            $this->output->writeln("<error>Pericles: ".$finess."</error>");
            foreach ($pericles as $pericle)
            {
                $this->output->writeln("<error>Pericle: ".$pericle."</error>");
                $pericle->setFinessEtablissement(null);
                $finess->removePericle($pericle);
                $em->persist($finess);
                $em->persist($pericle);
                $em->flush();
            }
        }

    }
    
    
    
    
    
    function unlinkDemandeGestionnaire($finess)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $demandes=$finess->getDemandesGestionnaire();
        foreach ($demandes as $demande)
        {
            $demande->setCommentaireAncreai($demande->getCommentaireAncreai()." - Finess desuet : ".$finess);
            $demande->setFiness(null);
            $finess->removeDemandesGestionnaire($demande);
            $em->persist($finess);
            $em->persist($demande);
            $em->flush();            
        }
    }
    
    function unlinkGestionnaire($finess)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $gestionnaire=$finess->getGestionnaire();
        if ($gestionnaire)
        {
            $gestionnaire->setFiness(null);
            $finess->setGestionnaire(null);
            $em->persist($finess);
            $em->persist($gestionnaire);
            $em->flush();
        }
    }
    
    
    function unlinkPericlesGestionnaire($finess)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $pericles=$finess->getPericles();
                    
        if ($pericles)
        {
            $this->output->writeln("<error>Pericles: ".$finess."</error>");
            foreach ($pericles as $pericle)
            {
                $this->output->writeln("<error>Pericle: ".$pericle."</error>");
                $pericle->setFinessGestionnaire(null);
                $finess->removePericle($pericle);
                $em->persist($finess);
                $em->persist($pericle);
                $em->flush();
            }
        }

    }
    
    
    function printFinesses($finesses, $lib="Etablissement")
    {
       foreach ($finesses as $finess)
       {
            $this->output->writeln("".$lib." : ".$finess."");
       }        
    }
    
     
    
    
}
