<?php

namespace Pericles3Bundle\Controller\BackOffice;


use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pericles3Bundle\Entity\ReferentielPublic;
use Pericles3Bundle\Entity\Referentiel;
use Pericles3Bundle\Entity\ReferentielExterne;

                    


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Dompdf\Dompdf;


    
    
/** 
 * ReferentielPublic controller.
 *
 * @Route("/backoffice/referentielpublic")
 */
class ReferentielPublicController extends AdminController
{
    
     
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/ref_externe", name="referentiel_externe_list")
     * @Method("GET")
     */
    public function showRefExterneListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $referentielsExterne = $em->getRepository('Pericles3Bundle:ReferentielExterne')->findAll();
        return $this->render('BackOffice/referentielpublic/show_ref_externe_list.html.twig', array(
            'referentielsExterne' => $referentielsExterne 
        ));
    }
    
     
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/ref_externe_{ref_externe}", name="referentiel_externe_show")
     * @Method("GET")
     */
    public function showRefExterneAction(ReferentielPublic $referentielPublic,$ref_externe )
    {
        
        $em = $this->getDoctrine()->getManager();
        $referentielExterne = $em->getRepository('Pericles3Bundle:ReferentielExterne')->findOneById($ref_externe);
        if ($this->GetUser()->hasReferentielPublic($referentielPublic) && $referentielPublic->getFini()<>1) $edit=true;
        else $edit=false;

        
        return $this->render('BackOffice/referentielpublic/show_ref_externe.html.twig', array(
            'referentielExterne' => $referentielExterne ,
            'referentielPublic' => $referentielPublic,
            'edit' => $edit,
        ));
        
    }
     
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/search_form", name="pericles3_backoffice_formsearch_referentielexterneniv1")
     * @Method("GET")
     */
    public function searchReferentielExterneN1Action(Request $request)
    {
        $q = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('Pericles3Bundle:ReferentielExterneNiv1')->findLike($q);
        return $this->render('BackOffice/referentielpublic/search_result.html.twig', ['results' => $results]);
    }
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/search_get_{id}", name="pericles3_backoffice_formsearch_get_referentielexterneniv1")
     * @Method("GET")
     */
    public function getReferentielExterneN1Action($id = null)
    {
        $author = $this->getDoctrine()->getRepository('Pericles3Bundle:ReferentielExterneNiv1')->find($id);
        return new Response($author->getNom());
    }

    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/ref_externe_{ref_externe}/detail", name="referentiel_externe_detail_show")
     * @Method("GET")
     */
    public function showRefExterneDetailAction(ReferentielPublic $referentielPublic,$ref_externe )
    {
        
        $em = $this->getDoctrine()->getManager();
        $referentielExterne = $em->getRepository('Pericles3Bundle:ReferentielExterne')->findOneById($ref_externe);
        if ($this->GetUser()->hasReferentielPublic($referentielPublic) && $referentielPublic->getFini()<>1) $edit=true;
        else $edit=false;
        return $this->render('BackOffice/referentielpublic/show_ref_externe_detail.html.twig', array(
            'referentielExterne' => $referentielExterne ,
            'referentielPublic' => $referentielPublic,
            'edit' => $edit,
        ));
    }
     
    
    
    /**
     * Displays a form to edit an existing ReferentielPublic entity.
     *
     * @Route("/genere_cache", name="referentielpublic_genere_cache")
     * @Method("GET")
     */
    public function genereCacheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $referentielsPublic = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findVeryAll();
        foreach ($referentielsPublic  as $referentielPublic )
        {
                $referentielPublic->GenereCache();
                $em->persist($referentielPublic);
                $em->flush();
                $this->addFlash('success', "Le référentiel " .$referentielPublic." a été généré");
        }
        return $this->redirectToRoute('referentielpublic_index');        
    }
    
    
    
    /**
     * Lists all ReferentielPublic entities.
     *
     * @Route("/", name="referentielpublic_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $criteresAnnexeManquant = $em->getRepository('Pericles3Bundle:Referentiel')->FindCriteresRefExterneManquant();
        $referentielPublics = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findAll();
        $referentielsDesuet = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findDesuet();
        
        return $this->render('BackOffice/referentielpublic/index.html.twig', array(
            'criteresAnnexeManquant' => $criteresAnnexeManquant,
            'referentielPublics' => $referentielPublics,
            'referentielsDesuet' => $referentielsDesuet,
        ));
    }
    
        
    /**
     * Lists all ReferentielPublic entities.
     *
     * @Route("/suivi", name="referentielpublic_suivi_index")
     * @Method("GET")
     */
    public function indexSuiviAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_REFERENTIEL_WATCH'))
        {
            $referentielPublics = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findVeryAll();
        }
        else
        {
            $referentielPublics = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findAllUser($this->GetUser());
        }
        return $this->render('BackOffice/referentielpublic/index_suivi.html.twig', array(
            'referentielPublics' => $referentielPublics,
        ));
    }
    

    
        
    /**
     * Lists all ReferentielPublic entities.
     *
     * @Route("/arborescence", name="referentielpublic_suivi_arborescence")
     * @Method("GET")
     */
    public function indexArborescenceAction()
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_REFERENTIEL_WATCH'))
        {
            $referentielPublics = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findSansParents();
            return $this->render('BackOffice/referentielpublic/arborescence.html.twig', array(
                'referentielPublics' => $referentielPublics,
            ));
        }
    }
    
        
    /**
     * Lists all ReferentielPublic entities.
     *
     * @Route("/arborescence/versionning", name="referentielpublic_suivi_arborescence_versionning")
     * @Method("GET")
     */
    public function indexArborescenceVAction()
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_REFERENTIEL_WATCH'))
        {
            $referentielPublics = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findSansParents();
            $referentielPublics0 = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findAlpha();
                    
            return $this->render('BackOffice/referentielpublic/arborescence_versionning.html.twig', array(
                'referentielPublics' => $referentielPublics,
                'referentielPublics0' => $referentielPublics0 
            ));
        }
    }
    

    
    
    
    
    
 

    /**
     * Creates a new ReferentielPublic entity.
     *
     * @Route("/new", name="referentielpublic_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $referentielPublic = new ReferentielPublic();
        $form = $this->createForm('Pericles3Bundle\Form\ReferentielPublicType', $referentielPublic);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($request->get('referentiel_source')>'')
            {
                $referentielPublicSource = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($request->get('referentiel_source'));
            }
            
            

            
            
            $referentielPublic->setFini(0);
            
            $mega_admin=$em->getRepository('Pericles3Bundle:User')->findOneById(1);
            if ($request->get('referentiel_source')>'')
            {
                $referentielPublic->setCopie(1);
                $referentielPublic->setVersionning($referentielPublicSource->GetVersionning()+1);
                
            }
            else 
            {
                $referentielPublic->setCopie(0);
                $referentielPublic->setVersionning(0);
            }
            
            
            $referentielPublic->addUser($mega_admin);
            $mega_admin->addReferentielsPublic($referentielPublic);

            
            $em->persist($referentielPublic);
            $em->flush();

           
               
            if ($request->get('referentiel_source')>'')
            {
//            $referentielPublicSource = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($request->get('referentiel_source'));
                $referentielPublic->setSourceParent($referentielPublicSource);
                $em->persist($referentielPublic);
                $em->flush();
                
                if ($request->get('avec_critereannexe'))
                {
                    if ($referentielPublic->GetReferentielExterne() != $referentielPublicSource->GetReferentielExterne())
                    {
                        $this->addFlash('error', "Le referentiel externe a été modifé : nouveau : ".$referentielPublicSource->GetReferentielExterne());
                    }
                    $referentielPublic->setReferentielExterne($referentielPublicSource->GetReferentielExterne());
                }
            
                if($request->get('avec_biblio'))
                {
                    foreach ($referentielPublicSource->getBibliothequesAncreai() as $BiblioAncreai)
                    {
                        $BiblioAncreai->addReferentielPublic($referentielPublic);
                        $referentielPublic->addBibliothequesAncreai($BiblioAncreai);
                    }
                    $em->persist($referentielPublic);
                    $em->flush();
                }
            
            
                
                foreach ($referentielPublicSource->getReferentielDomaines() as $refDomaineSource)
                {
                    $referentielDom=new Referentiel();
                    $referentielDom->setNom($refDomaineSource->GetNom());
                    $referentielDom->setSourceParent($refDomaineSource);
                    
                    $referentielDom->setReferentielPublic($referentielPublic);
                    $referentielDom->setNomCourt($refDomaineSource->GetNomCourt());
                    $referentielDom->setNonConcerne(false);
                    $referentielDom->setVerifie(false);
                    $referentielDom->setOrdre($refDomaineSource->GetOrdre());
                    $referentielDom->setTypeReferentiel($refDomaineSource->getTypeReferentiel());
                    $em->persist($referentielDom);
                    foreach ($refDomaineSource->getChildren() as $refDimensionDource) 
                    {
                        $referentielDim=new Referentiel();
                        $referentielDim->setSourceParent($refDimensionDource);
                        $referentielDim->setNom($refDimensionDource->GetNom());
                        $referentielDim->setReferentielPublic($referentielPublic);
                        $referentielDim->setParent($referentielDom);
                        $referentielDim->setNomCourt($refDimensionDource->GetNomCourt());
                        $referentielDim->setNonConcerne(false);
                        $referentielDim->setVerifie(false);
                        $referentielDim->setOrdre($refDimensionDource->GetOrdre());
                        $referentielDim->setTypeReferentiel($refDimensionDource->getTypeReferentiel());
                        $em->persist($referentielDim);
                        foreach ($refDimensionDource->getChildren() as $refCritereSource) 
                        {
                            $referentielCrit=new Referentiel();
                            $referentielCrit->setSourceParent($refCritereSource);
                            $referentielCrit->setNom($refCritereSource->GetNom());
                            $referentielCrit->setReferentielPublic($referentielPublic);
                            $referentielCrit->setParent($referentielDim);
                            $referentielCrit->setNomCourt($refCritereSource->GetNomCourt());
                            $referentielCrit->setNonConcerne(false);
                            $referentielCrit->setVerifie(false);
                            /*
                            $referentielCrit->setRBPP($refCritereSource->GetRBPP());
                            $referentielCrit->setRbpppComment($refCritereSource->GetRbpppComment());
                            */      
                            
                            
                            $referentielCrit->setOrdre($refCritereSource->GetOrdre());
                            $referentielCrit->setTypeReferentiel($refCritereSource->getTypeReferentiel());
    
                            foreach ($refCritereSource->getChildren() as $refQuestSource) 
                            {
                               
                                $referentielQuest=new Referentiel();
                                $referentielQuest->setSourceParent($refQuestSource);
                                $referentielQuest->setNom($refQuestSource->GetNom());
                                $referentielQuest->setReferentielPublic($referentielPublic);
                                $referentielQuest->setParent($referentielCrit);
                                $referentielQuest->setNomCourt($refQuestSource->GetNomCourt());
                                $referentielQuest->setNonConcerne(false);
                                $referentielQuest->setVerifie(false);

                                if($request->get('avec_ouinon'))
                                {
                                    $referentielQuest->setReponseOui($refQuestSource->GetReponseOui());
                                    $referentielQuest->setReponseNon($refQuestSource->GetReponseNon());
                                }
                                else
                                {
                                }

                                $referentielQuest->setOrdre($refQuestSource->GetOrdre());
                                $referentielQuest->setTypeReferentiel($refQuestSource->getTypeReferentiel());
                                $em->persist($referentielQuest);
                            }
                            
                            if($request->get('avec_rbpp'))
                            {
                                $referentielCrit->setRBPP($refCritereSource->GetRBPP());
                            }
                            if ($request->get('avec_critereannexe'))
                            {
                                $referentielCrit->setReferentielExterneNiv1($refCritereSource->getReferentielExterneNiv1());
                            }


                            $em->persist($referentielCrit);
                        }
                    }
                    $em->flush();
                }
                
                $referentielPublic->GenereCache();
                $em->persist($referentielPublic);
                $em->flush();
                 
                
                

                
            }
            else
            {
                for ($i=1;$i<=5;$i++)
                {
                    $nom="Domaine ".$i;
                    $referentiel=new Referentiel();
                    $referentiel->setNom($nom);
                    $referentiel->setReferentielPublic($referentielPublic);
                    $referentiel->setNomCourt($nom);
                    $referentiel->setVerifie(false);
                    $referentiel->setNonConcerne(false);
                    $referentiel->setOrdre($i);
                    $referentiel->setTypeReferentiel($em->getRepository('Pericles3Bundle:TypeReferentiel')->findOneById(1));
                    $em->persist($referentiel);
                    $em->flush();
                }
                // ??????????????????????????
                $referentielPublic->addReferentiel($referentiel);
            }
            
            
       
                    
                
            return $this->redirectToRoute('referentielpublic_show', array('id' => $referentielPublic->getId()));
        }
        
        $referentielPublicsSource = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findNonDesuet();


        return $this->render('BackOffice/referentielpublic/new.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'referentielPublicsSource' => $referentielPublicsSource,
            'form' => $form->createView(),
        ));
    }
    
    
    
    
    /**
     * Deletes a ObjectifOperationnel entity.
     *
     * @Route("/delete_referentiel/{id}", name="delete_referentiel")
     * @Method({"GET", "POST"})
     */
    public function deleteGetAction(referentielPublic $referentielPublic)
    {
        $em = $this->getDoctrine()->getManager();
        if ($referentielPublic->GetNbEtablissements()>0)
        {
            $this->addFlash('error', "Le referentiel n'à pas été supprimé car il est lié à ".$referentielPublic->GetNbEtablissements()." établissements");
        }
        else
        {
            foreach ($referentielPublic->getReferentielDomaines() as $refDomaine)
            {
                foreach ($refDomaine->getChildren() as $refDimension)
                {
                    foreach ($refDimension->getChildren() as $refCritere)
                    {
                        foreach ($refCritere->getChildren() as $refQuestions)
                        {
                            $em->remove($refQuestions);
                        }
                        $em->remove($refCritere);
                    }
                    $em->remove($refDimension);
                }
                $em->remove($refDomaine);
            }
            $em->remove($referentielPublic);
            $em->flush();
            $this->addFlash('success', "Le referentiel à été supprimé");
            return $this->redirectToRoute('referentielpublic_index');
        }
        return $this->redirectToRoute('referentielpublic_show', array('id' => $referentielPublic->getId()));

    }
                    
    
    
    
    
       
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/control", name="referentielpublic_show_control")
     * @Method("GET")
     */
    public function showControlAction()
    {
        $em = $this->getDoctrine()->getManager();
//        $referentielNodes = $em->getRepository('Pericles3Bundle:Referentiel')->FindByPublic($referentielPublic);
        $referentielNodes = $em->getRepository('Pericles3Bundle:Referentiel')->FindSaufQuestions();
        return $this->render('BackOffice/referentielpublic/show_control.html.twig', array(
            'referentielNodes' => $referentielNodes,
        ));
    }
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}", name="referentielpublic_show")
     * @Method("GET")
     */
    public function showAction(ReferentielPublic $referentielPublic)
    {
 
        return $this->render('BackOffice/referentielpublic/show.html.twig', array(
            'referentielPublic' => $referentielPublic,
        ));
    }
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/add_user", name="referentielpublic_add_user")
     * @Method("GET")
     */
    public function showAddUserAction(ReferentielPublic $referentielPublic)
    {
 
        return $this->render('BackOffice/referentielpublic/show_add_user.html.twig', array(
            'referentielPublic' => $referentielPublic,
        ));
    }
    
    
    
    
 
    
    
    
    
   
    
    
        

    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/{id}/affiche", name="pericles3_ref_vierge")
     * @Method("GET")
     */
    public function testdevrefViergeAction(ReferentielPublic $referentielPublic)
    {
        return($this->render('Synthese/export/synthese_referentiel_vierge_rbpp.html.twig', 
                array(
                'ReferentielPublic'=>$referentielPublic,
                'typeExport'=>  strtoupper("PDF")
                )));    
    } 
                    

    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/{id}/genere_referentiel", name="pericles3_genere_referentiel")
     * @Method({"GET", "POST"})
     */
    public function generepericles3_genere_referentielAction(ReferentielPublic $referentielPublic,Request $request)
    {
        $relative="/referentiels/";
        $UploadDirectory = UPLOAD_DIR.$relative;
        $this->get('Utils')->FolderUploadExisteCreate($relative);
                    
        $synthese_extension=$request->get('extension');
        $view = $this->renderView('Synthese/export/synthese_referentiel_vierge_rbpp.html.twig', 
                        array(
                        'ReferentielPublic'=>$referentielPublic,
                        'typeExport'=>  strtoupper($synthese_extension)
                        ));
        
        
        if ($synthese_extension=="pdf")
        {
            
//            include($this->get('kernel')->getRootDir().'/../vendor/dompdf/dompdf_config.inc.php');
            //############### GENERATION PDF ###############
            $dompdf = new DOMPDF();
            $dompdf->load_html($view);
            $dompdf->render();
            $file_to_save=$UploadDirectory.$referentielPublic->getId().".pdf";
            
            file_put_contents($file_to_save, $dompdf->output());
            return new JsonResponse(true);
        }
        elseif ($synthese_extension=="doc")
        {
       //     include($this->get('kernel')->getRootDir().'/../vendor/htmltodoc/html_to_doc.inc.php');
            $view=$this->get('HtmlToMht')->HtmlToMht($view);
            $fp = fopen($UploadDirectory.$referentielPublic->getId().".doc", 'w');
            fwrite($fp, $view);
            return new JsonResponse(true);
        }
        else { return new JsonResponse(false); }
        
        
        }
    
    
    
    
      
    
    
    /**
     * Exportation du fichier
     *
     * @Route("/{id}/export/file/{synthese_extension}", name="arsene_refpublic_vierge_getpath")
     * @Method({"GET", "POST"})
     */
    public function exportrefPublicAction(ReferentielPublic $referentielPublic, $synthese_extension){
        $uploadPath = $this->get('kernel')->getRootDir().'/../web/upload/';
        
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
        $relative="/referentiels/";
        $UploadDirectory = UPLOAD_DIR.$relative;
        
        $uploadPath = WEB_DIR.'/upload/';
        $file=$UploadDirectory.$referentielPublic->getId().".".$synthese_extension;

        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
//        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-type', $contenttype);
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$referentielPublic.'  - '.date("Y-m-d").'.'.$synthese_extension. '";');
        $response->headers->set('Content-length', filesize($file));
        $response->sendHeaders();
        $response->setContent(file_get_contents($file));
        return $response;
    }

    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/criteres", name="referentiel_criteres_show")
     * @Method("GET")
     */
    public function showCriteres(ReferentielPublic $referentielPublic)
    {
        return $this->render('BackOffice/referentielpublic/show_critere.html.twig', array(
            'referentielPublic' => $referentielPublic,
        ));
    }
     
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/criteres_rbpp", name="referentiel_criteres_rbpp_show")
     * @Method("GET")
     */
    public function showCriteresRbpp(ReferentielPublic $referentielPublic)
    {
        return $this->render('BackOffice/referentielpublic/show_critere_rbpp.html.twig', array(
            'referentielPublic' => $referentielPublic,
        ));
    }
    
     
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/yesnon", name="referentiel_ouinon_show")
     * @Method("GET")
     */
    public function OuiNonCriteres(ReferentielPublic $referentielPublic)
    {
        return $this->render('BackOffice/referentielpublic/show_ouinon.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'all' => false,
        ));
    }
    
    
    
     
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/yesnon/all", name="referentiel_ouinon_all_show")
     * @Method("GET")
     */
    public function OuiNonAllCriteres(ReferentielPublic $referentielPublic)
    {
        return $this->render('BackOffice/referentielpublic/show_ouinon.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'all' => true,
        ));
    }
    
    
    
    
    
    
    
   
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}/link_source", name="referentielpublic_show_link_source_move")
     * @Method({"GET", "POST"})
     */
    public function LinkNodeSourceAction(ReferentielPublic $referentielPublic,$id_node,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $referentielNode = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);
        
        if ($request->getMethod() == 'POST') 
        {
//            $this->addFlash('success', "neworder : ".$neworder);
        }
        /*
         *         {% if node.parent %}
                {% for node_to_source in node.parent.sourceParent.children %}
         */
        
        if ($referentielNode->GetTypeReferentiel()->GetId()==1) // domaine
        {
            $nodes_without_child=$referentielPublic->getSourceParent()->getReferentielDomaines();;
        }
        elseif ($referentielNode->GetTypeReferentiel()->GetId()==2) // domaine
        {
            $nodes_without_child = $em->getRepository('Pericles3Bundle:Referentiel')->FindDimensionsByPublic($referentielPublic->getSourceParent());
        }
        elseif ($referentielNode->GetTypeReferentiel()->GetId()==3) // domaine
        {
            $nodes_without_child = $em->getRepository('Pericles3Bundle:Referentiel')->FindCritereByPublic($referentielPublic->getSourceParent());
        }
        elseif ($referentielNode->GetTypeReferentiel()->GetId()==4) // domaine
        {
            $nodes_without_child = $em->getRepository('Pericles3Bundle:Referentiel')->FindQuestionsByPublic($referentielPublic->getSourceParent());
        }
        $next = $em->getRepository('Pericles3Bundle:Referentiel')->FindNextByType($referentielNode);
        $next_orphan = $em->getRepository('Pericles3Bundle:Referentiel')->FindNextByTypeOrphan($referentielNode);
        
        return $this->render('BackOffice/referentielpublic/show_node_link_source.html.twig', array(
            'referentielPublic' => $referentielPublic, 'referentielPublic' => $referentielPublic, 'node' => $referentielNode,"nodes_without_child"=>$nodes_without_child
                ,"next_orphan"=>$next_orphan
                ,"next"=>$next
        ));
    }
    
    

    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}/link_source/{id_node_to_link}", name="referentielpublic_show_link_source_commit")
     * @Method({"GET", "POST"})
     */
    public function LinkSourceCommitAction(ReferentielPublic $referentielPublic,$id_node,$id_node_to_link,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $referentielNode = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);
        $referentielNodeToLink = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node_to_link);
        
        $referentielNode->setSourceParent($referentielNodeToLink);
        $em->persist($referentielNode);
        $em->flush();
        

        $this->addFlash('success', "Linkage ");
        $next_orphan = $em->getRepository('Pericles3Bundle:Referentiel')->FindNextByType($referentielNode);

        return $this->redirectToRoute('referentielpublic_show_link_source_move', array('id' => $referentielPublic->getId(),'id_node'=>$next_orphan->GetId()));
    }
    

    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}/unlink_source", name="referentielpublic_show_unlink_source_commit")
     * @Method({"GET", "POST"})
     */
    public function UnLinkSourceCommitAction(ReferentielPublic $referentielPublic,$id_node)
    {
        $em = $this->getDoctrine()->getManager();
        
        $referentielNode = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);
        $referentielNode->setSourceParent(null);
        $em->persist($referentielNode);
        $em->flush();
        $this->addFlash('success', "Liens rompu ");
        return $this->redirectToRoute('referentielpublic_show_link_source_move', array('id' => $referentielPublic->getId(),'id_node'=>$id_node));
    }
    
    
    
      /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/comparaison", name="referentielpublic_comparaison")
     * @Method({"GET", "POST"})
     */
    public function ComparaisonAction(ReferentielPublic $referentielPublic)
    {
        $em = $this->getDoctrine()->getManager();
        
        $refsParentSansEnfant = $em->getRepository('Pericles3Bundle:Referentiel')->findParentSansEnfant($referentielPublic->getSourceParent(),$referentielPublic);

        $FirstOrphan=$em->getRepository('Pericles3Bundle:Referentiel')->FindFirstOrphan($referentielPublic);
        
        return $this->render('BackOffice/referentielpublic/comparaison_enfant_pere.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'refsParentSansEnfant' => $refsParentSansEnfant,
            'firstOrphan' => $FirstOrphan
        ));
    }
    
    
      /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/comparaison/autolink", name="referentielpublic_comparaison_autolink")
     * @Method({"GET", "POST"})
     */
    public function ComparaisonAutoLinkAction(ReferentielPublic $referentielPublic)
    {
        $referentielPublicParent=$referentielPublic->getSourceParent();
        
        $em = $this->getDoctrine()->getManager();
        $this->addFlash('success', "Linkageauto ");
        $refs=$em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielCibleSansSource($referentielPublic);
        
        $i=0;
        
        foreach ($refs as $ref)
        {
//          $this->addFlash('success', "Recherche pour : ".$ref);
            $parent= $em->getRepository('Pericles3Bundle:Referentiel')->FindByLibellePublicType($ref->GetNom(),$referentielPublicParent,$ref->getTypeReferentiel()->GetId());
            if ($parent)
            {
                $i++;
                $ref->SetSourceParent($parent);
                $em->persist($ref);
                $em->flush();
            }
            else
            {
//                $this->addFlash('error', $ref." --->Pas trouvé ");
            }
        }
        
            $this->addFlash('success', $i." mis  à jour sur ".count($refs));
        
        return $this->redirectToRoute('referentielpublic_comparaison', array('id' => $referentielPublic->getId()));
    }
    
    
    

        
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/comparaison_questions", name="referentielpublic_comparaison_questions")
     * @Method({"GET", "POST"})
     */
    public function ComparaisonQuestionsAction(ReferentielPublic $referentielPublic)
    {
        $em = $this->getDoctrine()->getManager();
        
//        $criteres = $em->getRepository('Pericles3Bundle:Referentiel')->FindCritereByPublic($referentielPublic);
        $refsParentSansEnfant = $em->getRepository('Pericles3Bundle:Referentiel')->findParentSansEnfant($referentielPublic->getSourceParent(),$referentielPublic,4);
        $refsOrphelins= $em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielCibleSansSource($referentielPublic,4);

        return $this->render('BackOffice/referentielpublic/comparaison_enfant_pere_questions.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'refsParentSansEnfant' => $refsParentSansEnfant,
            'refsOrphelins' => $refsOrphelins,
        ));
    }
    
    
      /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/comparaison_criteres", name="referentielpublic_comparaison_criteres")
     * @Method({"GET", "POST"})
     */
    public function ComparaisonCriteresAction(ReferentielPublic $referentielPublic)
    {
        $em = $this->getDoctrine()->getManager();
        
        $refsParentSansEnfant = $em->getRepository('Pericles3Bundle:Referentiel')->findParentSansEnfant($referentielPublic->getSourceParent(),$referentielPublic,3);
        $refsOrphelins= $em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielCibleSansSource($referentielPublic,3);

        $criteres_parents = $em->getRepository('Pericles3Bundle:Referentiel')->FindCritereByPublic($referentielPublic->getSourceParent());

        return $this->render('BackOffice/referentielpublic/comparaison_enfant_pere_criteres.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'refsParentSansEnfant' => $refsParentSansEnfant,
            'refsOrphelins' => $refsOrphelins,
            'criteresParents' => $criteres_parents,
        ));
    }
    
    
    
     /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/comparaison_critere/{critere}", name="referentielpublic_comparaison_critere_one")
     */
    public function ComparaisonCritereAction(ReferentielPublic $referentielPublic,  Referentiel $critere)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('BackOffice/referentielpublic/comparaison_enfant_pere_critere_one.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'RefCritere' => $critere,
        ));
    }
    
    
    

    

    
    
    
       
      /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}/move_critere", name="referentielpublic_show_node_move_critere")
     * @Method({"GET", "POST"})
     */
    public function moveNodeCritereQuestionAction(ReferentielPublic $referentielPublic,$id_node,Request $request)
    {
        // attention ! ne doit pas etre aplelé si les évaluations ont commencées 
        $em = $this->getDoctrine()->getManager();
        $referentielCritere = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);
        $dimensionOrigineParent=$referentielCritere->getParent();
        $dimensions=$em->getRepository('Pericles3Bundle:Referentiel')->FindDimensionsByPublic($referentielPublic);
        
        if ($request->getMethod() == 'POST') 
        {
            $id_dimension=$request->get('dimension');
            $dimension_ref_cible=$em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_dimension);
            $neworder=$dimension_ref_cible->GetNbChildren()+1;
            $referentielCritere->SetParent($dimension_ref_cible);
            $referentielCritere->SetOrdre($neworder);
            $em->persist($referentielCritere);
            $em->flush();
            $this->addFlash('success', "Le critere <b>".$referentielCritere."</b> à été déplacer en position ".$neworder." de la dimension <b>".$dimension_ref_cible."</b>");
            $this->reorder($dimensionOrigineParent);
            return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielPublic->getId(),'id_node'=>$dimension_ref_cible->GetId()  ));
                    
        }
            $this->reorder($dimensionOrigineParent);
        
        return $this->render('BackOffice/referentielpublic/show_node_deplace_critere.html.twig', array(
            'referentielPublic' => $referentielPublic, 'referentielPublic' => $referentielPublic, 'node' => $referentielCritere, 'dimensions'=>$dimensions
        ));
    }
    

    
    
    
    
    
    
    
    
    
   
      /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}/move_question", name="referentielpublic_show_node_move_question")
     * @Method({"GET", "POST"})
     */
    public function moveNodeQuestionAction(ReferentielPublic $referentielPublic,$id_node,Request $request)
    {
        // attention ! en cascade dans les évaluation !!! 
        $em = $this->getDoctrine()->getManager();
        $referentielQuestion = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);
        $critereOrigineParent=$referentielQuestion->getParent();
        $criteres=$em->getRepository('Pericles3Bundle:Referentiel')->FindCritereByPublic($referentielPublic);
        if ($request->getMethod() == 'POST') 
        {
            $id_critere=$request->get('critere');
            $critere_ref_cible=$em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_critere);
            $neworder=$critere_ref_cible->GetNbChildren()+1;
            $referentielQuestion->SetParent($critere_ref_cible);
            $referentielQuestion->SetOrdre($neworder);
            $em->persist($referentielQuestion);
            $em->flush();
            foreach ($referentielQuestion->GetQuestions() as $question)
            {
                $question->setCritere($critere_ref_cible->getCritereEtablissement($question->GetEtablissement()));
                $em->persist($question);
//                $this->addFlash('success', "-----".$question->GetNumero()." : ".$question." : ".$question->GetEtablissement());
            }
            $em->flush();
            $this->addFlash('success', "Le l'élément d'apréciation  <b>".$referentielQuestion."</b> à été déplacer en position ".$neworder." dans le critère <b>".$critere_ref_cible."</b>");
            $this->reorder($critereOrigineParent);
            return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielPublic->getId(),'id_node'=>$critere_ref_cible->GetId()  ));
        }
        $this->reorder($critereOrigineParent);
        
        return $this->render('BackOffice/referentielpublic/show_node_deplace_question.html.twig', array(
            'referentielPublic' => $referentielPublic, 'referentielPublic' => $referentielPublic, 'node' => $referentielQuestion, 'criteres'=>$criteres
        ));
    }
    
    
    
    
    
    public function reorder(Referentiel $referentielNode)
    {
        $em = $this->getDoctrine()->getManager();
        if (! $referentielNode->GetChildrenOrdreOK())
        {
            $neworder=1;
            $children=$em->getRepository('Pericles3Bundle:Referentiel')->FindChildByOrder($referentielNode);
            foreach ($children as $child )
            {
                $this->addFlash('success', $child ." :  ".$child->GetOrdre()." -> ".$neworder);
                $child->SetOrdre($neworder);
                $em->persist($child);
                $em->flush();
                $neworder++;
            }
            return(true);
        }
    }
    
    
    
    
   
      /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}", name="referentielpublic_show_node")
     * @Method({"GET", "POST"})
     */
    public function showNodeAction(ReferentielPublic $referentielPublic,$id_node,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $referentielNode = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);

        $repositoryDomaineExterne = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineExterne');

        $id_referentiel_pricipal=$referentielNode->getReferentielPublic()->getId();

        if (! $referentielNode->GetChildrenOrdreOK())
        {
            $neworder=1;
            $children=$em->getRepository('Pericles3Bundle:Referentiel')->FindChildByOrder($referentielNode);
            foreach ($children as $child )
            {
                $this->addFlash('success', $child ." :  ".$child->GetOrdre()." -> ".$neworder);
                $child->SetOrdre($neworder);
                $em->persist($child);
                $em->flush();
                $neworder++;
            }
            $this->addFlash('error', "Il y a eut un probleme de d'ordre !!! Veuillez revérifier l'ordre !! ");
        }
         
        
        $sub_node = new Referentiel();
        if ($referentielPublic->getReferentielExterne())
        {
           $form = $this->createForm('Pericles3Bundle\Form\ReferentielType', 
                   $sub_node ,['id_referentiel_pricipal'=>$id_referentiel_pricipal,'rererentiel_type'=>$referentielNode->getTypereferentiel()->GetId()+1,'id_referentielExterne'=>$referentielPublic->getReferentielExterne()->getId()]);
        }
        else
        {
           $form = $this->createForm('Pericles3Bundle\Form\ReferentielType', $sub_node ,['id_referentiel_pricipal'=>$id_referentiel_pricipal,'rererentiel_type'=>$referentielNode->getTypereferentiel()->GetId()+1]);
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $newordre=$referentielNode->GetNbChildren()+1;
            $em = $this->getDoctrine()->getManager();
            $sub_node->setOrdre($newordre);
            $sub_node->setVerifie(true);
            $sub_node->setNonConcerne(true);
            $sub_node->setParent($referentielNode);
            $sub_node->setReferentielPublic($referentielPublic);
            $sub_node->SetTypeReferentiel($em->getRepository('Pericles3Bundle:TypeReferentiel')->findOneById($referentielNode->getTypereferentiel()->GetId()+1));
            $em->persist($sub_node);
            $em->flush();
            
            $referentielPublic->GenereCache();
            
            $em->persist($referentielPublic);
            $em->flush();
            
                switch ($sub_node->GetTypeReferentiel()->getId())
                {
                    case 1:
                        $this->addFlash('error', "Ajout de domaine non activé...");
                    case 2:
                            foreach ($referentielNode->GetDomaines() as $Domaine)
                            {
                                $dimension = new \Pericles3Bundle\Entity\Dimension();
                                $dimension->setDomaine($Domaine);
                                $dimension->setReferentiel($sub_node);
                                
                                $em->persist($dimension);
                                $em->flush();
                                $this->addFlash('success', "Ajouté pour : ".$Domaine->GetEtablissement());
                            }
                            break;
                    case 3:
                        foreach ($referentielNode->GetDimensions() as $Dimension)
                        {
                            $critere = new \Pericles3Bundle\Entity\Critere;
                            $critere->setDimension($Dimension);
                            $critere->setArevoir(3);
                            $critere->setReferentiel($sub_node);
                            $em->persist($critere);
                            $em->flush();
                            $this->addFlash('success', "Ajouté pour : ".$Dimension->GetEtablissement());
                            
                            if ($sub_node->getReferentielExterneNiv1())
                            {
                                $domaineExterne = $repositoryDomaineExterne->findOneByEtabExtrneN1($Dimension->GetEtablissement(),$sub_node->GetReferentielExterneNiv1());
                                $critere->setDomaineExterne($domaineExterne);
                                $this->addFlash('success', "domaineExterne ---> ".$domaineExterne);
                                $em->persist($domaineExterne);
                                $em->flush();
                            }
                        }
                        //$question->Set
                        $this->addFlash('success', "Critere ajouté ! ");
                            break;
                    case 4:
                      //  $RefsParent=$em->getRepository('Pericles3Bundle:Critere')->findOneByReferentielId($referentielNode->GetId());
                        foreach ($referentielNode->GetCriteres() as $Critere)
                        {
                            $question = new \Pericles3Bundle\Entity\Question;
                            $question->setCritere($Critere);
                            $question->setReferentiel($sub_node);
                            $Critere->SetARevoir(2);
                            $em->persist($question);
                            $em->flush();
                            $this->addFlash('success', "Ajouté pour : ".$Critere->GetEtablissement());
                        }
                        //$question->Set
                        $this->addFlash('success', "Element d'apréciation ajouté");
                        break;
                }
                    
            
            return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielPublic->getId(),'id_node'=>$id_node  ));
        }    
        if (( $this->GetUser()->hasReferentielPublic($referentielPublic) or   $this->get('security.authorization_checker')->isGranted('ROLE_MEGA_ADMIN'))  && (! $referentielPublic->GetNbEtablissements() &&  $referentielPublic->getFini()<>1)) $edit=true;
        else $edit=false;
        
        
        return $this->render('BackOffice/referentielpublic/show_node.html.twig', array(
            'referentielPublic' => $referentielPublic, 'referentielPublic' => $referentielPublic, 'node' => $referentielNode,'form'=>$form->createView(), 'edit'=>$edit
        ));
    }
    
   /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/check_node", name="referentielpublic_check_node")
     * @Method({"GET", "POST"})
     */
    public function checkNodeAction(Referentiel $referentielNode,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $referentielNode->setVerifie(true);
        $em->persist($referentielNode);
        $em->flush();
        $this->addFlash('success', "L'élément est considéré comme vérifié");
        return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielNode->getReferentielPublic()->getId(),'id_node'=>$referentielNode->getId()));
    }
    
    
   /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/check_node_recursif", name="referentielpublic_check_recursif_node")
     * @Method({"GET", "POST"})
     */
    public function checkRecursiNodeAction(Referentiel $referentielNode,Request $request)
    {
        $this->CheckRecursiveNode($referentielNode);
        $this->addFlash('success', "L'élément et ses sous éléments sont considérés comme vérifiés");
        return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielNode->getReferentielPublic()->getId(),'id_node'=>$referentielNode->getId()));
    }
    
    
    public function CheckRecursiveNode(Referentiel $referentielNode)
    {
        $em = $this->getDoctrine()->getManager();
        $referentielNode->setVerifie(true);
        $em->persist($referentielNode);
        $em->flush();
        if ($referentielNode->GetNbChildren())
        {
            foreach ($referentielNode->getChildren() as  $referentielNodeChild)
            {
                $this->CheckRecursiveNode($referentielNodeChild);
            }
        }
    }

    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/edit_node_externe", name="referentielpublic_edit_node_externe")
     * @Method({"GET", "POST"})
     */
    public function editNodeExterneAction(Referentiel $referentielNode,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id_referentiel_pricipal=$referentielNode->getReferentielPublic()->getId();

        $id_referentielExterne=$referentielNode->getReferentielPublic()->getReferentielExterne()->getId();
        $options=['rererentiel_type'=>$referentielNode->getTypereferentiel()->GetId(),"id_referentiel_pricipal"=>$id_referentiel_pricipal,'id_referentielExterne'=>$id_referentielExterne,"averifier"=>false];            
        $form = $this->createForm('Pericles3Bundle\Form\ReferentielType', $referentielNode ,$options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($referentielNode);
            $em->flush();
            return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielNode->getReferentielPublic()->getId(),'id_node'=>$referentielNode->getId()));
        }    
        return $this->render('BackOffice/referentielpublic/edit_node.html.twig', array(
            'rererentiel_type' => $referentielNode->getTypeReferentiel(), 
            'referentielPublic' => $referentielNode->getReferentielPublic(), 'node' => $referentielNode,'form'=>$form->createView()
        ));
    }
     
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/edit_node", name="referentielpublic_edit_node")
     * @Method({"GET", "POST"})
     */
    public function editNodeAction(Referentiel $referentielNode,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id_referentiel_pricipal=$referentielNode->getReferentielPublic()->getId();

        if ($referentielNode->getReferentielPublic()->getReferentielExterne())
        {
            if ($referentielNode->getReferentielPublic()->GetNbEtablissements())
            {
                 $id_referentielExterne=0;     
            }
            else
            {
                $id_referentielExterne=$referentielNode->getReferentielPublic()->getReferentielExterne()->getId();
            }
        }
        else 
        {
             $id_referentielExterne=0;     
        }
        if ($referentielNode->getReferentielPublic()->getCopie())
        {
           $averifier=true;
        }
        else
        {
            $averifier=false;
        }
        
        $options=['rererentiel_type'=>$referentielNode->getTypereferentiel()->GetId(),"id_referentiel_pricipal"=>$id_referentiel_pricipal,'id_referentielExterne'=>$id_referentielExterne,"averifier"=>$averifier];            
        
        $form = $this->createForm('Pericles3Bundle\Form\ReferentielType', $referentielNode ,$options);
            
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em->persist($referentielNode);
            $em->flush();
            return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielNode->getReferentielPublic()->getId(),'id_node'=>$referentielNode->getId()));
        }    
        
        return $this->render('BackOffice/referentielpublic/edit_node.html.twig', array(
            'rererentiel_type' => $referentielNode->getTypeReferentiel(), 
            'referentielPublic' => $referentielNode->getReferentielPublic(), 'node' => $referentielNode,'form'=>$form->createView()
        ));
    }
     
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}/delete", name="referentielpublic_delete_node")
     * @Method({"GET", "POST"})
     */
    public function deleteNodeAction(ReferentielPublic $referentielPublic,$id_node,Request $request)
    {        
        $em = $this->getDoctrine()->getManager();
        $referentielNode = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);
        $id_parent=$referentielNode->GetParent()->GetId();
        
        foreach ($referentielNode->GetParent()->getChildren() as $child)
        {
            if ($referentielNode->GetOrdre()<$child->GetOrdre())
            {
                $child->setOrdre($child->getOrdre()-1);
                $em->persist($child);
            }
        }
        $em->flush();
        

        if ($referentielNode->getReferentielPublic()==$referentielPublic && $referentielPublic->getFini()==0  )
        {
            
            foreach ($referentielNode->GetEvals() as $eval)
            {
                $em->remove($eval);
            }
            
            foreach ($referentielNode->getSourceChildren() as $cible)
            {
                $referentielNode->removeSourceChild($cible);
                $cible->setSourceParent(null);
                $em->persist($child);
            }
            
            
            
            
            
                $em->remove($referentielNode);
                $em->flush();
                $this->addFlash('success', "L'élément à bien été supprimé");
        }
        
        $referentielPublic->setNbCriteresCache($referentielPublic->getNbCriteres());
        $referentielPublic->setNbQuestionsCache($referentielPublic->getNbQuestions());

        $em->persist($referentielPublic);
        $em->flush();
        $this->addFlash('success', "Regen du cache : OK ");
        
        $this->addFlash('success', " getNbCriteres : ".$referentielPublic->getNbCriteres());
        $this->addFlash('success', " getNbCriteresCache : ".$referentielPublic->getNbCriteresCache());
        

        return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielPublic->getId(),'id_node'=>$id_parent  ));
    }
   
     
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}/up", name="referentielpublic_up_node")
     * @Method({"GET", "POST"})
     */
    public function upNodeAction(ReferentielPublic $referentielPublic,$id_node,Request $request)
    {        
        $em = $this->getDoctrine()->getManager();
        $referentielNode = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);
        $id_parent=$referentielNode->GetParent()->GetId();
        
        foreach ($referentielNode->GetParent()->getChildren() as $child)
        {
            if ($child->GetOrdre()==$referentielNode->GetOrdre()-1)
            {
                $child->setOrdre($child->getOrdre()+1);
                $em->persist($child);
            }
        }
        $referentielNode->setOrdre($referentielNode->getOrdre()-1);
        $em->flush();
        return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielPublic->getId(),'id_node'=>$id_parent  ));
    }
   
    
       
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/node/{id_node}/donw", name="referentielpublic_down_node")
     * @Method({"GET", "POST"})
     */
    public function downNodeAction(ReferentielPublic $referentielPublic,$id_node,Request $request)
    {        
        $em = $this->getDoctrine()->getManager();
        $referentielNode = $em->getRepository('Pericles3Bundle:Referentiel')->findOneById($id_node);
        $id_parent=$referentielNode->GetParent()->GetId();
        
        foreach ($referentielNode->GetParent()->getChildren() as $child)
        {
            if ($child->GetOrdre()==$referentielNode->GetOrdre()+1)
            {
                $child->setOrdre($child->getOrdre()-1);
                $em->persist($child);
            }
        }
        $referentielNode->setOrdre($referentielNode->getOrdre()+1);
        $em->flush();
        return $this->redirectToRoute('referentielpublic_show_node', array('id' => $referentielPublic->getId(),'id_node'=>$id_parent  ));
    }
   


    
    
    

    /**
     * Displays a form to edit an existing ReferentielPublic entity.
     *
     * @Route("/{id}/edit", name="referentielpublic_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ReferentielPublic $referentielPublic)
    {
        
        $editForm = $this->createForm('Pericles3Bundle\Form\ReferentielPublicType', $referentielPublic,['edit'=>true]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($referentielPublic);
            $em->flush();

            return $this->redirectToRoute('referentielpublic_show', array('id' => $referentielPublic->getId()));
        }

        return $this->render('BackOffice/referentielpublic/edit.html.twig', array(
            'referentielPublic' => $referentielPublic,
            'edit_form' => $editForm->createView()
        ));
    }


    function nettoieReferentiel()
    {
        $em = $this->getEm();
        $Refsall= $em->getRepository('Pericles3Bundle:Referentiel')->findAll();
//        $Refsall= $em->getRepository('Pericles3Bundle:Referentiel')->FindTest();
        foreach ($Refsall as $ref)
        {
            $nom=$ref->GetNom();
            $nom = htmlentities($nom, null);
            $nom = str_replace(" ?", "&nbsp;?", $nom);
            $nom= str_replace("'", "’", $nom);
            $nom = html_entity_decode($nom);
            
            if ($ref->GetNom()!=$nom)
            {
                $ref->SetNom($nom);
                $em->persist($ref);
            }
            /*
            if ( strpos($nom, " ?"))
            {
                $nom= str_replace("'", "’", $nom);
                $nom= str_replace("&nbsp;", " ", $nom);
            }
             * 
             */
            $this->Output('<info>'.$nom.'</info>');
        }
        $em->flush();
        $this->Output('<info>OK...</info>');
    }
    
    
    
    
  
}

