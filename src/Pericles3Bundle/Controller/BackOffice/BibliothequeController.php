<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pericles3Bundle\Entity\Bibliotheque;


/**
 * Bibliotheque controller.
 *
 * @Route("/backoffice/bibliotheque")
 */
class BibliothequeController extends Controller
{

    
    
    
    /* BIBLIO BACKOFFICE */
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/", name="backoffice_bibliotheque_index")
     * @Method("GET")
     */
    public function indexBiblithequeBOAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bibliotheques = $em->getRepository('Pericles3Bundle:Bibliotheque')->findBy(array("gestionnaire"=>$this->getUser()->GetGestionnaire() ),array('dateUpdate' => 'DESC'));
        return $this->render('BackOffice/Bibliotheque/index.html.twig', array(
            'bibliotheques' => $bibliotheques
        ));
    }
        
    /*  -------------FIN  BIBLIO BACKOFFICE  ------------------ */
    
    
    
}
