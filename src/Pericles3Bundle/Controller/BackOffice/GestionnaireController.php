<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Response;

use Pericles3Bundle\Entity\FinessGestionnaire;
use Pericles3Bundle\Entity\Gestionnaire;
use Pericles3Bundle\Entity\User;

use Pericles3Bundle\Form\GestionnaireType;

/**
 * Gestionnaire controller.
 *
 * @Route("/backoffice/gestionnaire")
 */
class GestionnaireController extends Controller
{
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/", name="backoffice_gestionnaire_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE')) 
        {
            $gestionnaire= $this->getUser()->GetGestionnaire();
             return $this->render('BackOffice/Gestionnaire/show.html.twig', array('gestionnaire' => $gestionnaire ));
        }
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) 
        {
            
            $em = $this->getDoctrine()->getManager();
            if ($this->GetUser()->GetCreai())
            {
                $gestionnaires = $em->getRepository('Pericles3Bundle:Gestionnaire')->findByCreai($this->GetUser()->GetCreai());
            }
            else
            {
                $gestionnaires = $em->getRepository('Pericles3Bundle:Gestionnaire')->findReels();
            }
            return $this->render('BackOffice/Gestionnaire/index.html.twig', array(
                'gestionnaires' => $gestionnaires,
            ));
        }
    }
    
      
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/active_new_fonct", name="backoffice_gestionnaire_active_nf")
     * @Method("GET")
     */
    public function indexActiveNFAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_MEGA_ADMIN')) 
        {
            $em = $this->getDoctrine()->getManager();
            $gestionnaires = $em->getRepository('Pericles3Bundle:Gestionnaire')->findReels();
            foreach ($gestionnaires as $gestionnaire )
            {
                
                if ($gestionnaire->getReferentielAJour())
                {
                    if ($gestionnaire->getNewFonctionnaliteGestionnaire())
                    {
                        $this->addFlash('error', "GEstionnaire : ".$gestionnaire);
                    }
                    else
                    {
                        $gestionnaire->setNewFonctionnaliteGestionnaire(true);
                        $em->persist($gestionnaire);
                        $this->addFlash('success', "GEstionnaire : ".$gestionnaire);
                    }
                }
                else
                {
                    $this->addFlash('error', "GEstionnaire : ".$gestionnaire);
                }

            }
             $em->flush();
             
            return $this->redirectToRoute('backoffice_gestionnaire_index');
        }
    }
    
    
    
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/all", name="backoffice_gestionnaire_index_all")
     * @Method("GET")
     */
    public function indexAllAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_SUPERVISOR')) 
        {
            $em = $this->getDoctrine()->getManager();
            $gestionnaires = $em->getRepository('Pericles3Bundle:Gestionnaire')->findAll();
            return $this->render('BackOffice/Gestionnaire/index.html.twig', array(
                'gestionnaires' => $gestionnaires,
            ));
        }
    }
    
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/tests", name="backoffice_gestionnaire_index_test")
     * @Method("GET")
     */
    public function indexTestAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_SUPERVISOR')) 
        {
            $em = $this->getDoctrine()->getManager();
            $gestionnaires = $em->getRepository('Pericles3Bundle:Gestionnaire')->findTest();
            return $this->render('BackOffice/Gestionnaire/index.html.twig', array(
                'gestionnaires' => $gestionnaires,
            ));
        }
    }
    
        
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/disabled", name="backoffice_gestionnaire_index_disabled")
     * @Method("GET")
     */
    public function indexDisabledAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_SUPERVISOR')) 
        {
            $em = $this->getDoctrine()->getManager();
            $gestionnaires = $em->getRepository('Pericles3Bundle:Gestionnaire')->findNonActive();
            return $this->render('BackOffice/Gestionnaire/index.html.twig', array(
                'gestionnaires' => $gestionnaires,
            ));
        }
    }
    
    
    
    
    

    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/sans_factures", name="backoffice_gestionnaire_sans_factures")
     * @Method("GET")
     */
    public function indexSansFacturesAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_COMPTA_VIEW')) 
        {
            $gestionnaires = $this->getDoctrine()->getRepository('Pericles3Bundle:Gestionnaire')->findSansFactureWarning();
            return $this->render('BackOffice/Gestionnaire/index.html.twig', array(
                'gestionnaires' => $gestionnaires,
            ));
        }
    }
    
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/sans_finess", name="backoffice_gestionnaire_sans_finess")
     * @Method("GET")
     */
    public function indexSansFinessAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) 
        {
            $gestionnaires = $this->getDoctrine()->getRepository('Pericles3Bundle:Gestionnaire')->findSansFiness();
            return $this->render('BackOffice/Gestionnaire/index.html.twig', array(
                'gestionnaires' => $gestionnaires,
            ));
        }
    }
    
    

    
     
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/{id}/linkfiness", name="backoffice_gestionnaire_linkfiness")
     * @Method("GET")
     */
    public function LinkFinessGestionnaireAction(Gestionnaire $Gestionnaire,Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_GESTIONNAIRE'))
        {
            return $this->render('BackOffice/Gestionnaire/link_finess.html.twig', ['gestionnaire' => $Gestionnaire]);
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/{id}/linkfiness/go_{codeFiness}", name="backoffice_gestionnaire_linkfiness_go")
     * @Method("GET")
     */
    public function LinkFinessGoGestionnaireAction(Gestionnaire $Gestionnaire, FinessGestionnaire $codeFiness )
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_GESTIONNAIRE'))
        {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', "Le finess a bien été lié");
            $Gestionnaire->setFiness($codeFiness);
            $codeFiness->setGestionnaire($Gestionnaire);
            $em->persist($Gestionnaire);
            $em->persist($codeFiness);
            $em->flush();
            return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $Gestionnaire->getId()));
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
    
    

    /**
     * Recherche
     *
     * @Route("/search", name="pericles3_gestionnaire_search")
     * @Method({"GET", "POST"})
    */
    public function SearchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $occurence=$request->get('occurence');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            if ($this->getUser()->getAllEtablissement())
            {
                $creai=null;
            }
            else
            {
                $creai=$this->getUser()->GetCreai();
            }
        }

        $gestionnaires=$em->getRepository('Pericles3Bundle:Gestionnaire')->FindByOccurence($occurence,$creai);

        
        return $this->render('BackOffice/Gestionnaire/search.html.twig', ['occurence'=>$occurence, 'gestionnaires'=>$gestionnaires ]);
    }
    

    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/search_form", name="pericles3_backoffice_formsearch_gestionnaire")
     * @Method("GET")
     */
    public function searchGestionnaireAction(Request $request)
    {
        $q = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('Pericles3Bundle:Gestionnaire')->findLike($q);

        return $this->render('BackOffice/Gestionnaire/search_result.html.twig', ['results' => $results]);
    }
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/search_get_{id}", name="pericles3_backoffice_formsearch_get_gestionnaire")
     * @Method("GET")
     */
    public function getGestionnaireAction($id = null)
    {
        $author = $this->getDoctrine()->getRepository('Pericles3Bundle:Gestionnaire')->find($id);
        return new Response($author->getNom());
    }
    
    
    
    
    /**
     * Creates a new Gestionnaire entity.
     *
     * @Route("/new", name="backoffice_gestionnaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $gestionnaire = new Gestionnaire();
        $form = $this->createForm('Pericles3Bundle\Form\GestionnaireType', $gestionnaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $gestionnaire->setCreatedBy($this->GetUser());
            $gestionnaire->setCreatedDate(new \DateTime());
            $gestionnaire->setNewFonctionnaliteGestionnaire(true);
            $em->persist($gestionnaire);
            $em->flush();
            return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $gestionnaire->getId()));
        }
        return $this->render('BackOffice/Gestionnaire/new.html.twig', array(
            'gestionnaire' => $gestionnaire,
            'form' => $form->createView(),
        ));
    }
    
    
    
    /**
     * Creates a new Gestionnaire entity.
     *
     * @Route("/new_by_finess/{codeFiness}", name="backoffice_gestionnaire_new_byfiness")
     * @Method({"GET", "POST"})
     */
    public function newByFinessAction(\Pericles3Bundle\Entity\FinessGestionnaire $FinessGestionnaire)
    {
        $em = $this->getDoctrine()->getManager();
        $gestionnaire = $this->GetGestionnaireByFiness($FinessGestionnaire);
        $gestionnaire->setCreatedBy($this->GetUser());
        $gestionnaire->setCreatedDate(new \DateTime());
        $gestionnaire->setNewFonctionnaliteGestionnaire(true);

        $em->persist($gestionnaire);
        $em->flush();
        return $this->redirectToRoute('backoffice_gestionnaire_edit', array('id' => $gestionnaire->getId()));
    }
    
    

    /**
     * Finds and displays a Gestionnaire entity.
     *
     * @Route("/{id}/suivi", name="backoffice_gestionnaire_show_suivi")
     * @Method("GET")
     */
    public function showSuiviAction(Gestionnaire $gestionnaire)
    {
        return $this->render('BackOffice/Gestionnaire/suivi.html.twig', array(
            'gestionnaire' => $gestionnaire 
        ));
    }
    
    
    /**
     * Finds and displays a Gestionnaire entity.
     *
     * @Route("/{id}/users_etab", name="backoffice_gestionnaire_show_users_etab")
     * @Method("GET")
     */
    public function showUserEtabAction(Gestionnaire $gestionnaire)
    {
        return $this->render('BackOffice/Gestionnaire/users_etab.html.twig', array(
            'gestionnaire' => $gestionnaire 
        ));
    }
    
    
    
    /**
     * Finds and displays a Gestionnaire entity.
     *
     * @Route("/{id}/findecontrat_etablissement", name="backoffice_gestionnaire_findecontrat_etablissement")
     * @Method("GET")
     */
    public function setfincontratEtablissementsAction(Gestionnaire $gestionnaire)
    {
        $em = $this->getDoctrine()->getManager();


        $fin_contrat=$em->getRepository('Pericles3Bundle:EtablissementCategory')->findOneById(6);
        $mode_cotisation=$em->getRepository('Pericles3Bundle:modeCotisation')->findOneById(13);
        
        
        
        foreach ($gestionnaire->getEtablissements() as $etablissement  )
        {
            $this->AddFlash("success",$etablissement. " : Fin de contrat");
            $etablissement->setModeCotisation($mode_cotisation);
            $etablissement->setCategory($fin_contrat);
            $em->persist($etablissement);
//            $etablissement->Set
        }
        
        //$em->persist($gestionnaire);
        $em->flush();
        $this->AddFlash("success","Les établissements ont bien été passé en non facturable");
        return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $gestionnaire->getId()));

    }
    

    /**
     * Finds and displays a Gestionnaire entity.
     *
     * @Route("/{id}", name="backoffice_gestionnaire_show")
     * @Method("GET")
     */
    public function showAction(Gestionnaire $gestionnaire)
    {
        return $this->render('BackOffice/Gestionnaire/show.html.twig', array(
            'gestionnaire' => $gestionnaire 
        ));
    }
    
    
    /**
     * Finds and displays a Gestionnaire entity.
     *
     * @Route("/{id}/patch", name="backoffice_gestionnaire_show_patch")
     * @Method("GET")
     */
    public function patchAction(Gestionnaire $gestionnaire)
    {
        return $this->render('BackOffice/Gestionnaire/patch.html.twig', array(
            'gestionnaire' => $gestionnaire 
        ));
    }
    
    
    
    
    

    /**
     * Displays a form to edit an existing Gestionnaire entity.
     *
     * @Route("/{id}/edit", name="backoffice_gestionnaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Gestionnaire $gestionnaire)
    {
        $deleteForm = $this->createDeleteForm($gestionnaire);
        
        $editForm = $this->createForm('Pericles3Bundle\Form\GestionnaireType', $gestionnaire,['mega_admin'=>$this->get('security.authorization_checker')->isGranted('ROLE_MEGA_ADMIN')]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            /*
            if ($editForm->get('finess_num')->getData())
            { 
                $this->AddFlash("success","DATA ");

                $n_finess=$em->getRepository('Pericles3Bundle:FinessGestionnaire')->findOneBycodeFiness($editForm->get('finess_num')->getData());
                $gestionnaire->setFiness($n_finess);
                $n_finess->SetGestionnaire($gestionnaire);
                $em->persist($n_finess);
            }
            */
            $em->persist($gestionnaire);
            $em->flush();
            $this->AddFlash("success","Le gestionnaire à bien été modifié");
            return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $gestionnaire->getId()));
        }

        return $this->render('BackOffice/Gestionnaire/edit.html.twig', array(
            'gestionnaire' => $gestionnaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Gestionnaire entity.
     *
     * @Route("/{id}", name="backoffice_gestionnaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Gestionnaire $gestionnaire)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createDeleteForm($gestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                  
            $demande= $gestionnaire->getDemandeGestionnaire();
            if ($demande)
            {
                $demande->setGestionnaire(null);
            }
            
            $finess= $gestionnaire->getFiness();
            if ($finess)
            {
                $finess->setGestionnaire(null);
                $gestionnaire->setFiness(null);
                $gestionnaire->setNom($gestionnaire->getNom()." - Supprimé");
                $em->persist($finess);
                $em->persist($gestionnaire);
                $em->flush();
            }  
         
            $em->remove($gestionnaire);
            $em->flush();
            $this->AddFlash("success","Le gestionnaire à bien été supprimé");
        }

        return $this->redirectToRoute('backoffice_gestionnaire_index');
    }

    
    /**
     * Deletes a Gestionnaire entity.
     *
     * @Route("/deleting_{id}", name="backoffice_gestionnaire_deleting")
     * @Method("GET")
     */
    public function deleteGestionnaireAction(Gestionnaire $gestionnaire)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($gestionnaire);
        $em->flush();
        $this->AddFlash("success","Le gestionnaire à bien été supprimé");
        return $this->redirectToRoute('backoffice_gestionnaire_index');
    }

    
      
    /**
     * Deletes a Gestionnaire entity.
     *
     * @Route("/delete/biblo_{id}", name="backoffice_gestionnaire_delette_biblio")
     * @Method("GET")
     */
    public function deleteBiblioAction(Gestionnaire $gestionnaire)
    {
        $em = $this->getDoctrine()->getManager();
         
        
        foreach ($gestionnaire->getBibliotheques() as $biblio)
        {
            $this->AddFlash("success","Biblio ".$biblio." supprimé");
            
            foreach ($biblio->GetPreuves() as $preuve)
            {
                $this->AddFlash("success","Preuve ".$preuve." supprimé");
                $em->remove($preuve);
                $em->flush();
            }
            $em->remove($biblio);
            $em->flush();
        }
        $this->AddFlash("success","Le gestionnaire à bien été supprimé");
        return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $gestionnaire->getId()));
    }

    
    /**
     * Creates a form to delete a Gestionnaire entity.
     *
     * @param Gestionnaire $gestionnaire The Gestionnaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Gestionnaire $gestionnaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_gestionnaire_delete', array('id' => $gestionnaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
    
    
    

    
    
    function GetGestionnaireByFiness(\Pericles3Bundle\Entity\FinessGestionnaire $FinessGestionnaire)
    {
       $gestionnaire = new Gestionnaire();
       $em = $this->getDoctrine()->getManager();
       $gestionnaire->setNom($FinessGestionnaire->GetRaisonSociale());
       $gestionnaire->setAdresse($FinessGestionnaire->GetAdresse());
       $gestionnaire->setFiness($FinessGestionnaire);
       $gestionnaire->setCreai($FinessGestionnaire->getDepartement()->GetCreai());
       $gestionnaire->setCodePostal($FinessGestionnaire->getCodePostal());
       $gestionnaire->setVille($FinessGestionnaire->getVille());
       $gestionnaire->setTel($FinessGestionnaire->getTel());
       $gestionnaire->setStockageGestionnaire($em->getRepository('Pericles3Bundle:StockageGestionnaire')->findOneById(0));
       return($gestionnaire);
    }
                   
    
    
    
    
    
}
