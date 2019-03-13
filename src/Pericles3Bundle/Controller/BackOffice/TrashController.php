<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Pericles3Bundle\Entity\Facture;
use Pericles3Bundle\Entity\FacturePresta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Dompdf\Dompdf;



/**
 * Facture controller.
 *
 * @Route("backoffice/trash")
 */
class TrashController extends AdminController
{
    /**
     * Lists all facture entities.
     *
     * @Route("/", name="trash_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ObjectifsStrategiques  =$em->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->FindReferentielDesuet();
        $objectifOperationnels =$em->getRepository('Pericles3Bundle:ObjectifOperationnel')->FindReferentielDesuet();
        $syntheses =$em->getRepository('Pericles3Bundle:CommentaireDomaine')->FindReferentielDesuet();
        $constats =$em->getRepository('Pericles3Bundle:Constat')->FindReferentielDesuet();
        $preuves =$em->getRepository('Pericles3Bundle:Preuve')->FindReferentielDesuet();
        
        
        $referentielsExternePasBon=$em->getRepository('Pericles3Bundle:ReferentielExterne')->referentielsExternePasBon();
        
        
        
        return $this->render('BackOffice/Trash/index.html.twig', 
                ['ObjectifsStrategiques'=>$ObjectifsStrategiques,
                    'objectifOperationnels'=>$objectifOperationnels,
                    'syntheses'=>$syntheses,
                    'constats'=>$constats,
                    'preuves'=>$preuves,
                    'referentielsExternePasBon'=>$referentielsExternePasBon,
                ]);
        
    }
    
    
      /**
     * Lists all facture entities.
     *
     * @Route("/domaines", name="trash_domaines")
     * @Method("GET")
     */
    public function indexdomainesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $domaines =$em->getRepository('Pericles3Bundle:Domaine')->FindAll();
        $domaines_desuet =$em->getRepository('Pericles3Bundle:Domaine')->FindReferentielDesuet();
        
        return $this->render('BackOffice/Trash/domaines.html.twig', ['domaines'=>$domaines,'domaines_desuet'=>$domaines_desuet]);
        
    }
    
    
      /**
     * Lists all facture entities.
     *
     * @Route("/preuves", name="trash_preuves")
     * @Method("GET")
     */
    public function indexPreuvesAction()
    {
        $preuves =$this->getPreuvessansFichiers();
        
        $lastDate = New \DateTime('2000-01-01');
        foreach ($preuves as $preuve)
        {
            if ($preuve->getdateCreate()>$lastDate)
            {
                $lastDate=$preuve->getdateCreate();
            }
        }
        $today=New \DateTime();
        $nbDays=$today->diff($lastDate)->days;

        if ($nbDays>50) { $this->addFlash('success', $nbDays." jours sans perte de preuves"); }
        else  { $this->addFlash('danger', $nbDays." jours sans perte de preuves"); }

        return $this->render('BackOffice/Trash/preuves.html.twig', ['preuves'=>$preuves,'lastDate'=>$lastDate]);
    }
    
   
    function getPreuvessansFichiers()
    {
        $em = $this->getDoctrine()->getManager();
        $preuves =$em->getRepository('Pericles3Bundle:Preuve')->findAllFichier();

        $preuvesSansFichier= new \Doctrine\Common\Collections\ArrayCollection(); 

        foreach ($preuves as $preuve)
        {
            if (! $preuve->getFileExist())
            {
                $preuvesSansFichier->Add($preuve);
            }
        }
        return($preuvesSansFichier);
    }
    
    
    
    
    
    
    
    
      /**
     * Lists all facture entities.
     *
     * @Route("/preuves/link_biblio", name="trash_preuves_link_biblio")
     * @Method("GET")
     */
    public function indexPreuvesLinkBilioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $preuves =$em->getRepository('Pericles3Bundle:Preuve')->findAllFichier();
        
        foreach ($preuves as $preuve)
        {
            
                if (! $preuve->GetFileExist())
                {
                    
                    $this->addFlash('success', "filexite pas "); 
                     if ($preuve->getFileExistInBilioEtab())
                     {
                            $this->addFlash('success', "FileExistInBilioEtab"); 
                            $biblio=$em->getRepository('Pericles3Bundle:Bibliotheque')->FindBiblioByFile($preuve->GetEtablissement(),$preuve->GetFichier()) ;
                            if ($biblio)        
                            {
                                $this->addFlash('success', "bilio OK"); 
                                if ($biblio)
                                {
                                    $preuve->SetBibliotheque($biblio);
                                    $biblio->addPreufe($preuve);
                                    $em->persist($preuve);
                                    $em->persist($biblio);
                                    $em->flush();
                                }
                                $this->addFlash('success', "Preuve ".$preuve."-->".$biblio); 

                            }

                     }
                     else
                     {
                            $this->addFlash('error', "Preuve existe pas dans etab".$preuve); 
                     }


                    }
                }

        return $this->redirectToRoute('trash_preuves');

    }
    
       
    

    /**
     * Lists all facture entities.
     *
     * @Route("/sauvegardes", name="trash_trashsauvegarde")
     * @Method("GET")
     */
    public function indexTrashSauvegardeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sauvegardes =$em->getRepository('Pericles3Bundle:Sauvegarde')->findAll();
        return $this->render('BackOffice/Trash/sauvegardes.html.twig', ['sauvegardes'=>$sauvegardes]);
    }
    
    
     /**
     * Lists all facture entities.
     *
     * @Route("/biblio", name="trash_biblio")
     * @Method("GET")
     */
    public function indexbiblioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $biblios =$em->getRepository('Pericles3Bundle:Bibliotheque')->findFichiers();
        $biblios_manquant= new \Doctrine\Common\Collections\ArrayCollection(); 
        foreach ($biblios as $biblio)
        {
            if (! $biblio->getFileExist())
            {
                $biblios_manquant->add($biblio);
            }
        }
        
        $lastDate = New \DateTime('2000-01-01');
        foreach ($biblios_manquant as $biblio)
        {
            if ($biblio->getDateUpdate()>$lastDate)
            {
                $lastDate=$biblio->getDateUpdate();
                
            }
        }
        $today=New \DateTime();
        $nbDays=$today->diff($lastDate)->days;
        if ($nbDays>50) { $this->addFlash('success', $nbDays." jours sans perte du nom de fichier : ".$lastDate->format("Y-m-d")); }
        else  { $this->addFlash('danger', $nbDays." jours sans perte du nom de fichier : ".$lastDate->format("Y-m-d")); }
 
        
         
        $biblios =$em->getRepository('Pericles3Bundle:Bibliotheque')->findFichiersManquant();
        
        $lastDate = New \DateTime('2000-01-01');
        foreach ($biblios as $biblio)
        {
            if ($biblio->getDateUpdate()>$lastDate)
            {
                $lastDate=$biblio->getDateUpdate();
                
            }
        }
        
        $today=New \DateTime();
        $nbDays=$today->diff($lastDate)->days;
        if ($nbDays>50) { $this->addFlash('success', $nbDays." jours sans perte de fichier dans la bibliotheque : ".$lastDate->format("Y-m-d")); }
        else  { $this->addFlash('danger', $nbDays."  jours sans perte de fichier dans la bibliotheque : ".$lastDate->format("Y-m-d")); }

        
        return $this->render('BackOffice/Trash/biblio.html.twig', ['biblios_vide'=>$biblios, 'biblios_manquant'=>$biblios_manquant]);
    }
    
    
    
    
    /**
     * Lists all facture entities.
     *
     * @Route("/biblio_{id}", name="trash_biblio_id")
     * @Method("GET")
     */
    public function indexbiblioIdAction(\Pericles3Bundle\Entity\Bibliotheque $biblio)
    {
        $em = $this->getDoctrine()->getManager(); 
        $folder=WEB_DIR.'/upload/'.$biblio->getRelativPath();
        $fichiers= new \Doctrine\Common\Collections\ArrayCollection(); 
        if (is_dir($folder))
        {
            $dir = opendir($folder);

            while ($file = readdir($dir)) {
                if ($file != "." && $file != "..") {
                        //$this->addFlash('success', $file); 
                    if ($biblio->GetEtablissement()) $nbiblio=$em->getRepository('Pericles3Bundle:Bibliotheque')->FindIdBiblioByFile($biblio->GetEtablissement(),$file);
                    else $nbiblio=$em->getRepository('Pericles3Bundle:Bibliotheque')->FindIdBiblioGestionnaireByFile($biblio->GetGestionnaire(),$file);
                    if ($nbiblio==0) $fichiers->Add($file);
                }
            } 
        }
         
        return $this->render('BackOffice/Trash/biblio_show.html.twig', ['biblio'=>$biblio, 'fichiers'=>$fichiers]);
        
    }
    
    
    
    /**
     * Lists all facture entities.
     *
     * @Route("/biblio_{id}/link/{filename}", name="trash_biblio_id_link")
     * @Method("GET")
     */
    public function indexbiblioLinkAction(\Pericles3Bundle\Entity\Bibliotheque $biblio,$filename)
    {
        $em = $this->getDoctrine()->getManager(); 
        $biblio->setFichier($filename);
        $em->persist($biblio);
        $em->flush();
        return $this->redirectToRoute('trash_biblio');

    }
    
    
    /**
     * Lists all facture entities.
     *
     * @Route("/etablissement", name="trash_etablissement")
     * @Method("GET")
     */
    public function indexEtablissementAction()
    {
        $em = $this->getDoctrine()->getManager();
        $etablissements =$em->getRepository('Pericles3Bundle:Etablissement')->FindWithReferentielDesuet();
        
        return $this->render('BackOffice/Trash/etablissements.html.twig', ['etablissements'=>$etablissements]);
        
    }
    
    /**
     * Lists all facture entities.
     *
     * @Route("/etablissement/refexterne", name="trash_etablissement_externe")
     * @Method("GET")
     */
    public function indexRefExterneEtablissementAction()
    {
        $em = $this->getDoctrine()->getManager();
        $etablissements =$em->getRepository('Pericles3Bundle:Etablissement')->FindAll();
        return $this->render('BackOffice/Trash/etablissements_refexterne.html.twig', ['etablissements'=>$etablissements]);
    }
    
    /**
     * Lists all facture entities.
     *
     * @Route("/etablissement/ref", name="trash_etablissement_ref")
     * @Method("GET")
     */
    public function indexRefEtablissementAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $etablissements =$em->getRepository('Pericles3Bundle:Etablissement')->FindAll();
        $etablissements =$em->getRepository('Pericles3Bundle:Etablissement')->findFirstCreated(5);
        return $this->render('BackOffice/Trash/etablissements_ref.html.twig', ['etablissements'=>$etablissements]);
    }
    
    
    
    
    
    /**
     * Lists all facture entities.
     *
     * @Route("/etablissement/{id}/delete", name="trash_etablissement_delete_oldref")
     * @Method("GET")
     */
    public function indexEtablissementDelOldRedAction(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->indexEtablissementDelOldRed($etablissement);
        return $this->redirectToRoute('trash_etablissement');
    }
    
    
    public function indexEtablissementDelOldRed(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $em = $this->getEm();
        $this->OutputOrFlash("supression OldReferentiel : ".$etablissement);
        foreach ($etablissement->getDomainesObsolete() as $oldDomaine)
        {
            $this->OutputOrFlash($oldDomaine->GetNumero().":".$oldDomaine.":".$oldDomaine." ---> ".$oldDomaine->GetReferentielPublic());
            foreach ($oldDomaine->getDimensions() as $oldDimension)
            {
                foreach ($oldDimension->getCriteres() as $oldCritere)
                {
                    foreach ($oldCritere->getQuestions() as $oldQuestion)
                    {
                        $em->remove($oldQuestion);
                        $em->flush();
                    }
                    $em->remove($oldCritere);
                    $em->flush();
                }
                
                $em->remove($oldDimension);
                $em->flush();
            }
            
            $em->remove($oldDomaine);
            $em->flush();
        } 
    }
        
        
    
    
    
    
      /**
     * Lists all facture entities.
     *
     * @Route("/domaines/trash", name="trash_domaines_trash")
     * @Method("GET")
     */
    public function indexTrashAction()
    {
        $em = $this->getDoctrine()->getManager();
        $domaines_desuet =$em->getRepository('Pericles3Bundle:Domaine')->FindReferentielDesuet();
        
        foreach ($domaines_desuet as $Domaine )
           {
            if ($Domaine->GetEtablissement()->getNbSauvegardesReferentielDesuet()==0)
            {
                   foreach ($Domaine->getDimensions() as $Dimension  )
                   {
                       foreach ($Dimension->getCriteres() as $Critere )
                       {
                           foreach ($Critere->GetQuestions() as $Question)
                           {
                                $em->remove($Question);
                           }
                           $em->flush();
                           $em->remove($Critere);
                       }
                       $em->flush();
                       $em->remove($Dimension);
                   }
                   $em->flush();
                   $em->remove($Domaine);
               }
            }
            $em->flush();
            $this->addFlash('success', "Les domaines / dimensions / critères ont bien été supprimés");
        
        
        
        
        $this->addFlash('success', "Supression de tous les domaines desuets");
        return $this->redirectToRoute('trash_domaines');

        
        
    }
    
    
    

}
