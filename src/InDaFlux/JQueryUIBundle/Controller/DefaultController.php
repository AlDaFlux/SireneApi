<?php

namespace InDaFlux\JQueryUIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/jqueryui")
     */
    public function indexAction()
    {
        return $this->render('InDaFluxJQueryUIBundle:Default:index.html.twig');
    }
}

