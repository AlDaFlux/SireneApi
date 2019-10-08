<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Pericles3Bundle\Entity\FinessGestionnaire;
use Pericles3Bundle\Entity\Departement;
 

/**
 * Finess controller.
 *
 * @Route("/backoffice/finess/gestionnaire")
 */
class FinessGestionnaireController extends Controller
{
    /**
     * Lists all Finess entities.
     *
     * @Route("/", name="backoffice_finess_gestionnaire_index")
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
        return $this->render('BackOffice/finess_gestionnaire/index.html.twig', array(
            'departements'=>$departements
        ));
    }
    
    
    
    
    
    /**
     * Lists all Finess entities.
     *
     * @Route("/departement_{id}", name="backoffice_finess_gestionnaire_departement")
     * @Method("GET")
     */
    public function indexDepartementAction(Departement  $Departement)
    {
        return $this->render('BackOffice/finess_gestionnaire/finnes_par_dep.html.twig', array(
            'departement'=>$Departement
        ));
    }
     
    
     
    /**
     * Finds and displays a Finess entity.
     *
     * @Route("/show_{id}", name="backoffice_gestionnaire_finess_show")
     * @Method("GET")
     */
    public function showAction(FinessGestionnaire $finess)
    {
        

        return $this->render('BackOffice/finess_gestionnaire/show.html.twig', array(
            'finess' => $finess 
        ));
    }
    
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/search_form", name="pericles3_backoffice_formsearch_finess_gestionnaire")
     * @Method("GET")
     */
    public function searchFinessGestionnaireAction(Request $request)
    {
        $q = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        if ($this->GetUser()->GetCreai())
        {
            $results = $this->getDoctrine()->getRepository('Pericles3Bundle:FinessGestionnaire')->findLikeCreai($q,$this->GetUser()->GetCreai());
        }
        else
        {
            $results = $this->getDoctrine()->getRepository('Pericles3Bundle:FinessGestionnaire')->findLike($q);
        }        
        return $this->render('BackOffice/finess_gestionnaire/search_result.html.twig', ['results' => $results]);
    }

    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/search_get_{codeFiness}", name="pericles3_backoffice_formsearch_get_finess_gestionnaire")
     * @Method("GET")
     */
    public function getFinessGestionnaireAction($codeFiness = null)
    {
        $author = $this->getDoctrine()->getRepository('Pericles3Bundle:FinessGestionnaire')->findByCodefiness($codeFiness);
        return new Response($author->getRaisonSociale());
    }
    
    
    
    
   
}
