<?php

namespace InDaFlux\SwissArmyKnifeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/SwissArmyKnifeBundleHelp")
     */
    public function indexAction()
    {
        return $this->render('InDaFluxSwissArmyKnifeBundle:Default:index.html.twig');
    }
}
