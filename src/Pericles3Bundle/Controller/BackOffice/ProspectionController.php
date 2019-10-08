<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
  

/**
 * Gestionnaire controller.
 *
 * @Route("/backoffice/prospection")
 */ 
class ProspectionController extends Controller
{ 
    
        
     /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/", name="backoffice_prospection_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->GetUser()->getIsSupevisor())
        {
            return $this->render('BackOffice/Prospection/index.html.twig');
        }
        elseif ($this->GetUser()->getCreai())
        {
            $finesses = $em->getRepository('Pericles3Bundle:Finess')->findProspectionCreai($this->GetUser()->getCreai());
            return $this->render('BackOffice/Prospection/liste.html.twig', array('finesses' => $finesses));
        }
    }
    
     /**
     * Finds and displays a finessCategorie entity.
     *
     * @Route("/public_{id}", name="backoffice_prospection_public")
     * @Method("GET")
     */
    public function showAction(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        $em = $this->getDoctrine()->getManager();
        $finesses = $em->getRepository('Pericles3Bundle:Finess')->findProspectionReferentielPublic($referentielPublic);
        
        $pericles = $em->getRepository('Pericles3Bundle:Pericles')->findProspectionReferentielPublic($referentielPublic);
        
        
        return $this->render('BackOffice/Prospection/show.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'finesses' => $finesses, 
            'pericles' => $pericles 
        ));
    }
    
    
    
    
}   


