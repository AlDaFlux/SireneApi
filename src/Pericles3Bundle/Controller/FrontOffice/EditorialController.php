<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


use Pericles3Bundle\Entity\Constat;
use Pericles3Bundle\Entity\Critere;
//use Pericles3Bundle\Entity\ObjectifOperationnel;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;



/**
 * Critere controller.
 *
 * @Route("/editorial")
 */
class EditorialController extends Controller
{
     
     
    /**
     * Lists all etablissements entities.
     *
     * @Route("/", name="pericles3_editorial")
     * @Method("GET")
     */
    public function indexAction()
    {
        $editos = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Editorial')->findByUser($this->getUser());
        return $this->render('Editorial/index.html.twig', array('editos' => $editos));
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/{id}", name="pericles3_editorial_show")
     * @Method("GET")
     */
    public function ShowAction(\Pericles3Bundle\Entity\Editorial $editorial)
    {
        return $this->render('Editorial/show.html.twig', array('editorial' => $editorial));
    }
    
    
    
}
