<?php

namespace InDaFlux\ChartJsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/chartjs")
     */
    public function indexAction()
    {
        return $this->render('InDaFluxChartJsBundle:Default:index.html.twig');
    }
}
