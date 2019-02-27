<?php

namespace InDaFlux\FontelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/demofont")
     */
    public function indexAction()
    {
        return $this->render('InDaFluxFontelloBundle:Default:index.html.twig');
    }
}
