<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Pericles3Bundle\Entity\Domaine;
use Pericles3Bundle\Entity\Critere;
use Pericles3Bundle\Entity\Dimension;
use Pericles3Bundle\Entity\DomaineExterne;
use Pericles3Bundle\Entity\Etablissement;

use Pericles3Bundle\Entity\Sauvegarde;
use Pericles3Bundle\Entity\SauvegardeCritere;
use Pericles3Bundle\Entity\SauvegardeDimension;
use Pericles3Bundle\Entity\SauvegardeDomaine;
use Pericles3Bundle\Entity\SauvegardeQuestion;
use Pericles3Bundle\Entity\ReferentielPublic;




use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Dompdf\Dompdf;
use \stdClass;


/**
 * DomaineObjectifStrategique controller.
 *
 * @Route("/synthese")
 */
class SyntheseController extends Controller
{
        
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/", name="pericles3_synthese")
     * @Method("GET")
     */
    public function indexAction()
    {
        if ($this->GetUser()->IsGestionnaire())
        {
            $etablissements=$this->GetUser()->GetEtablissements();
            $referentiels=$this->GetUser()->getGestionnaireReferentielsPublic();
            foreach ($etablissements as $etablissement)
            {
                $etabsByRef[$etablissement->GetReferentielPublic()->GetID()][]=$etablissement;
            }
            

            return $this->render('Synthese/index.html.twig', 
                array('referentielsPublic'=> $referentiels, 
                'etablissements'=> $etablissements, 
                'etabsByRef'=> $etabsByRef 
                    ));

        }
        else
        {
            return $this->render('Synthese/index.html.twig');

        }
        
    }
    
    
    /**
     * Synthese Etablissment
     *
     * @Route("/referentiel_{id}", name="pericles3_synthese_referentiel_menu")
     * @Method("GET")
     */
    public function indexSynthesereferentielMenuAction(ReferentielPublic  $referentielPublic)
    {
        return $this->render('Synthese/index_referentiel.html.twig' , ['referentielPublic'=>$referentielPublic]);
//        return $this->render('Synthese/synthese_arsene/synthese_pericles.html.twig', ['etablissement'=>$Etablissement]);
    }
     
    
    
    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/synthese_arsene", name="pericles3_synthese_pericles")
     * @Method("GET")
     */
    public function indexPericlesAction()
    {
        return $this->render('Synthese/synthese_arsene/synthese_pericles.html.twig' );
    }
     
  
    /**
     * Synthese Etablissment
     *
     * @Route("/etablissement_{id}", name="pericles3_synthese_etablissement")
     * @Method("GET")
     */
    public function indexEtablissementAction(Etablissement $Etablissement)
    {

        return $this->render('Synthese/index.html.twig' , ['etablissement'=>$Etablissement]);

        
//        return $this->render('Synthese/synthese_arsene/synthese_pericles.html.twig', ['etablissement'=>$Etablissement]);
    }
     
    /**
     * Synthese Etablissment
     *
     * @Route("/synthese_arsene/etablissement_{id}", name="pericles3_synthese_arsene_etablissement")
     * @Method("GET")
     */
    public function indexSyntheseArsenetEtablissementAction(Etablissement $Etablissement)
    {
        return $this->render('Synthese/synthese_arsene/synthese_pericles.html.twig', ['etablissement'=>$Etablissement]);
    }
     


      
    /**
     * Synthese Etablissment
     *
     * @Route("/backup/{id}", name="pericles3_backup_synthese")
     * @Method("GET")
     */
    public function indexBackupAction(Sauvegarde $Sauvegarde)
    {
        return $this->render('Synthese/Sauvegardes/synthese_arsene.html.twig', ['Sauvegarde'=>$Sauvegarde]);
    }
     

    
            
      
    
    /**
     * Synthese alternatives
     *
     * @Route("/alternative_gestionnaire", name="syntheses_alternative_gestionnaire")
     * @Method("GET")
     */
    public function SyntheseAlternativesGestionnaireAction()
    {
        return $this->render('Synthese/synthese_alternative/synthese_alternative_gestionnaire.html.twig');
    }
    
     
    
    
    /**
     * Synthèse du domaine
     *
     * @Route("/domaine_{id}", name="pericles3_synthese_domaine")
     * @Method("GET")
     */
    public function indexDomaineAction(Domaine $domaine)
    {
        return $this->render('Synthese/synthese_arsene/synthese_domaine.html.twig', array('domaine'=> $domaine));
    }

    
    /**
     * Synthèse due la dimension
     *
     * @Route("/dimension_{id}", name="pericles3_synthese_dimension")
     * @Method("GET")
     */
    public function indexDimensionAction(Dimension $dimension)
    {
        return $this->render('Synthese/synthese_arsene/synthese_dimension.html.twig', array('dimension'=> $dimension));
    }

    
    
    
    /**
     * Synthèse du critere
     *
     * @Route("/critere_{id}", name="pericles3_synthese_critere")
     * @Method("GET")
     */
    public function indexCritereAction(Critere $critere)
    {
        return $this->render('Synthese/synthese_arsene/synthese_critere.html.twig', array('critere'=> $critere));
    }

    
    
    
    
    
    /**
     * Classement Alternatif (ANESM...
     *
     * @Route("/classement_alternatif", name="pericles3_synthese_alternatif")
     * @Method("GET")
     */
    public function indexAlternatifAction()
    {
        return $this->render('Synthese/synthese_alternative/synthese_alternative.html.twig');
    }
    
    
    /**
     * Classement Alternatif (ANESM...
     *
     * @Route("/classement_alternatif/etablissement_{id}", name="pericles3_synthese_alternatif_etablissement")
     * @Method("GET")
     */
    public function indexAlternatifEtablissemntAction(Etablissement $Etablissement)
    {
        return $this->render('Synthese/synthese_alternative/synthese_alternative.html.twig', ['etablissement'=>$Etablissement]);
    }
    
    
    
    
    /**
     * Classement Alternatif (ANESM...) pa niveau
     *
     * @Route("/classement_alternatif/niv_{id}", name="pericles3_synthese_alternatif_niv1")
     * @Method("GET")
     */
    public function indexAlternatifNiv1Action(DomaineExterne $DomaineExterne)
    {
        return $this->render('Synthese/synthese_alternative/synthese_alternative_niv1.html.twig', ['DomaineExterne' => $DomaineExterne ]);
    }
    
    
    /**
     * Classement Alternatif (ANESM...
     *
     * @Route("/export/classement_alternatif", name="pericles3_synthese_alternatif_export")
     * @Method("GET")
     */
    public function indexAlternatifExportAction()
    {
        $etablissement = $this->getUser()->getEtablissement();
        return $this->render('Synthese/export/export_alternatif.html.twig', ['etablissement' => $etablissement ]);
    }
     
 
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/page_export/etablissement_{id}", name="pericles3_synthese_export_page_etablissement")
     * @Method("GET")
     */
    public function indexExportPageEtablissementAction(Etablissement $Etablissement)
    {
        return $this->render('Synthese/export.html.twig', ['etablissement'=>$Etablissement]);
    }
    
    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/page_export_config/etablissement_{id}", name="pericles3_synthese_export_config_etablissement")
     * @Method("GET")
     */
    public function indexExportConfigPageEtablissementAction(Etablissement $Etablissement)
    {
        return $this->render('Synthese/export_etablissement_config.html.twig', ['etablissement'=>$Etablissement]);
    }
    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/generate/etablissement_{id}/preview", name="pericles3_generate_export_etablissement_preview")
     * @Method("GET")
     */
    public function indexExportGenerateEtablissementPreviwAction(Request $request,Etablissement $Etablissement)
    {
        return $this->render('Synthese/export/generate/synthese.html.twig', ['parameters'=> $request->query->all(),'typeExport'=>'PDF','etablissement'=>$Etablissement]);
        
    }
     
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/generate/etablissement_{id}/{synthese_extension}", name="pericles3_generate_export_etablissement")
     * @Method("GET")
     */
    public function indexExportGenerateEtablissementPdfAction(Request $request,Etablissement $Etablissement, $synthese_extension)
    {

        $uploadPath = WEB_DIR.'/upload/';
        $filename=$this->getParameter('application_name')." - ".$Etablissement.' - '.date("Y-m-d").'.'.$synthese_extension;
        $file=$uploadPath.$Etablissement->GetUploadFolderPath()."/synthese/".date("Y-m-d").".".$synthese_extension;
        $view = $this->renderView('Synthese/export/generate/synthese.html.twig', ['parameters'=> $request->query->all(),'typeExport'=>strtoupper($synthese_extension) ,'etablissement'=>$Etablissement]);
        
         if ($synthese_extension=="pdf")
        {
                $dompdf = new DOMPDF();
                $dompdf->load_html($view);
                $dompdf->render();
                file_put_contents($file, $dompdf->output());
                return($this->exportFile($file,$filename, $synthese_extension));
        }
        elseif ($synthese_extension=="doc")
        {
            $view=$this->get('HtmlToMht')->HtmlToMht($view);
            $fp = fopen($file, 'w');
            fwrite($fp, $view);
            return($this->exportFile($file,$filename, $synthese_extension));
        }
        else { return new JsonResponse(false); }
        
        
    }
    
     
    
    
    
    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/page_export", name="pericles3_synthese_export_page")
     * @Method("GET")
     */
    public function indexExportPageAction()
    {
        return $this->render('Synthese/export.html.twig');
    }
    
    
    
    
         
    
    /**
     * Exportation du fichier
     *
     * @Route("/export/etablissement_{id}/file/{synthese_type}.{synthese_extension}", name="arsene_synthese_getpath")
     * @Method({"GET", "POST"})
     */
    public function exportAction(Etablissement $Etablissement, $synthese_type, $synthese_extension){
        $uploadPath = WEB_DIR.'/upload/';
        $filename=$this->getParameter('application_name')." - ".$synthese_type.' - ' .$Etablissement.' - '.date("Y-m-d").'.'.$synthese_extension;
        $file=$uploadPath.$Etablissement->GetUploadFolderPath()."/synthese/".$synthese_type.".".$synthese_extension;
        return($this->exportFile($file,$filename,  $synthese_extension));
    }

    
    
    
    /**
     * Exportation du fichier
     *
     * @Route("/export/referentiel_{id}/file/{synthese_type}.{synthese_extension}", name="arsene_synthese_getpath_referentiel")
     * @Method({"GET", "POST"})
     */
    public function exportRefAction( ReferentielPublic $referentielPublic, $synthese_type, $synthese_extension){
        $uploadPath = WEB_DIR.'/upload/';
        $filename=$this->getParameter('application_name')." - ".$synthese_type.' - ' .$referentielPublic.' - '.date("Y-m-d").'.'.$synthese_extension;
        $file=$uploadPath.$this->GetUser()->Getgestionnaire()->GetUploadFolderPath()."/synthese/".$synthese_type.".".$synthese_extension;
        return($this->exportFile($file,$filename,  $synthese_extension));
    }
 
    
    /**
     * Exportation du fichier
     *
     * @Route("/export/sauvegarde_{id}/file/{synthese_type}.{synthese_extension}", name="arsene_synthese_sauvegarde_getpath")
     * @Method({"GET", "POST"})
     */
    public function exportSauvAction(sauvegarde $Sauvegarde, $synthese_type, $synthese_extension){
        $uploadPath = WEB_DIR.'/upload/';
        $Etablissement= $Sauvegarde->GetEtablissement();
        
        if ($synthese_type=="synthese_annexe") $filename=$this->getParameter('application_name')." - Annexe au rapport d'activité ".' - ' .$Etablissement.' - '.$Sauvegarde->getDateCreate()->format('d-m-Y')." - ".date("d-m-Y").'.'.$synthese_extension;
        else $filename=$filename=$this->getParameter('application_name')." - Sauvegarde ".$synthese_type.' - ' .$Etablissement.' - '.$Sauvegarde->getDateCreate()->format('d-m-Y')." - ".date("d-m-Y").'.'.$synthese_extension;
        
        $file=$uploadPath.$Etablissement->GetUploadFolderPath()."/synthese/".$synthese_type."_".$Sauvegarde->GetId().".".$synthese_extension;
        return($this->exportFile($file,$filename,  $synthese_extension));
    }

    
    private function exportFile($file,$filename,   $synthese_extension)
    {
        if($synthese_extension=="pdf")
        {
            $contenttype="application/pdf";
        }
        elseif($synthese_extension=="doc")
        {
            $contenttype="application/vnd.ms-word";
        }
        else
            die();
        
        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
//        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-type', $contenttype);
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');
        $response->headers->set('Content-length', filesize($file));
        $response->sendHeaders();
        $response->setContent(file_get_contents($file));
        return $response;
    }
    
    
     
                      
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/genere_graphs", name="pericles3_genere_graphs")
     * @Method({"GET", "POST"})
     */
    public function getGenereGraphs(Request $request)
    {
          
        //check si c'est un appel Ajax
          /*
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            die();
        }
        */
        $prefix=$request->get('prefix');

        if ($request->get("etablissement_id"))
        {
            $repositoryEtablissement = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $Etablissement = $repositoryEtablissement->findOneById($request->get("etablissement_id"));
        }
        elseif ($request->get("sauvegarde_id"))
        {
            $Sauvegarde= $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Sauvegarde')->findOneById($request->get("sauvegarde_id"));
            $Etablissement = $Sauvegarde->getEtablissement();
            $prefix='_backup_'.$Sauvegarde->GetId()."";
        }

        else
        {
            $Etablissement=$this->getUser()->getEtablissement();
        }
        
        
        $graphGeneral = $request->get('graphGeneral');
        $graphsDomaines = $request->get('graphsDomaines');
        
        
        
        //throw $this->createNotFoundException('Les testTab contienne  :  '.$keys);
            

        if (! $graphGeneral )
        {
             throw $this->createNotFoundException("Le graph principal n'a pas été trouvé");
        }
        
        if (! count($graphsDomaines))
        {
             throw $this->createNotFoundException('Les graphs des domaines ne sont pas trouvés');
        }
        

        $UploadGraphsDirectory =$Etablissement->GetUploadFolderPath()."/synthese/graphs/";
        $this->get('Utils')->FolderUploadExisteCreate($UploadGraphsDirectory);
        
        $UploadGraphsDirectory=UPLOAD_DIR.$UploadGraphsDirectory;
      
        
        //########## TRANSFORMATION IMAGE BASE 64 --> JPG ############
        self::base64_to_jpeg($graphGeneral,$UploadGraphsDirectory."general".$prefix.".jpg");
        foreach ($Etablissement->GetDomaines() as $domaine) {
            if (array_key_exists($domaine->getId(),$graphsDomaines))
            {
                self::base64_to_jpeg($graphsDomaines[$domaine->getId()],$UploadGraphsDirectory.$domaine->getId().$prefix.".jpg");
            }
            else
            {
                 $keys="";
                foreach ($graphsDomaines as $key => $val)
                {
                    $keys.=$key.", ";
                }
                throw $this->createNotFoundException('Les graphs des ne contienne pas le '.$domaine->getId()."mais ".$keys);
                
            }
//            self::base64_to_jpeg($graphGeneral,$UploadGraphsDirectory.$domaine->getId().$prefix.".jpg");
            }
            
        return new JsonResponse(true);
    }
    


    private function GetUploadDirectorySynthese(Etablissement $Etablissement)
    {
        $relative=$Etablissement->GetUploadFolderPath()."/synthese/";
        $UploadDirectory = UPLOAD_DIR.$relative;
        $this->get('Utils')->FolderUploadExisteCreate($relative);
        return($UploadDirectory);
        
    }

    private function GetUploadDirectorySyntheseReferentiel()
    {
        $relative=$this->GetUser()->GetGestionnaire()->GetUploadFolderPath()."/synthese/";
        $UploadDirectory = UPLOAD_DIR.$relative;
        $this->get('Utils')->FolderUploadExisteCreate($relative);
        return($UploadDirectory);
        
    }

    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/generesynthesesdocument_referentiel", name="pericles3_synthese_referntiel_generation")
     * @Method({"GET", "POST"})
     */
    public function genereSyntheseReferentielDocumentAction(Request $request)
    {
    //check si c'est un appel Ajax
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            die();
        }
        if ($request->get("referentiel_id"))
        {
            $repository = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ReferentielPublic');
            $referentielPublic = $repository->findOneById($request->get("referentiel_id"));
            
        }
        else
        {
            die();
        }
        
        $synthese_type=$request->get('type');
        $synthese_extension=$request->get('extension');
        $view = $this->renderView('Synthese/export/Referentiel/'.$synthese_type.'.html.twig', 
                        array(
                        'referentielPublic'=>$referentielPublic,
                        'typeExport'=>  strtoupper($synthese_extension)
                        ));
        return($this->genereSyntheseFunctionAction($this->GetUploadDirectorySyntheseReferentiel(),$view,$synthese_type,$synthese_extension));
    }
    
    
    
    

    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/generesynthesesdocument", name="pericles3_synthese_document_generation")
     * @Method({"GET", "POST"})
     */
    public function genereSyntheseDocumentAction(Request $request)
    {
    //check si c'est un appel Ajax
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            die();
        }
        if ($request->get("etablissement_id"))
        {
            $repositoryEtablissement = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $Etablissement = $repositoryEtablissement->findOneById($request->get("etablissement_id"));
        }
        else
        {
            $Etablissement=$this->getUser()->getEtablissement();
        }
        $synthese_type=$request->get('type');
        $synthese_extension=$request->get('extension');
        $view = $this->renderView('Synthese/export/'.$synthese_type.'.html.twig', 
                        array(
                        'etablissement'=>$Etablissement,
                        'typeExport'=>  strtoupper($synthese_extension)
                        ));
        return($this->genereSyntheseFunctionAction($this->GetUploadDirectorySynthese($Etablissement),$view,$synthese_type,$synthese_extension));
    }
    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/genere_synthese_sauvegarde", name="pericles3_synthese_document_generation_sauvegarde")
     * @Method({"GET", "POST"})
     */
    public function genereSyntheseDocumentSauvegardeAction(Request $request)
    {
    //check si c'est un appel Ajax
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            die();
        }
        $Sauvegarde = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Sauvegarde')->findOneById($request->get("sauvegarde_id"));
        $Etablissement=$Sauvegarde->getEtablissement();
        $synthese_type=$request->get('type');
        $synthese_extension=$request->get('extension');
        $view = $this->renderView('Synthese/export/'.$synthese_type.'.html.twig', 
                        array(
                        'etablissement'=>$Etablissement,
                        'Sauvegarde'=>$Sauvegarde,
                        'typeExport'=>  strtoupper($synthese_extension)
                        ));
        return($this->genereSyntheseFunctionAction($this->GetUploadDirectorySynthese($Etablissement),$view,$synthese_type,$synthese_extension,"_".$Sauvegarde->GetId()));
    }
    
    
    
    
    
    public function genereSyntheseFunctionAction($path,$view,$synthese_type,$synthese_extension,$suffixe='')
    {
        if ($synthese_extension=="pdf")
        {
            //include($this->get('kernel')->getRootDir().'/../vendor/dompdf/dompdf_config.inc.php');
            //############### GENERATION PDF ###############
            $dompdf = new DOMPDF();
            $dompdf->load_html($view);
            $dompdf->render();
            $file_to_save=$path.$synthese_type.$suffixe.".pdf";
            file_put_contents($file_to_save, $dompdf->output());
            return new JsonResponse(true);
        }
        elseif ($synthese_extension=="doc")
        {
            //include($this->get('kernel')->getRootDir().'/../vendor/htmltodoc/html_to_doc.inc.php');
            $view=$this->get('HtmlToMht')->HtmlToMht($view);
            $fp = fopen($path.$synthese_type.$suffixe.".doc", 'w');
            fwrite($fp, $view);
            return new JsonResponse(true);
        }
        else { return new JsonResponse(false); }
    }
 
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/testdev_synthese/sauvegarde_{id}/type_{synthese_type}", name="pericles3_synthese_sauvegarde_dev")
     * @Method("GET")
     */
    public function testdevSauvegardeAction(Sauvegarde $Sauvegarde,$synthese_type)
    {
        $Etablissement=$this->getUser()->getEtablissement();
        return($this->render('Synthese/export/'.$synthese_type.'.html.twig', 
                array(
                'etablissement'=>$Etablissement,
                'Sauvegarde'=>$Sauvegarde,
                'typeExport'=>  strtoupper("PDF")
                )));    
    } 

    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/testdev_synthese/ref_public_{id}/{synthese_type}", name="pericles3_synthese_dev_ref")
     * @Method("GET")
     */
    public function testdevRefAction(ReferentielPublic $referentielPublic, $synthese_type)
    {
        $synthese_extension="PDF";
       return($this->render('Synthese/export/Referentiel/'.$synthese_type.'.html.twig', 
                        array(
                        'referentielPublic'=>$referentielPublic,
                        'typeExport'=>  strtoupper($synthese_extension)
                        )));    
        
    }
    
    
       
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/testdev_synthese_{synthese_type}", name="pericles3_synthese_dev")
     * @Method("GET")
     */
    public function testdevAction($synthese_type)
    {
        $Etablissement=$this->getUser()->getEtablissement();
        $synthese_extension="PDF";
       return($this->render('Synthese/export/'.$synthese_type.'.html.twig', 
                        array(
                        'etablissement'=>$Etablissement,
                        'typeExport'=>  strtoupper($synthese_extension)
                        )));    
        
    }
    
    
     
    /**
     * Sauvegardes
     *
     * @Route("/sauvegardes", name="pericles3_sauvegardes")
     * @Method("GET")
     */
    public function indexSauvegardesAction()
    {
        if ($this->GetUser()->IsAnEtablissement())
        {
            return ($this->render('Synthese/Sauvegardes/index.html.twig'));
        }
        elseif ($this->getUser()->GetGestionnaire())
        {  
            $sauvegardes=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Sauvegarde')->findByGestionnaire($this->getUser());
            return ($this->render('Synthese/Sauvegardes/index_gestionnaire.html.twig',['sauvegardes'=>$sauvegardes]));
        }
    }
    
    
    
    

       
    /**
     * Sauvegardes
     *
     * @Route("/sauvegardes/etablissement_{id}", name="pericles3_sauvegardes_etablissement")
     * @Method("GET")
     */
    public function indexSauvegardesEtablissementAction(Etablissement $Etablissement)
    {
        return ($this->render('Synthese/Sauvegardes/index.html.twig', ['etablissement'=>$Etablissement]));
    }

  
    /**
     * Sauvegardes
     *
     * @Route("/annexes", name="pericles3_annexes")
     * @Method("GET")
     */
    public function indexAnnexeAction()
    {
        
        return ($this->render('Synthese/annexes.html.twig'));
    }

       
    /**
     * Sauvegardes
     *
     * @Route("/annexes/etablissement_{id}", name="pericles3_annexe_etablissement")
     * @Method("GET")
     */
    public function indexAnnexeEtablissementAction(Etablissement $Etablissement)
    {
        return ($this->render('Synthese/annexes.html.twig', ['etablissement'=>$Etablissement]));
    }

    
        
    
      
        
    /**
     * Sauvegardes
     *
     * @Route("/sauvegardes/domaine_{id}", name="pericles3_sauvegardes_domaine")
     * @Method("GET")
     */
    public function indexSauvegardesDomaineAction(SauvegardeDomaine $SauvegardeDomaine)
    {
        return ($this->render('Synthese/Sauvegardes/synthese_domaine.html.twig', ['domaineBackup'=>$SauvegardeDomaine]));
    }

        
    /**
     * Sauvegardes
     *
     * @Route("/sauvegardes/dimension_{id}", name="pericles3_sauvegardes_dimension")
     * @Method("GET")
     */
    public function indexSauvegardesDimensionAction(SauvegardeDimension $SauvegardeDimension)
    {
        return ($this->render('Synthese/Sauvegardes/synthese_dimension.html.twig', ['dimensionBackup'=>$SauvegardeDimension]));
    }
    
        
    /**
     * Sauvegardes
     *
     * @Route("/sauvegardes/critere_{id}", name="pericles3_sauvegardes_critere")
     * @Method("GET")
     */
    public function indexSauvegardesCritereAction(SauvegardeCritere $SauvegardeCritere)
    {
        return ($this->render('Synthese/Sauvegardes/synthese_critere.html.twig', ['critereBackup'=>$SauvegardeCritere]));
    }
    
    

    
    

    /**
     * Sauvegardes
     *
     * @Route("/sauvegardes/etablissement_{id}/do", name="pericles3_sauvegardes_etablissement_do")
     * @Method("GET")
     */
    public function doBackupEtabAction(Etablissement $Etablissement)
    {
        $this->doBackup($Etablissement);
        return $this->redirectToRoute('pericles3_sauvegardes_etablissement', array('id' => $Etablissement->getId()));
    }
    
    /**
     * Sauvegardes
     *
     * @Route("/sauvegardes/do", name="pericles3_sauvegardes_do")
     * @Method("GET")
     */
    public function doBackupAction()
    {
        $Etablissement=$this->getUser()->GetEtablissement();
        $this->doBackup($Etablissement);
        return $this->redirectToRoute('pericles3_sauvegardes');

    }
    
    
    

    public function doBackup($Etablissement)
    {    
     $em = $this->getDoctrine()->getManager();
            $Sauvegarde= new Sauvegarde();
            $Sauvegarde->setEtablissement($Etablissement);
            $Sauvegarde->setDateCreate(new \DateTime());
            $Sauvegarde->setUser($this->getUser());            
            $Sauvegarde->setNom("TEST --- SAUVEGARDE DU ");            
            $Sauvegarde->setNote($Etablissement->GetMoyenneNotes());            
            $em->persist($Sauvegarde);
            $em->flush();   
            
            $Domaines = $Etablissement->getDomaines();
            foreach ($Domaines as $Domaine) {
    			$SauvegardeDomaine = new SauvegardeDomaine();
                        $SauvegardeDomaine->setReferentiel($Domaine->GetReferentiel());
                        $SauvegardeDomaine->setNote($Domaine->GetMoyenneNotes());
                        $SauvegardeDomaine->SetSauvegarde($Sauvegarde);
                        $SauvegardeDomaine->SetDomaineOriginal($Domaine);
    			$em->persist($SauvegardeDomaine);
                        $em->flush();   
                        foreach ($Domaine->GetDimensions() as $Dimension) 
                        {
                            $SauvegardeDimension = new SauvegardeDimension();
                            $SauvegardeDimension->setReferentiel($Dimension->GetReferentiel());
                            $SauvegardeDimension->setNote($Dimension->GetMoyenneNotes());
                            $SauvegardeDimension->SetDomaine($SauvegardeDomaine);
                            $SauvegardeDimension->SetDimensionOriginal($Dimension);
                            $em->persist($SauvegardeDimension);
                            $em->flush();
                            foreach ($Dimension->GetCriteres() as $Critere) 
                            {
                                $SauvegardeCritere= new SauvegardeCritere();
                                $SauvegardeCritere->setReferentiel($Critere->GetReferentiel());
                                $SauvegardeCritere->setNote($Critere->GetNote());
                                $SauvegardeCritere->SetDimension($SauvegardeDimension);
                                $SauvegardeCritere->SetCritereOriginal($Critere);
                                $em->persist($SauvegardeCritere);
                                $em->flush();   
                                foreach ($Critere->GetQuestions() as $Question) 
                                {
                                    
                                        $SauvegardeQuestion= new SauvegardeQuestion();
                                        $SauvegardeQuestion->setReferentiel($Question->GetReferentiel());
                                        $SauvegardeQuestion->setReponse($Question->GetReponse());
                                        $SauvegardeQuestion->SetCritere($SauvegardeCritere);
                                        $SauvegardeQuestion->SetQuestionOriginal($Question);
                                        $em->persist($SauvegardeQuestion);
                                        $em->flush();   
                                }
                                
                           }
                           
                           

                       }
                        
           }
        $this->addFlash('success', "La sauvegarde à bien été effectué");
    }
    
    
    /**
     * Sauvegardes
     *
     * @Route("/sauvegardes/delete/{id}", name="pericles3_sauvegardes_delete")
     * @Method("GET")
     */
     public function DeleteSauvegardeAction(Sauvegarde $Sauvegarde)
     {
        $em = $this->getDoctrine()->getManager();
        foreach ($Sauvegarde->getDomaines() as $Domaine) 
        {
            foreach ($Domaine->getDimensions() as $Dimension) 
            {
                foreach ($Dimension->getCriteres() as $Critere) 
                {
                    foreach ($Critere->getQuestions() as $Question) 
                    {
                        $em->remove($Question);
                        $em->flush();
                    }
                    $em->remove($Critere);
                    $em->flush();
                }
                $em->remove($Dimension);
                $em->flush();
            }
            $em->remove($Domaine);
            $em->flush();
        }
        $em->remove($Sauvegarde);
        $em->flush();
        $this->addFlash('success', "La sauvegarde à bien été supprimée");
        return $this->redirectToRoute('pericles3_sauvegardes');  
     }
    
    
    
    
    
    
    

    function base64_to_jpeg($base64_string, $output_file) {
        $ifp = fopen($output_file, "wb");
        $data = explode(',', $base64_string);
        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);
        return $output_file;
    }
    
    
}
