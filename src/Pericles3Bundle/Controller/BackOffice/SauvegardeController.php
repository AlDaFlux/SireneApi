<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Pericles3Bundle\Entity\Sauvegarde;
use Pericles3Bundle\Entity\Patch;

use Pericles3Bundle\Entity\SauvegardeCritere;
use Pericles3Bundle\Entity\SauvegardeDimension;
use Pericles3Bundle\Entity\SauvegardeDomaine;
use Pericles3Bundle\Entity\SauvegardeQuestion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Sauvegarde controller.
 *
 * @Route("backoffice/sauvegarde")
 */
class SauvegardeController extends AdminController
{
    /**
     * Lists all sauvegarde entities.
     *
     * @Route("/", name="backoffice_sauvegarde_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sauvegardes = $em->getRepository('Pericles3Bundle:Sauvegarde')->findAll();

        return $this->render('BackOffice/sauvegarde/index.html.twig', array(
            'sauvegardes' => $sauvegardes,
        ));
    }
    
     /**
     * Lists all sauvegarde entities.
     *
     * @Route("/obsolete", name="backoffice_sauvegarde_obsolete")
     * @Method("GET")
     */
    public function indexObsoleteAction()
    {
        $em = $this->GetEm();

        $sauvegardes= new \Doctrine\Common\Collections\ArrayCollection(); 
        foreach ($em->getRepository('Pericles3Bundle:Sauvegarde')->findAll() as $sauvegarde )
        {
            if ($sauvegarde->getReferentielDesuet()) $sauvegardes->add($sauvegarde);
        }

        return $this->render('BackOffice/sauvegarde/index.html.twig', array(
            'sauvegardes' => $sauvegardes,
        ));
    }


    /**
     * Finds and displays a sauvegarde entity.
     *
     * @Route("/{id}", name="backoffice_sauvegarde_show")
     * @Method("GET")
     */
    public function showAction(Sauvegarde $sauvegarde)
    {

        return $this->render('BackOffice/sauvegarde/show.html.twig', array(
            'sauvegarde' => $sauvegarde,
        ));
    }
    
    /**
     * Finds and displays a sauvegarde entity.
     *
     * @Route("/{id}/patch", name="backoffice_sauvegarde_patch")
     * @Method("GET")
     */
    public function patchAction(Sauvegarde $sauvegarde)
    {
        $this->patchSauvegarde($sauvegarde);
        return $this->redirectToRoute('backoffice_sauvegarde_show', array('id' => $sauvegarde->getId()));
    }
    

    public function patchSauvegarde(Sauvegarde $sauvegarde)
    {
        $etablissement=$sauvegarde->getEtablissement();
        $referentielPublicSauvegarde=$sauvegarde->getReferentiel();
        $referentielPublicEtablissement=$etablissement->getReferentielPublic();
        
        $this->addFlash("error","Etab : ".$etablissement);
        foreach ($referentielPublicSauvegarde->getPatchSources() as $patch)
        {
            
            if ($patch->GetCible()->GetId()==$referentielPublicEtablissement->getId())
            {
                $patchToApply=$patch;
            }
        }
        if ($patchToApply ) $this->patchSauvegardeApply($sauvegarde,$patch);
        else $this->addFlash("error","APs de patch : ".$patch);
    }
    
    
    
    public function deleteSauvegarde(Sauvegarde $Sauvegarde)
    {
        $em = $this->GetEm();
        $this->OutputOrFlash("Supression de >".$Sauvegarde);
        $em->remove($Sauvegarde);
        $em->flush();            
        
//        $etablissement->removeSauvegarde($Sauvegarde);
        
        /*
        foreach ($Sauvegarde->getDomaines() as $Domaine) 
        {
            $em->remove($Domaine);
            $em->flush();            
            
           
            $Sauvegarde->removeDomaine($Domaine);
            $em->persist($Sauvegarde);
            $em->flush();            
            
            
                    /*
            foreach ($Domaine->getDimensions() as $Dimension) 
            {
                $this->OutputOrFlash("----->".$Dimension);
                foreach ($Dimension->getCriteres() as $Critere) 
                {
                    foreach ($Critere->getQuestions() as $Question) 
                    {
                        $em->remove($Question);
                        $em->flush();
                    }
                    $em->remove($Critere);
                    $em->flush();
                }
                $em->remove($Dimension);
                $em->flush();
            }
            
        }
        /*
        $etablissement=$Sauvegarde->getEtablissement();
        $etablissement->removeSauvegarde($Sauvegarde);
        $em->persist($etablissement);
        $em->flush();
         * 
         */

    }
    
    
    public function patchSauvegardeApply(Sauvegarde $oldSauvegarde,Patch $patch )
    {
        $em = $this->GetEm();
 
        $etablissement=$oldSauvegarde->getEtablissement();
//        $referentielPublicSauvegarde=$oldSauvegarde->getReferentiel();
        $referentielPublicEtablissement=$etablissement->getReferentielPublic();
        
        $newSauvegarde = new Sauvegarde;
        
        $newSauvegarde->setDateCreate($oldSauvegarde->getDateCreate());
        $newSauvegarde->setEtablissement($etablissement);
        $newSauvegarde->setNom($oldSauvegarde->getNom());
        $newSauvegarde->setNote($oldSauvegarde->getNote());
        $newSauvegarde->setUser($oldSauvegarde->getUser());
        
        $em->persist($newSauvegarde);
        $em->flush();   
        
            $Domaines = $referentielPublicEtablissement->getReferentielDomaines();
            foreach ($Domaines as $Domaine) {
                
                
    			$SauvegardeDomaine = new SauvegardeDomaine();
                        $SauvegardeDomaine->setReferentiel($Domaine);
                        
                        $oldBackup=null;
                        $patchRefSource=$patch->getPatcheRefPublicByRefCible($Domaine);
                        if ($patchRefSource) { if ($patchRefSource->GetSource())  $oldBackup=$patchRefSource->GetSource()->getSauvegardeDomaineBySauvegarde($oldSauvegarde); }
                        
                        if ($oldBackup)
                        {
                            $this->OutputOrFlashSuccess("->".$Domaine);
                            $SauvegardeDomaine->setNote($oldBackup->getNote());
                        }
                        else
                        {
                            $this->OutputOrFlashError("->".$Domaine);
                            $SauvegardeDomaine->setNote(-3);
                        }

                        
                        $SauvegardeDomaine->SetSauvegarde($newSauvegarde);
                        $SauvegardeDomaine->SetDomaineOriginal($Domaine->getDomaineEtablissement($etablissement));
    			$em->persist($SauvegardeDomaine);
                        $em->flush();   
                        foreach ($Domaine->GetChildren() as $Dimension) 
                        {
                            $SauvegardeDimension = new SauvegardeDimension();
                            $SauvegardeDimension->setReferentiel($Dimension);

                            $oldBackup=null;
                            $patchRefSource=$patch->getPatcheRefPublicByRefCible($Dimension);
                            if ($patchRefSource) 
                            { 
                                if ($patchRefSource->GetSource()) $oldBackup=$patchRefSource->GetSource()->getSauvegardeDimensionBySauvegarde($oldSauvegarde); 
                                
                            }

                            
                            if ($oldBackup)
                            {
                                $SauvegardeDimension->setNote($oldBackup->getNote());
                                $this->OutputOrFlashSuccess("--->".$Dimension);
                            }
                            else
                            {
                                $this->OutputOrFlashError("--->".$Dimension);
                                $SauvegardeDimension->setNote(-3);
                            }
                            
                            
                            
                            $SauvegardeDimension->SetDomaine($SauvegardeDomaine);
                            $SauvegardeDimension->SetDimensionOriginal($Dimension->getDimensionEtablissement($etablissement));

                            $em->persist($SauvegardeDimension);
                            $em->flush();
                            foreach ($Dimension->GetChildren() as $Critere) 
                            {
                                $SauvegardeCritere= new SauvegardeCritere();
                                $SauvegardeCritere->setReferentiel($Critere);

                                $oldBackup=null;
                                $patchRefSource=$patch->getPatcheRefPublicByRefCible($Critere);
                                if ($patchRefSource) 
                                {
                                    if ($patchRefSource->GetSource()) $oldBackup=$patchRefSource->GetSource()->getSauvegardeCritereBySauvegarde($oldSauvegarde); 
                                }
                                
                                
                                if ($oldBackup)
                                {
                                    $this->OutputOrFlashSuccess("----->".$Critere);
                                    $SauvegardeCritere->setNote($oldBackup->getNote());
                                }
                                else
                                {
                                    $this->OutputOrFlashError("----->".$Critere);
                                    $SauvegardeCritere->setNote(-3);
                                }


                            $SauvegardeCritere->SetDimension($SauvegardeDimension);
                                $SauvegardeCritere->SetCritereOriginal($Critere->getCritereEtablissement($etablissement));
                                $em->persist($SauvegardeCritere);
                                $em->flush();   
                                foreach ($Critere->GetChildren() as $Question) 
                                {
                                    
                                        $SauvegardeQuestion= new SauvegardeQuestion();
                                        $SauvegardeQuestion->setReferentiel($Question);
                                        
                                        $oldBackup=null;
                                        $patchRefSource=$patch->getPatcheRefPublicByRefCible($Question);
                                        if ($patchRefSource) 
                                        { 
                                            if ($patchRefSource->GetSource()) $oldBackup=$patchRefSource->GetSource()->getSauvegardeQuestionBySauvegarde($oldSauvegarde); 
                                        }
                                        
                                        if ($oldBackup)
                                        {
                                            $SauvegardeQuestion->setReponse($oldBackup->getReponse());
                                            $this->OutputOrFlashSuccess("------->".$Question);
                                        }
                                        else
                                        {
                                            $this->OutputOrFlashError("------->".$Question);
                                            $SauvegardeQuestion->setReponse(-3);
                                        }

                                        $SauvegardeQuestion->SetCritere($SauvegardeCritere);
                                        $SauvegardeQuestion->SetQuestionOriginal($Question->getQuestionEtablissement($etablissement));
                                        $em->persist($SauvegardeQuestion);
                                        $em->flush();   
                                }
                           }
                       }
                        
           }

//*           $oldSauvegarde->setToDelete(true);
           $this->OutputOrFlash("----- Supression de la sauvegarde !!! -----");
           $em->remove($oldSauvegarde);
           $em->flush();            
    }

    
    
    
    
    
    
    
    
    
    
}
