<?php


namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


use Pericles3Bundle\Entity\Etablissement;



/**
 * Evaluation controller.
 *
 * @Route("/watchdd")
 */
class SurveillanceDisqueController extends Controller
{
    /**
     * Index Evaluation
     *
     * @Route("/", name="watchdd_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') )
        {
                
        }
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE'))
        {
            $dossiers_name[]="bibliotheque";
            foreach ($dossiers_name as $dossier_name)
            {
                $folder=WEB_DIR."/upload/".$this->getUser()->GetUploadFolderPath()."/".$dossier_name;
                $debugthis['folder']=$folder;
                $debugthis['name']=$dossier_name;
                $debugthis['size']=$this->get('Utils')->SizeFiles($this->get('Utils')->scanDirectory($folder,$dossier_name,null,$this->GetUser()->GetGestionnaire()));
                $debugthis['files']=$this->get('Utils')->scanDirectory($folder,$dossier_name,null,$this->GetUser()->GetGestionnaire());
                $dossiers[$dossier_name]=$debugthis;
            }
            return $this->render('Index/watchdd.html.twig', ['dossiers'=>$dossiers]);
        }
        elseif($this->getUser()) 
        {
            $dossiers_name[]="preuves";
            $dossiers_name[]="bibliotheque";

            foreach ($dossiers_name as $dossier_name)
            {
                
                $folder=WEB_DIR."/upload/".$this->getUser()->GetUploadFolderPath()."/".$dossier_name;
                
                $debugthis['folder']=$folder;
                $debugthis['name']=$dossier_name;
                $debugthis['size']=$this->get('Utils')->SizeFiles($this->get('Utils')->scanDirectory($folder,$dossier_name,$this->GetUser()->getEtablissement()));
                $debugthis['files']=$this->get('Utils')->scanDirectory($folder,$dossier_name,$this->GetUser()->getEtablissement());
                $dossiers[$dossier_name]=$debugthis;
            }
            return $this->render('Index/watchdd.html.twig', ['dossiers'=>$dossiers]);
        }
    }
    
    
    
    
     /**
     * Index Evaluation
     *
     * @Route("/etablissement_{id}", name="watchdd_index_etablissement")
     * @Method("GET")
     */
    public function indexEtablissementAction(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        
            $dossiers_name[]="preuves";
            $dossiers_name[]="bibliotheque";

            foreach ($dossiers_name as $dossier_name)
            {
                $folder=WEB_DIR."/upload/".$etablissement->GetUploadFolderPath()."/".$dossier_name;
                $debugthis['folder']=$folder;
                $debugthis['name']=$dossier_name;
                $debugthis['size']=$this->get('Utils')->SizeFiles($this->get('Utils')->scanDirectory($folder,$dossier_name,$etablissement));
                $debugthis['files']=$this->get('Utils')->scanDirectory($folder,$dossier_name,$etablissement);
                $dossiers[$dossier_name]=$debugthis;
            }
            
            return $this->render('Index/watchdd.html.twig', ['dossiers'=>$dossiers, 'etablissement'=>$etablissement]);
    }
    
    
    
    
    
    
    /**
     * Index Evaluation
     *
     * @Route("/watch_dd_delete_preuve_file/{filename}", name="watch_dd_delete_preuve_file")
     * @Method("GET")
     */
    public function DeleteFilePreuveAction($filename)
    {
        $etablissement=$this->getUser()->GetEtablissement();
        if($etablissement) 
        {
            $folder=WEB_DIR."/upload/".$this->getUser()->GetUploadFolderPath()."/preuves/";
            $fichier=$folder.$filename;
            if (file_exists($fichier))
            {
                unlink ($fichier);
                $this->addFlash('success', "Le fichier ".$filename." à bien été supprimé");
            }
            else
            {
                $this->addFlash('error', "Le fichier n'existe pas ");
            }
        }
        else
        {
            $this->addFlash('error', "Une erreur est survenue lors de la supression du fichier");
        }
        return $this->redirectToRoute('watchdd_index');
    }
    
    
    
    /**
     * Index Evaluation
     *
     * @Route("/watch_dd_delete_preuve_file/etablissement_{id}/{filename}", name="watch_dd_delete_preuve_etablissement_file")
     * @Method("GET")
     */
    public function DeleteFilePreuveEtablissementAction(Etablissement $etablissement,$filename)
    {
        $folder=WEB_DIR."/upload/".$etablissement->GetUploadFolderPath()."/preuves/";
        $fichier=$folder.$filename;
        if (file_exists($fichier))
        {
            unlink ($fichier);
            $this->addFlash('success', "Le fichier ".$filename." à bien été supprimé");
        }
        else
        {
            $this->addFlash('error', "Le fichier n'existe pas ");
        }
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') )
        {
            return $this->redirectToRoute('backoffice_etablissement_view_disque', ['id'=>$etablissement->getId()]);
        }
        else
        {
            return $this->redirectToRoute('watchdd_index_etablissement', ['id'=>$etablissement->getId()]);
        }
    }


    
    
    
    
    
    
      
         
}

    
    
     
    
     