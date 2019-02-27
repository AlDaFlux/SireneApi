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
 * @Route("/paq")
 */
class PAQController extends Controller
{
        /*
    private function getRepository()
    {
       return( $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineObjectifStrategique'));
    }
    */
    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/", name="pericles3_paq")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $etablissement = $this->getUser()->getEtablissement();
        if ($etablissement)
        {
            $ObjectifsStrategiques =  $em->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->findDerniers($etablissement);
            $objectifOperationnels =  $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->findDerniers($etablissement);
            
            
            $objectifOperationnelsOrphan =  $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->findOrphans($etablissement);
            return $this->render('ObjectifsAmelioration/index.html.twig', array('ObjectifsStrategiques' => $ObjectifsStrategiques,'objectifOperationnels' => $objectifOperationnels, 'objectifOperationnelsOrphan' => $objectifOperationnelsOrphan));
        }
        else
        {
            return $this->render('ObjectifsAmelioration/index_gestionnaire.html.twig');
        }
    }
  
  
    
    
    
    
    
    /**
     * Recherche
     *
     * @Route("/search", name="pericles3_paq_search")
     * @Method({"GET", "POST"})
    */
    public function SearchAction(Request $request)
    {
        
        $results=  new \Doctrine\Common\Collections\ArrayCollection();
        $occurence=$request->get('occurence');
        $etablissement=$this->getUser()->GetEtablissement();
        if ($occurence && $etablissement)
        {
            $results['Objectifs Stratégiques d\'Amélioration']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->FindByEtablissementOccurence($etablissement,$occurence);
            $results['Objectifs Opérationnels d\'Amélioration']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve')->FindByEtablissementOccurencePdv($etablissement,$occurence);
        }
        return $this->render('ObjectifsAmelioration/search.html.twig', ['occurence'=>$occurence, 'results'=>$results ]);
    }
     
    /**
     * Affiche un logigramme du PAQ Par Domaine
     *
     * @Route("/organigramme/domaine_{id}", name="pericles3_paq_organigramme_domaine")
     * @Method("GET")
     */   
    public function organigrammeDomaineAction(Domaine $domaine)
    {
        return $this->render('ObjectifsAmelioration/organigramme_domaine.html.twig', array('domaine' => $domaine));
    }   
    
    
    /**
     * Affiche un logigramme du PAQ
     *
     * @Route("/organigramme", name="pericles3_paq_organigramme")
     * @Method("GET")
     */   
    public function organigrammeAction()
    {
        return $this->render('ObjectifsAmelioration/organigramme.html.twig');
    }
    
    
    
    /**
     * Affiche un logigramme du PAQ par etablissement
     *
     * @Route("/organigramme/etablissement_{id}", name="pericles3_paq_organigramme_etablissement")
     * @Method("GET")
     */   
    public function organigrammeEtablissementAction(Etablissement $etablissement)
    {
        $repositoryDomaine = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Domaine');
        return $this->render('ObjectifsAmelioration/organigramme.html.twig', array('etablissement' => $etablissement));
    }
    
    
    
    
}
