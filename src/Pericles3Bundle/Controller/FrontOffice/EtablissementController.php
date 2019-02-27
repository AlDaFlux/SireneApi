<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Pericles3Bundle\Entity\Etablissement;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;
use Symfony\Component\Validator\Constraints\Regex as RegexConstraint;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * Gestionnaire controller.
 *
 * @Route("/etablissement")
 */
class EtablissementController extends Controller
{
    
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/", name="pericles3_etablissement")
     * @Method("GET")
     */
    public function indexAction()
    {
            $etablissement = $this->getUser()->getEtablissement();
            return $this->render('Etablissement/index.html.twig', array('etablissement' => $etablissement));
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/etablissement_{id}", name="pericles3_etablissement_byid")
     * @Method("GET")
     */
    public function indexEtablissementAction(Etablissement $etablissement)
    {
            return $this->render('Etablissement/index.html.twig', array('etablissement' => $etablissement));
    }
    
	
     
    /**
     * Lists all etablissements entities.
     *
     * @Route("/etablissement_{id}/update", name="pericles3_update_etablissement_byid")
     * @Method({"GET", "POST"})
     */
    public function updateEtablissementByidAction(Etablissement $etablissement,Request $request)
    {
        if ($request->getMethod() == 'POST') {
                $this->updateEtab($etablissement,$request);
                return $this->redirectToRoute("pericles3_etablissement_byid",['id' =>$etablissement->getId()]);
        }
        return $this->render('Etablissement/update.html.twig', array('etablissement' => $etablissement));
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/update", name="pericles3_etablissement_update")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request)
    {
        if ($this->getUser())
        {
		$etablissement = $this->getUser()->getEtablissement();
		if ($request->getMethod() == 'POST') {
                    	$this->updateEtab($etablissement,$request);
                        return $this->redirectToRoute("pericles3_etablissement");
                }
		return $this->render('Etablissement/update.html.twig', array('etablissement' => $etablissement));
        }
        else
        {
                throw $this->createAccessDeniedException("Vous n'êtes pas connecté");
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
     
    /**
     * Lists all etablissements entities.
     *
     * @Route("/etablissement_{id}/princip/update", name="pericles3_update_etablissement_princip_byid")
     * @Method({"GET", "POST"})
     */
    public function updatePrincipeEtablissementByidAction(Etablissement $etablissement,Request $request)
    {
 
        if ($request->getMethod() == 'POST') {
                $etablissement->SetPrincipContractualisations($request->get('princip_contractualisations'));
                $etablissement->SetPrincipValeurs($request->get('princip_valeurs'));
                $etablissement->SetPrincipObjectifs($request->get('princip_objectifs'));
                $etablissement->SetPrincipCaractéristiques($request->get('princip_caracteristiques'));
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($etablissement);
                $em->flush();
                $this->addFlash('success', 'Les modifications ont été prises en compte');
                return $this->redirectToRoute("pericles3_etablissement_byid",['id' =>$etablissement->getId()]);
        }
        return $this->render('Etablissement/update_princip.html.twig', array('etablissement' => $etablissement));
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/update/princip", name="pericles3_etablissement_princip_update")
     * @Method({"GET", "POST"})
     */
    public function updatePrincipeAction(Request $request)
    {
        $etablissement = $this->getUser()->getEtablissement();
        if ($request->getMethod() == 'POST') {
                $etablissement->SetPrincipContractualisations($request->get('princip_contractualisations'));
                $etablissement->SetPrincipValeurs($request->get('princip_valeurs'));
                $etablissement->SetPrincipObjectifs($request->get('princip_objectifs'));
                $etablissement->SetPrincipCaractéristiques($request->get('princip_caracteristiques'));
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($etablissement);
                $em->flush();
                $this->addFlash('success', 'Les modifications ont été prises en compte');
                return $this->redirectToRoute("pericles3_etablissement");
        }   
        return $this->render('Etablissement/update_princip.html.twig', array('etablissement' => $etablissement));
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function updateEtab(Etablissement $etablissement,Request $request)
    {
		if ($request->getMethod() == 'POST') {
			$id = $etablissement->GetId();

                        $dateDernierProjet = $request->get('dateDernierProjet');
			$dateDerniereEvaluationInterne = $request->get('dateDerniereEvaluationInterne');
			$dateDerniereEvaluationExterne = $request->get('dateDerniereEvaluationExterne');
			
                        
                        $etp=$request->get('nombreEtb');
                        $etp=  str_replace(',',".",$etp);

				try {
					$etablissement->setCapaciteAcceuil($request->get('capaciteAcceuil'));
					$etablissement->setHebergementComplet($request->get('hebergementComplet'));
					$etablissement->setAcceuilJour($request->get('acceuilJour'));
					$etablissement->setAccueilTemporaire($request->get('accueilTemporaire'));

                                        $etablissement->setnombreEtb($etp);

                                        $etablissement->setDirecteur($request->get('directeur'));
                                        
                                        
                                        if ($dateDernierProjet == '') $etablissement->setDateDernierProjet($dateDernierProjet);
        		    		else $etablissement->setDateDernierProjet(new \DateTime($dateDernierProjet));
                          
                                        if ($dateDerniereEvaluationInterne == '') $etablissement->setDateDerniereEvaluationInterne($dateDerniereEvaluationInterne);
                                        else $etablissement->setDateDerniereEvaluationInterne(new \DateTime($dateDerniereEvaluationInterne));

                                        if ($dateDerniereEvaluationExterne == '') $etablissement->setDateDerniereEvaluationExterne($dateDerniereEvaluationExterne);
                                        else $etablissement->setDateDerniereEvaluationExterne(new \DateTime($dateDerniereEvaluationExterne));
                                

					$em = $this->getDoctrine()->getManager();
					$em->persist($etablissement);
					$em->flush();
					$this->addFlash('success', 'L\'établissement a bien été modifié');
					return true;
				} catch (\Exception $e) {
					$this->addFlash('error', 'Une erreur inattendue est survenue lors de la modification de l\'établissement');
				}
			
		}
		
    }


    
        
        
    /**
     * Lists all etablissements entities.
     *
     * @Route("/etablissement_{id}/addlogo", name="arsene_etablissement_upload_logo")
     * @Method({"GET", "POST"})
     */
    public function addLogoAction(Etablissement $Etablissement,Request $request)
    {
        $uploadPath = WEB_DIR.'/upload';
        $UploadFolderPath= $Etablissement->GetUploadFolderPath();
        $UploadDirectory = $UploadFolderPath."";
       
        if (!isset($_FILES["FileInput"]))
        {     
            $this->addFlash('error', "Vous devez selectionner un fichier");
            return $this->redirectToRoute("pericles3_etablissement_byid",['id' =>$Etablissement->getId()]);
        }
        $File_Name=""; 
        
        if (isset($_FILES["FileInput"]))
        {
            if($_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
            {
                    if (! $this->get('Utils')->FolderUploadExisteCreate($UploadDirectory))
                    {
                        $this->addFlash('error', "Une erreur est survenue lors de la création du dossier");
                        return $this->redirectToRoute("pericles3_etablissement_byid",['id' =>$Etablissement->getId()]);
                    }
                    if ($_FILES["FileInput"]["size"] > $this->container->getParameter('upload.max_file_size')) { die(self::getBootstrapAlert('Erreur!',"Le Fichier est trop gros !!", 'alert-danger'));}
                    
                    if (! in_array(strtolower($_FILES['FileInput']['type']),$this->container->getParameter('upload.filetype'))) 
                    {
                        $this->addFlash('error', "Format de fichier <b>" . $_FILES['FileInput']['type']. "</b> non supporté <br><b>Rappel : </b> les fichiers supportés sont : ".implode(' - ',$this->container->getParameter('upload.extensions')));
                        return $this->redirectToRoute("pericles3_etablissement_byid",['id' =>$Etablissement->getId()]);
                    }

                    $File_Name= strtolower($_FILES['FileInput']['name']);

                    $dest_file=$uploadPath."/".$UploadDirectory."/".$File_Name;
                    if (file_exists($dest_file))
                    {
                        $this->addFlash('success', "Le fichier existait déjà");
                    }
                    else
                    {
                        if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $dest_file))
                        {
                            $this->addFlash('success', "Le fichier a été envoyé!");
                        }
                        else
                        {
                        $this->addFlash('error', "Erreur lors de l'envoi du fichier!!");
                        }
                    }
                }        
        }
        $Etablissement->setLogoFichierName($File_Name);
        $em = $this->getDoctrine()->getManager();
        $em->persist($Etablissement);
        $em->flush();
        $this->addFlash('Success', "Le logo à bien été rajouter");
        return $this->redirectToRoute("pericles3_etablissement_byid",['id' =>$Etablissement->getId()]);
    }
        

    /**
     * Supprime une preuve
     *
     * @Route("/etablissement_{id}/deletelogo", name="arsene_etablissement_delete_logo")
     * @Method({"GET", "POST"})
     */
    public function deleteLogoAction(Etablissement $Etablissement,Request $request)
    {
        $fichier_image=WEB_DIR.$Etablissement->getLogoPath();
        if (file_exists($fichier_image))
            unlink($fichier_image);
        $this->addFlash('Success', "Le logo à bien été supprimmé");
        
        
        $Etablissement->setLogoFichierName(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($Etablissement);
        $em->flush();                
                
        return $this->redirectToRoute("pericles3_etablissement_byid",['id' =>$Etablissement->getId()]);
    }
        
        
        
	 
         
}