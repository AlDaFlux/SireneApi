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
 * @Route("/backoffice/watchdd")
 */
class WatchDD extends Controller
{

    
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/", name="backoffice_admin_watchdd")
     * @Method("GET")
     */
    public function WatchDDAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        
        return $this->render('BackOffice/watchdd/index.html.twig', array(
        
        ));
    }
        
    
    
    /**
     * Recherche
     *
     * @Route("/file", name="backoffice_admin_watchdd_searchfile")
     * @Method({"GET", "POST"})
    */
    public function fileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filename=$request->get('filename');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $biblios=$em->getRepository('Pericles3Bundle:Bibliotheque')->FindBiblioByFileAll($filename);
            $preuves=$em->getRepository('Pericles3Bundle:Preuve')->findFichiersByName($filename);

//            $locations= exec('locate "'.$filename.'"');
            $locations= shell_exec('locate "'.$filename.'"');
            
            
            return $this->render('BackOffice/watchdd/file.html.twig', ['filename'=>$filename, 'bibliotheques'=>$biblios, 'preuves'=>$preuves,'locations'=>$locations]);
         
        }

    }
    
    
    
}
