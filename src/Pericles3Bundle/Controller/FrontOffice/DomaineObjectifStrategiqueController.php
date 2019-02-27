<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Pericles3Bundle\Entity\DomaineObjectifStrategique;
use Pericles3Bundle\Entity\Domaine;
use Pericles3Bundle\Entity\Etablissement;

use Pericles3Bundle\Form\DomaineObjectifStrategiqueType;


/**
 * DomaineObjectifStrategique controller.
 *
 * @Route("/paq/osa")
 */
class DomaineObjectifStrategiqueController extends Controller
{
        
    private function getRepository()
    {
       return( $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineObjectifStrategique'));
    }
    
    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/", name="paq_osa_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");

        $em = $this->getDoctrine()->getManager();
        $domaineObjectifStrategiques = $em->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->findAll();

        return $this->render('domaineobjectifstrategique/index.html.twig', array(
            'domaineObjectifStrategiques' => $domaineObjectifStrategiques,
        ));
    }

    
        
     /**
     * Liste les DomaineObjectifStrategique entity.
     *
     * @Route("/list", name="paq_osa_list")
     * @Method({"GET", "POST"})
     */
    public function listeAction()
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        $etablissement = $this->getUser()->getEtablissement();
        if ($etablissement)
        {
            $ObjectifsStrategiques = $this->getRepository()->findByEtablissement($etablissement);
        }
        else
        {
            $ObjectifsStrategiques = $this->getRepository()->findByGestionnaire($this->getUser());
        }
        $sous_titre="";  
        return $this->render('ObjectifsAmelioration/Strategiques/liste.html.twig', array('ObjectifsStrategiques' => $ObjectifsStrategiques,"sous_titre" => $sous_titre));
    }
    
            
     /**
     * Liste les DomaineObjectifStrategique entity.
     *
     * @Route("/list/etablissement_{id}", name="paq_osa_list_etablissement")
     * @Method({"GET", "POST"})
     */
    public function listeEtablissementAction(Etablissement $etablissement)
    {
        $ObjectifsStrategiques = $this->getRepository()->findByEtablissement($etablissement);
        return $this->render('ObjectifsAmelioration/Strategiques/liste.html.twig', 
                array('ObjectifsStrategiques' => $ObjectifsStrategiques,"sous_titre" => "","etablissement"=>$etablissement));
    }
    
    
     /**
     * Liste les DomaineObjectifStrategique entity.
     *
     * @Route("/list/domaine_{id}", name="paq_osa_list_filtre_domaine")
     * @Method({"GET", "POST"})
     */
    public function listeDomaineAction(Domaine $domaine)
    {
        

        if ($this->getUser())
        {
            $etablissement = $this->getUser()->getEtablissement();
            $sous_titre=$domaine->GetNom();  
            $ObjectifsStrategiques = $this->getRepository()->findDomaine($domaine->GetId());
            return $this->render('ObjectifsAmelioration/Strategiques/liste.html.twig', 
                    array('ObjectifsStrategiques' => $ObjectifsStrategiques,"sous_titre" => $sous_titre,'domaine'=>$domaine));
        }
        else
        {
              throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
        
    }
    
     /**
     * Liste les DomaineObjectifStrategique entity.
     *
     * @Route("/list/{statut}", name="paq_osa_list_filtre_statut")
     * @Method({"GET", "POST"})
     */
    public function listeSattutAction($statut='')
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        $etablissement = $this->getUser()->getEtablissement();
        if ($etablissement)
        {
                switch ($statut) {
                    case 'encours':
                        $sous_titre=" En cours";  
                        $ObjectifsStrategiques = $this->getRepository()->findStatut($etablissement,1);
                        break;
                    case 'importantes':
                        $ObjectifsStrategiques = $this->getRepository()->findStatut($etablissement,2);
                        $sous_titre=" Importants";  
                        break;
                    case 'finies':
                        $ObjectifsStrategiques = $this->getRepository()->findStatut($etablissement,3);
                        $sous_titre=" Finis";  
                        break;
                     default:
                        $ObjectifsStrategiques = $this->getRepository()->findByEtablissement($etablissement);
                        $sous_titre="";  
                }
        }
        else {
            switch ($statut) {
                case 'encours':
                    $sous_titre=" En cours";  
                    $ObjectifsStrategiques = $this->getRepository()->findStatutGestionnaire($this->getUser(),1);
                    break;
                case 'importantes':
                    $ObjectifsStrategiques = $this->getRepository()->findStatutGestionnaire($this->getUser(),2);
                    $sous_titre=" Importants";  
                    break;
                case 'finies':
                    $ObjectifsStrategiques = $this->getRepository()->findStatutGestionnaire($this->getUser(),3);
                    $sous_titre=" Finis";  
                    break;
            }

        }  
        
        
        
        
        return $this->render('ObjectifsAmelioration/Strategiques/liste.html.twig', array('ObjectifsStrategiques' => $ObjectifsStrategiques,"sous_titre" => $sous_titre));
    }
    

     /**
     * Liste les DomaineObjectifStrategique entity.
     *
     * @Route("/list/etablissement_{id}/{statut}", name="paq_osa_list_etablissement_filtre_statut")
     * @Method({"GET", "POST"})
     */
    public function listeEtablissementSatutAction(Etablissement $etablissement ,$statut='')
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        switch ($statut) 
        {
                    case 'encours':
                        $sous_titre=" En cours";  
                        $ObjectifsStrategiques = $this->getRepository()->findStatut($etablissement,1);
                        break;
                    case 'importantes':
                        $ObjectifsStrategiques = $this->getRepository()->findStatut($etablissement,2);
                        $sous_titre=" Importants";  
                        break;
                    case 'finies':
                        $ObjectifsStrategiques = $this->getRepository()->findStatut($etablissement,3);
                        $sous_titre=" Finis";  
                        break;
        } 
        return $this->render('ObjectifsAmelioration/Strategiques/liste.html.twig', array('ObjectifsStrategiques' => $ObjectifsStrategiques,"sous_titre" => $sous_titre,"etablissement"=>$etablissement));
    }
    
                        
     
    /**
     * Creates a new DomaineObjectifStrategique entity.
     *
     * @Route("/new/etablissement_{id}", name="paq_osa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Etablissement $Etablissement, Request $request)
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        $domaineObjectifStrategique = new DomaineObjectifStrategique();
        $form = $this->createForm('Pericles3Bundle\Form\DomaineObjectifStrategiqueType', $domaineObjectifStrategique,['etablissement_id'=>$Etablissement->GetId()]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $domaineObjectifStrategique->setUser($this->getUser());
            $domaineObjectifStrategique->setDateCreate(new \DateTime(date("Y-m-d H:i:s")));
            $domaineObjectifStrategique->setEtablissement( $Etablissement);
            $em->persist($domaineObjectifStrategique);
            $em->flush();
            $this->addFlash('success', "L'objectif à bien été créer");
            
            if ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE'))
            {
                return $this->redirectToRoute('paq_osa_list_etablissement',['id'=>$Etablissement->getId()]);
            }
            else
            {
                return $this->redirectToRoute('paq_osa_list');
            }
                    
            
        }
        
        return $this->render('ObjectifsAmelioration/Strategiques/new.html.twig', array(
            'domaineObjectifStrategique' => $domaineObjectifStrategique,
            'etablissement' => $Etablissement,
            'form' => $form->createView(),
        ));
    }
               
    /**
     * Creates a new DomaineObjectifStrategique entity.
     *
     * @Route("/new/domaine_{id}", name="paq_osa_new_domaine")
     * @Method({"GET", "POST"})
     */
    public function newDomaineAction(Domaine $Domaine,Request $request)
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");

        $Etablissement=$Domaine->getEtablissement();
                
        $domaineObjectifStrategique = new DomaineObjectifStrategique();
        $form = $this->createForm('Pericles3Bundle\Form\DomaineObjectifStrategiqueType', $domaineObjectifStrategique,['domaine_defined'=> true,  'etablissement_id'=>$Etablissement->GetId()]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $domaineObjectifStrategique->setDomaine($Domaine);
            $domaineObjectifStrategique->setUser($this->getUser());
            $domaineObjectifStrategique->setDateCreate(new \DateTime(date("Y-m-d H:i:s")));
            $domaineObjectifStrategique->setEtablissement($Etablissement);
            $em->persist($domaineObjectifStrategique);
            $em->flush();
            $this->addFlash('success', "L'objectif à bien été créer");
            return $this->redirectToRoute('paq_osa_list_filtre_domaine', array('id' => $Domaine->getId()));
        }
        
        return $this->render('ObjectifsAmelioration/Strategiques/new.html.twig', array(
            'domaineObjectifStrategique' => $domaineObjectifStrategique,
            'domaine' => $Domaine,
            'form' => $form->createView(),
        ));
    }
            
    
    
    
    
    
    
    
    
    
    /**
     * Finds and displays a DomaineObjectifStrategique entity.
     *
     * @Route("/{id}", name="paq_osa_show")
     * @Method("GET")
     */
    public function showAction(DomaineObjectifStrategique $domaineObjectifStrategique)
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");

        
        return $this->render('ObjectifsAmelioration/Strategiques/show.html.twig', array('ObjectifStrategique' => $domaineObjectifStrategique));
    }

    /**
     * Displays a form to edit an existing DomaineObjectifStrategique entity.
     *
     * @Route("/{id}/edit", name="paq_osa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DomaineObjectifStrategique $domaineObjectifStrategique)
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");

        $deleteForm = $this->createDeleteForm($domaineObjectifStrategique);
        $editForm = $this->createForm('Pericles3Bundle\Form\DomaineObjectifStrategiqueType', $domaineObjectifStrategique,
                ['etablissement_id'=>$domaineObjectifStrategique->getEtablissement()->GetId()]);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($domaineObjectifStrategique);
            $em->flush();
            $this->addFlash('success', "L'objectif à bien été mis à jour");

            return $this->redirectToRoute('paq_osa_show', array('id' => $domaineObjectifStrategique->getId()));
        }
//      return $this->render('ObjectifsAmelioration/Strategiques/show.html.twig', array('ObjectifStrategique' => $domaineObjectifStrategique));
        return $this->render('ObjectifsAmelioration/Strategiques/edit.html.twig', array(
            'domaineObjectifStrategique' => $domaineObjectifStrategique,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
            

    /**
     * Deletes a DomaineObjectifStrategique entity.
     *
     * @Route("/delete/{id}", name="paq_osa_delete_get")
     * @Method({"GET", "POST"})
     */
    public function deleteGetAction(DomaineObjectifStrategique $domaineObjectifStrategique)
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");

        $em = $this->getDoctrine()->getManager();
        $em->remove($domaineObjectifStrategique );
        $em->flush();
        $this->addFlash('success', "L'objectif Stratégique à bien été supprimé.");
        return $this->redirectToRoute('paq_osa_list');
    }


    
    
    /**
     * Deletes a DomaineObjectifStrategique entity.
     *
     * @Route("/{id}", name="paq_osa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DomaineObjectifStrategique $domaineObjectifStrategique)
    {
        if ( ! $this->getUser())   throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");

        $form = $this->createDeleteForm($domaineObjectifStrategique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($domaineObjectifStrategique);
            $em->flush();
        }

        return $this->redirectToRoute('paq_osa_index');
    }

    /**
     * Creates a form to delete a DomaineObjectifStrategique entity.
     *
     * @param DomaineObjectifStrategique $domaineObjectifStrategique The DomaineObjectifStrategique entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DomaineObjectifStrategique $domaineObjectifStrategique)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('paq_osa_delete', array('id' => $domaineObjectifStrategique->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
            
    
    
    
    
}
