<?php

namespace InDaFlux\HtmlToDocWImgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/indafluxhtmltodocwimg")
     */
    public function indexAction()
    {
        return $this->render('InDaFluxHtmlToDocWImgBundle:Default:index.html.twig');
    }
}
