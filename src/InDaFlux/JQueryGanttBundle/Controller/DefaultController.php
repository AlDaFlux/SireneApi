<?php

namespace InDaFlux\JQueryGanttBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/gantt")
     */
    public function indexAction()
    {
        return $this->render('InDaFluxJQueryGanttBundle:Default:index.html.twig');
    }
}

