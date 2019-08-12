<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Pericles3Bundle\Entity\Finess;
use Pericles3Bundle\Entity\Departement;

use Pericles3Bundle\Form\FinessType;

/**
 * Finess controller.
 *
 * @Route("/backoffice/finess")
 */
class FinessController extends Controller
{
    /**
     * Lists all Finess entities.
     *
     * @Route("/", name="backoffice_finess_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->getUser()->IsCreai())
        {
            $departements=$em->getRepository('Pericles3Bundle:Departement')->findByCreai($this->getUser()->GetCreai());
        }
        else 
        {
            $departements=$em->getRepository('Pericles3Bundle:Departement')->FindAll();
        }
        return $this->render('BackOffice/finess/index.html.twig', array(
            'departements'=>$departements
        ));
    }
    
    
    
    
    
    /**
     * Lists all Finess entities.
     *
     * @Route("/departement_{id}", name="backoffice_finess_departement")
     * @Method("GET")
     */
    public function indexDepartementAction(Departement  $Departement)
    {
        return $this->render('BackOffice/finess/finnes_par_dep.html.twig', array(
            'departement'=>$Departement
        ));
    }
     
    
    
    
    
    
    
    
    /**
     * Lists all Finess entities.
     *
     * @Route("/import", name="backoffice_finess_import")
     * @Method("GET")
     */
    public function indexImportAction()
    {
        $em = $this->getDoctrine()->getManager();
       
        
        $nouveaux=$em->getRepository('Pericles3Bundle:FinessImport')->findNouveaux();
        $deleted=$em->getRepository('Pericles3Bundle:Finess')->findSupprimerDansImport();
        $communs=$em->getRepository('Pericles3Bundle:FinessImport')->findCommuns();
        $deletedWithEtab=$em->getRepository('Pericles3Bundle:Finess')->findSupprimerDansImportAvecEtablissement();
        
        
        /*
        $nouveaux=null;
        $deleted=null;
        $communs=null;
        $deletedWithEtab=null;
        
        $nouveauxGestionnaire=null;
        $deletedGestionnaire=null;
        $communsGestionnaire=null;
        $deletedWithGestionnaire=null;
        */

        
        $nouveauxGestionnaire=$em->getRepository('Pericles3Bundle:FinessGestionnaireImport')->findNouveaux();
        $communsGestionnaire=$em->getRepository('Pericles3Bundle:FinessGestionnaireImport')->findCommuns();
        $deletedWithGestionnaire=$em->getRepository('Pericles3Bundle:FinessGestionnaire')->findSupprimerDansImportAvecGestionnaire();
        $deletedGestionnaire=$em->getRepository('Pericles3Bundle:FinessGestionnaire')->findSupprimerDansImport();

                
        /*
      
        $code="finess-extraction-des-entites-juridiques";
        //$code="finess-extraction-des-equipements-sociaux-et-medico-sociaux";
        $code="finess-extraction-du-fichier-des-etablissements";
         
        $url= "https://www.data.gouv.fr/api/1/datasets/".$code."/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
        curl_close($ch);
        $json=json_decode($result, true);
        $resources_tmp=$json["resources"];
        $resources= array();
        foreach ($resources_tmp as $resource_tmp)
        {
            if ($resource_tmp["format"]=="csv") 
            {
                $resource= $resource_tmp;
                $resources[]=$resource;
                break;
            }
        } 
        $lastMaj = $resource["last_modified"]
        */
        $lastMaj=null;
        
        return $this->render('BackOffice/finess/import.html.twig', array(
            'nouveaux'=>$nouveaux,
            'deleted'=>$deleted,
            'communs'=>$communs,
            'deletedWithEtab'=>$deletedWithEtab,
            
            'nouveauxGestionnaire'=>$nouveauxGestionnaire,
            'deletedGestionnaire'=>$deletedGestionnaire,
            'communsGestionnaire'=>$communsGestionnaire,
            'deletedWithGestionnaire'=>$deletedWithGestionnaire,
            'last_maj'=>$lastMaj
        ));
    } 

    
    
    /**
     * Lists all Finess entities.
     *
     * @Route("/import/diff", name="backoffice_finess_import_diff")
     * @Method("GET")
     */
    public function indexImportDiffAction()
    {
        $em = $this->getDoctrine()->getManager();

        
        /*
        $nouveaux=$em->getRepository('Pericles3Bundle:FinessImport')->findNouveaux();
        $nouveauxGestionnaire=
         * 
         */
        
        return $this->render('BackOffice/finess/import_diff.html.twig', array(
            'finesses'=>$em->getRepository('Pericles3Bundle:Finess')->findImportDifferentEtablissement(),
            'last_maj'=>null
        ));
    } 

    /**
     * Creates a new Finess entity.
     *
     * @Route("/new", name="backoffice_finess_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $finess = new Finess();
            $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('Pericles3Bundle\Form\FinessType', $finess);
        $form->handleRequest($request);

        if ($em->getRepository('Pericles3Bundle:Finess')->findOneByCodeFiness($form->getData()->GetCodeFiness()))
        {
            $this->addFlash('error', "Le FINESS ".$form->getData()->GetCodeFiness()."</i> est déja dans la base");
        }
        elseif ($form->isSubmitted() && $form->isValid()) {
            
            $finess->setDemandesEtablissement(null);
            $em->persist($finess);
            
            $em->flush();

            return $this->redirectToRoute('backoffice_finess_show', array('id' => $finess->getId()));
        }

        return $this->render('BackOffice/finess/new.html.twig', array(
            'finess' => $finess,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Finess entity.
     *
     * @Route("/show_{id}", name="backoffice_finess_show")
     * @Method("GET")
     */
    public function showAction(Finess $finess)
    { 

        return $this->render('BackOffice/finess/show.html.twig', array(
            'finess' => $finess 
        ));
    }

    /**
     * Displays a form to edit an existing Finess entity.
     *
     * @Route("/{id}/edit", name="backoffice_finess_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Finess $finess)
    {
       
        $editForm = $this->createForm('Pericles3Bundle\Form\FinessType', $finess);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($finess);
            $em->flush();

            return $this->redirectToRoute('backoffice_finess_edit', array('id' => $finess->getId()));
        }

        return $this->render('BackOffice/finess/edit.html.twig', array(
            'finess' => $finess,
            'edit_form' => $editForm->createView() 
        ));
    }
 
}
