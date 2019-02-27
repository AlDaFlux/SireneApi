<?php


namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

  
use Pericles3Bundle\Entity\Editorial;



/**
 * Evaluation controller.
 *
 * @Route("/infos")
 */
class InfosController extends Controller
{
    
    /**
     * Index Evaluation
     *
     * @Route("/", name="pericles3_filinfos")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $editorials = $em->getRepository('Pericles3Bundle:Editorial')->findMineAndFinish();
        return $this->render('Infos/index.html.twig' ,['editorials' =>$editorials]);
    }
    
    
    
    
    
} 