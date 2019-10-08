<?php

namespace Pericles3Bundle\Controller\FrontOffice;
 

use Pericles3Bundle\Entity\Bibliotheque;
use Pericles3Bundle\Entity\Domaine;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\BibliothequeAncreai;
use Pericles3Bundle\Entity\Preuve;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Pericles3Bundle\Entity\BibliothequeAncreaiTypeSource;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * DomaineObjectifStrategique controller.
 *
 * @Route("/bibliotheque")
 */
class BibliothequeController extends Controller
{
    
    private function getRepository()
    {
        return($this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Bibliotheque'));
    }
    
    /**
     * Index Bibliotheque
     *
     * @Route("/", name="pericles3_bibliotheque")
     * @Method("GET")
     */
    public function indexAction()
    {
        $repositoryBibliothequeAncreai = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:BibliothequeAncreai');
        $repositoryPreuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve');
        $repositoryBibliotheques = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Bibliotheque');
         
        $Etablissement=$this->getUser()->GetEtablissement();
        $bibliothequesGestionnaire=null;
        $bibliothequesEtablissement=null;
        $bibliotheques=null;
        
        if ($Etablissement)
        {
                // ANCREAI 
               $t=$this->getUser()->GetEtablissement()->GetReferentielPublic();
               $bibliothequesAncreai = $repositoryBibliothequeAncreai->findByReferentielPublic($t,3);
                $bibliotheques=$repositoryBibliotheques->findLastByEtablissement($Etablissement);
               // PREUVES
                
               $preuves= $repositoryPreuves->findBy(['etablissement' =>$Etablissement], ['dateCreate' => 'DESC'],3 );
        }
        else
        { 
            $bibliothequesAncreai = $repositoryBibliothequeAncreai->findByUserGestionnaire($this->getUser(),5);
            $bibliothequesGestionnaire = $repositoryBibliotheques->FindBiblioGestionnaireByGestionnaire($this->getUser()->getGestionnaire(),5);
            $bibliothequesEtablissement =  $repositoryBibliotheques->FindBiblioEtablissementsByGestionnaire($this->getUser(),5);
            $preuves= $repositoryPreuves->findByGestionnaire($this->getUser(),3 );
        }
        
        
       

        return $this->render('Bibliotheque/index.html.twig', 
                array( 'ancreai_bibliotheques' => $bibliothequesAncreai
                , 'preuves' => $preuves
                , 'bibliotheques' => $bibliotheques
                , 'bibliothequesGestionnaire' => $bibliothequesGestionnaire
                , 'bibliotheques_etablissement' =>  $bibliothequesEtablissement
                ));
    }
    
     
    
    /**
     * Bibliotheque de preuves
     *
     * @Route("/preuves/etablissement_{id}", name="pericles3_bibliotheque_preuves_etablissement")
     * @Method("GET")
     */
    public function bibliothequePreuvesEtablissementAction(Etablissement $etablissement)
    {
        $repositoryPreuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve');
    	$preuves= $repositoryPreuves->findByEtablissement($etablissement);
        
    	return $this->render('Bibliotheque/preuves.html.twig', 
                array('preuves' => $preuves, 'titre' => 'Preuves ', 'sous_titre'=>$etablissement , 'etablissement' => $etablissement));
    }
    
    
    
    
    
    /**
     * Recherche
     *
     * @Route("/search", name="pericles3_bibliotheque_search")
     * @Method({"GET", "POST"})
    */
    public function SearchAction(Request $request)
    {
        $results=  new \Doctrine\Common\Collections\ArrayCollection();
        $occurence=$request->get('occurence');
        
        $etablissement=$this->getUser()->GetEtablissement();
        $gestionnaire=$this->getUser()->Getgestionnaire();
        if ($occurence && $etablissement)
        {
            $results['Bibliothèque Arsène']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:BibliothequeAncreai')->FindByReferentielOccurence($etablissement->GetReferentielPublic(),$occurence);
            $results['Bibliothèque établissement']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Bibliotheque')->FindByEtablissementOccurence($etablissement,$occurence);
            $results['Preuves']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve')->FindByEtablissementOccurence($etablissement,$occurence);
        }
        return $this->render('Bibliotheque/search.html.twig', ['occurence'=>$occurence, 'results'=>$results ]);
    }
    
    
    
    
    
      
    
     
      
    /**
     * Bibliotheque  show
     *
     * @Route("/bibliotheque_{id}", name="pericles3_bibliotheque_show")
     * @Method("GET")
     */
    public function bibliothequeShowAction(Bibliotheque $Bibliotheque)
    {
    	return $this->render('Bibliotheque/bibliotheque_show.html.twig', array('bibliotheque' => $Bibliotheque));
    }
 
      
    /**
     * Bibliotheque  show
     *
     * @Route("/bibliotheque_{id}/uptogestionnaire", name="pericles3_bibliotheque_uptogestionnaire")
     * @Method("GET")
     */
    public function bibliothequeUpAction(Bibliotheque $Bibliotheque)
    {
        $em = $this->getDoctrine()->getManager();
        $etablissement=$Bibliotheque->getEtablissement();
        if ($this->GetUser()->GetGestionnaire())
        {
            $oldPath=$Bibliotheque->getRelativPath();
            
            $Bibliotheque->setGestionnaire($this->GetUser()->GetGestionnaire());
            $Bibliotheque->setEtablissement(null);
            
            if ($Bibliotheque->getFichier())
            {
                  if (file_exists(WEB_DIR.'/upload/'.$oldPath))
                  {
//                      $this->addFlash('success', WEB_DIR.'/upload/'.$oldPath);
//                      $this->addFlash('success', WEB_DIR.'/upload/'.$Bibliotheque->getRelativPath());
                      if (file_exists(WEB_DIR.'/upload/'.$Bibliotheque->getRelativPath()))
                      {
                          $this->addFlash('error', "Le fichier existait déja dans la bibliothèque gestionnaire");
                      }
                      else
                      {
                          
                           if (!file_exists( WEB_DIR.'/upload/'.$Bibliotheque->getRelativPathFolder()))
                           {
                               mkdir(WEB_DIR.'/upload/'.$Bibliotheque->getRelativPathFolder(), 0777, true);
                           }
    //                       copy(WEB_DIR.'/upload/'.$oldPath,  WEB_DIR.'/upload/'.$Bibliotheque->getRelativPath()); 
      //                     unlink(WEB_DIR.'/upload/'.$oldPath); 
                            rename(WEB_DIR.'/upload/'.$oldPath,  WEB_DIR.'/upload/'.$Bibliotheque->getRelativPath()); 
                      }
                        $etablissement->SetSizeTotalFileUploadCache($this->container->get('Utils')->sizeTotalFileUpload($etablissement));
                        $em->persist($etablissement);
                        $em->flush();
                  }
                else 
                {
                      $this->addFlash('error', WEB_DIR.'/upload/'.$oldPath. " n'existe pas...");
                      return $this->redirectToRoute('pericles3_bibliotheque_show', array('id' => $Bibliotheque->getId()));
                }
            }
            
            $this->addFlash('success', "L'élément de la bibliothèque est passé dans la bibliothèque gestionnaire");
        }
        $em->persist($Bibliotheque);
        $em->flush();

        return $this->redirectToRoute('pericles3_bibliotheque_show', array('id' => $Bibliotheque->getId()));
    }
 

    /**
     * Bibliotheque  show
     *
     * @Route("/bibliotheque_{id}/downttoetablissement/{etablissement}", name="pericles3_bibliotheque_downttoetablissement")
     * @Method("GET")
     */
    public function bibliothequeDownAction(Bibliotheque $Bibliotheque, Etablissement $etablissement)
    {
        
        $em = $this->getDoctrine()->getManager();
        $this->addFlash('success', "L'élément de la bibliothèque (".$Bibliotheque.") est passé de la bibliothèque gestionnaire à ".$etablissement);
        
        $oldPath=$Bibliotheque->getRelativPath();
            
        $Bibliotheque->setGestionnaire(null);
        $Bibliotheque->setEtablissement($etablissement);

        
          
            if ($Bibliotheque->getFichier())
            {
                  if (file_exists(WEB_DIR.'/upload/'.$oldPath))
                  {
//                      $this->addFlash('success', WEB_DIR.'/upload/'.$oldPath);
//                      $this->addFlash('success', WEB_DIR.'/upload/'.$Bibliotheque->getRelativPath());
                      if (file_exists(WEB_DIR.'/upload/'.$Bibliotheque->getRelativPath()))
                      {
                          $this->addFlash('error', "Le fichier existait déja dans la bibliothèque gestionnaire");
                      }
                      else
                      {
                           if (!file_exists( WEB_DIR.'/upload/'.$Bibliotheque->getRelativPathFolder()))
                           {
                               mkdir(WEB_DIR.'/upload/'.$Bibliotheque->getRelativPathFolder(), 0777, true);
                           }
//                            copy(WEB_DIR.'/upload/'.$oldPath,  WEB_DIR.'/upload/'.$Bibliotheque->getRelativPath()); 
//                            unlink(WEB_DIR.'/upload/'.$oldPath); 
                            rename(WEB_DIR.'/upload/'.$oldPath,  WEB_DIR.'/upload/'.$Bibliotheque->getRelativPath()); 
                      }
                        $etablissement->SetSizeTotalFileUploadCache($this->container->get('Utils')->sizeTotalFileUpload($etablissement));
                        $em->persist($etablissement);
                        $em->flush();
                  }
                else 
                {
                      $this->addFlash('error', WEB_DIR.'/upload/'.$oldPath. " n'existe pas...");
                }
            }
            
            
        $em->persist($Bibliotheque);
        $em->flush();

        return $this->redirectToRoute('pericles3_bibliotheque_show', array('id' => $Bibliotheque->getId()));
 
    }
 
                  
    
    
    /**
     * Bibliotheque de preuves show
     *
     * @Route("/preuve/{id}", name="pericles3_bibliotheque_preuve_show")
     * @Method("GET")
     */
    public function bibliothequePreuveShowAction(Preuve $Preuve)
    {
    	return $this->render('Bibliotheque/preuves_show.html.twig', array('preuve' => $Preuve));
    }
 
    
     
    
    
      
    /**
     * Bibliotheque de preuves
     *
     * @Route("/preuves/domaine_{id}", name="pericles3_bibliotheque_preuves_domaine")
     * @Method("GET")
     */
    public function bibliothequePreuvesDomaineAction(Domaine $Domaine)
    {
        $repositoryPreuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve');
    	$preuves= $repositoryPreuves->findByDomaine($Domaine);
    	return $this->render('Bibliotheque/preuves.html.twig', 
                array('preuves' => $preuves, 'titre' => 'Preuves ', 'domaine' => $Domaine));
    }
 
    
      
    /**
     * Bibliotheque de preuves
     *
     * @Route("/preuves/{type_preuve}", name="pericles3_bibliotheque_preuves_source")
     * @Method("GET")
     */
    public function bibliothequePreuvesSourceAction($type_preuve)
    {
        $repositoryPreuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve');
    	$Etablissement=$this->getUser()->GetEtablissement();
        if ($Etablissement)
        {
            $preuves= $repositoryPreuves->findBy(array('etablissement' =>$Etablissement,'type_preuve'=>$type_preuve), array('dateCreate' => 'DESC'));
        }
        else
        {
            $preuves= $repositoryPreuves->findByGestionnaireType($this->getUser(),$type_preuve,0);
        }
        
    	return $this->render('Bibliotheque/preuves.html.twig', 
                array('preuves' => $preuves, 'titre' => 'Preuves ', 'type_preuve' => $type_preuve));
    }
 
     
    
    /**
     * Bibliotheque de preuves
     *
     * @Route("/preuves", name="pericles3_bibliotheque_preuves")
     * @Method("GET")
     */
    public function bibliothequePreuvesAction()
    {
        $repositoryPreuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve');


        $Etablissement=$this->getUser()->GetEtablissement();
        if ($Etablissement)
        {
            $preuves= $repositoryPreuves->findBy(array('etablissement' =>$Etablissement), array('dateCreate' => 'DESC'));
        }
        else 
        {
            $preuves= $repositoryPreuves->findByGestionnaire($this->getUser(),0);
        }
    	return $this->render('Bibliotheque/preuves.html.twig', 
                array('preuves' => $preuves, 'titre' => 'Preuves ', 'type_preuves' => 'all'));
    }
    
    
     /**
     * Bibliotheque de preuves
     *
     * @Route("/preuves_tobiblio", name="pericles3_bibliotheque_preuves_biblio")
     * @Method("GET")
     */
    public function bibliothequePreuvesToBilioAction()
    {
        $etablissement=$this->getUser()->GetEtablissement();
        return($this->PreuveBiblioListe($etablissement));
    }
    
    
    function PreuveBiblioListe($Etablissement)
    {
                $repositoryPreuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve');
        $repositoryBiblio = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Bibliotheque');
        
        
        $fichiers_in_biblio=  new \Doctrine\Common\Collections\ArrayCollection();
        $fichiers_notin_biblio=  new \Doctrine\Common\Collections\ArrayCollection();
        
        if ($Etablissement)
        {
            $fichiers= $repositoryPreuves->findFichiersEtablissement($Etablissement);
            foreach ($fichiers as $fichier)
            { 
                
                $biblioEtab=$repositoryBiblio->FindBiblioByFile($Etablissement,$fichier["fichier"]);
                //var_dump($biblio);
                //die();
                if ($Etablissement->getGestionnaire())
                {
                    $biblioGestionnaire=$repositoryBiblio->FindBiblioGestionnaireByFile($Etablissement->getGestionnaire(),$fichier["fichier"]);;
                }
                else
                {
                    $biblioGestionnaire=null;
                }
                
                if ($biblioEtab)
                {
                    $fichier['biblio']=$biblioEtab;
                    $fichiers_in_biblio->Add($fichier);
                }
                elseif ($biblioGestionnaire)
                {
                    $fichier['biblio']=$biblioGestionnaire;
                    $fichiers_in_biblio->Add($fichier);
                }
                else
                {
                    $fichiers_notin_biblio->Add($fichier);
                }
            }
            
        }
        
    	return $this->render('Bibliotheque/preuves_tobiblio.html.twig', array('etablissement' => $Etablissement,'fichiers_notin_biblio' => $fichiers_notin_biblio,'fichiers_in_biblio' => $fichiers_in_biblio));
    }
    
    
     /**
     * Bibliotheque de preuves
     *
     * @Route("/preuves_tobiblio/etablissement_{id}", name="pericles3_bibliotheque_preuves_biblio_etab")
     * @Method("GET")
     */
    public function bibliothequePreuvesToBilioEtabAction(Etablissement $etablissement)
    {
        return($this->PreuveBiblioListe($etablissement));
    }
    
    
     /**
     * Bibliotheque de preuves
     *
     * @Route("/biblio_{id}/etab_{etablissement}/preuves_tobiblio/linktoexistant/{filename}", name="pericles3_bibliotheque_preuves_linktoexistant")
     * @Method({"GET", "POST"})
     */
    public function bibliothequePreuvesToExistBilioAction(Bibliotheque $biblio, Etablissement $etablissement ,$filename,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPreuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve');
        
        $preuves=$repositoryPreuves->findFichiersByNameEtablissement($filename,$etablissement);
              
        
        foreach ($preuves as $preuve)
        {
            $preuve->setBibliotheque($biblio);
            $preuve->setFichier("");
            $em->persist($preuve);
            $em->flush();
        }
        
        
        $oldfilepath=WEB_DIR."/upload/".$etablissement->GetUploadFolderPath()."/preuves/".$filename;
        $folder_trash=WEB_DIR.'/upload/Trash/'.$etablissement->GetUploadFolderPath()."/preuves/";
        
        
            if (file_exists($oldfilepath))
            {
                if (! file_exists($folder_trash)) 
                {
                    mkdir($folder_trash, 0777, true);
                }
                copy($oldfilepath,  $folder_trash."/".$filename);
                unlink($oldfilepath);
            }
            else
            {
                $this->addFlash('debug', "Le fichier ".$oldfilepath." n'existait plus");
            }

        $this->addFlash('success', "Les preuves ont été associées à la bibliothèque");
        return $this->redirectToRoute('pericles3_bibliotheque_show', array('id' => $biblio->getId()));
        
        
//        return $this->redirectToRoute('pericles3_bibliotheque_preuves_biblio');
 
    }
    
    
    
      
     /**
     * Bibliotheque de preuves
     *
     * @Route("/etab_{id}/preuves_tobiblio/change/{filename}", name="pericles3_bibliotheque_preuves_biblio_change")
     * @Method({"GET", "POST"})
     */
    public function bibliothequePreuvesToChangeBilioAction(Etablissement $etablissement,$filename,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPreuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve');

         
            $preuves=$repositoryPreuves->findFichiersByNameEtablissement($filename,$etablissement);
               
            $folder=WEB_DIR."/upload/".$etablissement->GetUploadFolderPath()."/";
            
            if (file_exists($folder."bibliotheque/".$filename))
            {
                $this->addFlash('error', "Le fichier ".$filename." existe déja dans la bibliotheque...");
                return $this->redirectToRoute('pericles3_bibliotheque_preuves_biblio');
            }
            else
            { 
                if (!file_exists($folder."bibliotheque"))
                {
                    mkdir($folder."bibliotheque", 0777, true);
                }
                rename($folder."preuves/".$filename, $folder."bibliotheque/".$filename);
                $biblio = new Bibliotheque();
                $biblio->setEtablissement($etablissement);
                $biblio->setFichier($filename);
                $biblio->setMessage($request->get('titre'));
                $biblio->setDateUpdate(new \DateTime());
                $biblio->setUser($this->GetUser());
                $biblio->setTypeMessage("fichier");
                $biblio->setThematique( $request->get('thematique'));
                
                $em->persist($biblio);
                $em->flush();
 
                foreach ($preuves as $preuve)
                {
                    $preuve->setBibliotheque($biblio);
                    $preuve->setFichier("");
                    $em->persist($preuve);
                    $em->flush();
                }
                $this->addFlash('success', "La bibliotheque a été créee");
                return $this->redirectToRoute('pericles3_bibliotheque_show', array('id' => $biblio->getId()));
    }
}
    
    
    
    
    
    
    

    /**
     * Bibliotheque etablissement
     *
     * @Route("/etablissement", name="pericles3_bibliotheque_etablissement")
     * @Method("GET")
     */
    public function bibliothequeEtablissementAction()
    {
        $Etablissement=$this->getUser()->GetEtablissement();
        $repositoryBibliotheque = $this->getRepository();
        
        
        if ($Etablissement)
        {
        	$bibliotheques = $repositoryBibliotheque->findBy(array('etablissement' =>$Etablissement), array('dateUpdate' => 'DESC'));
                return $this->render('Bibliotheque/liste.html.twig', array('bibliotheques' => $bibliotheques, 'titre' => 'Etablissement : '.$Etablissement, 'type_bibliotheque' => 'etablissement'));
        }
        else
        {
        	$bibliotheques = $repositoryBibliotheque->FindBiblioEtablissementsByGestionnaire($this->getUser());
                return $this->render('Bibliotheque/liste_biblio_etab_gestionnaire.html.twig', array('bibliotheques' => $bibliotheques));
        }
        
    }
    
    
    /**
     * Bibliotheque etablissement
     *
     * @Route("/etablissement_{id}", name="pericles3_bibliotheque_etablissement_id")
     * @Method("GET")
     */
    public function bibliothequeEtablissementByIDAction(Etablissement $etablissement)
    {
    	$repositoryBibliotheque = $this->getRepository();
    	$bibliotheques = $repositoryBibliotheque->findBy(array('etablissement' =>$etablissement), array('dateUpdate' => 'DESC'));
    	return $this->render('Bibliotheque/liste.html.twig', 
                array('bibliotheques' => $bibliotheques,
                    'titre' => 'Etablissement : '.$etablissement, 
                    'etablissement' => $etablissement, 
                    'type_bibliotheque' => 'etablissement'));
    }
    
    

       /**
     * Bibliotheque etablissement
     *
     * @Route("/gestionnaire", name="pericles3_bibliotheque_gestionnaire")
     * @Method("GET")
     */
    public function bibliothequeGestionnaireAction()
    {
      

        $showAction=true;
        if ($this->getUser()->getEtablissement())
        {
                $Gestionnaire=$this->getUser()->getEtablissement()->getGestionnaire();
                $showAction=false;
        }
        elseif ($this->getUser()->getGestionnaire())
        {
                $Gestionnaire=$this->getUser()->getGestionnaire();
        }
        
        
        if ($Gestionnaire)
        {
            $repositoryBibliotheque = $this->getRepository();
            $bibliotheques = $repositoryBibliotheque->findBy(array('gestionnaire' =>$Gestionnaire), array('dateUpdate' => 'DESC'));
            return $this->render('Bibliotheque/liste.html.twig', array('bibliotheques' => $bibliotheques, 'titre' => 'Gestionnaire : '.$Gestionnaire, 'type_bibliotheque' => 'gestionnaire', "show_action"=>$showAction));
        }
        else
        {
            $this->addFlash('error', "Vous n'êtes pas Gestionnaire ou n'avez pas de gestionnaire");
            return $this->redirectToRoute('pericles3_bibliotheque');
        }
    }

    
    
    
    /**
     * Tableau Bibliotheque
     *
     * @Route("/tableau/{etablissement}", name="pericles3_bibliotheque_tableau")
     * @Method("GET")
     */
    public function tableauAction($etablissement)
    {
    	$repositoryBibliotheque = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Bibliotheque');
    	$bibliotheque = $repositoryBibliotheque->findByEtablissement($etablissement);
    	
    	return $this->render('Bibliotheque/tableau.html.twig', array('etablissement'=> $etablissement, 'bibliotheques' => $bibliotheque));
    }
    
    
    /**
     * Tableau Bibliotheque ANCREAI
     *
     * @Route("/ancreai/source_{id}", name="pericles3_bibliotheque_ancreai_source")
     * @Method("GET")
     */
    public function bibliothequeAncreaiSourceAction(BibliothequeAncreaiTypeSource $BibliothequeAncreaiTypeSource)
    {
    	$repositoryBibliotheque = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:BibliothequeAncreai');
        if ($this->getUser()->GetEtablissement())
        {
            $t=$this->getUser()->GetEtablissement()->GetReferentielPublic();
            $bibliotheques = $repositoryBibliotheque->findByReferentielPublicSource($t,$BibliothequeAncreaiTypeSource);
        }
        else
        {
            $bibliotheques = $repositoryBibliotheque->findBySourceGestionnaire($this->getUser(),$BibliothequeAncreaiTypeSource);
        }
    	return $this->render('Bibliotheque/ancreai.html.twig', array('ancreai_bibliotheques' => $bibliotheques,'source'=> $BibliothequeAncreaiTypeSource->GetTitre() ));
    }
    
    
    /**
     * Tableau Bibliotheque ANCREAI
     *
     * @Route("/ancreai/public_{id}", name="pericles3_bibliotheque_ancreai_public")
     * @Method("GET")
     */
    public function bibliothequeAncreaiPublicAction(\Pericles3Bundle\Entity\ReferentielPublic $ReferentielPublic)
    {
    	$repositoryBibliotheque = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:BibliothequeAncreai');
        if ($this->getUser()->GetGestionnaire())
        {
            $bibliotheques = $repositoryBibliotheque->findByReferentielPublic($ReferentielPublic);
        }
        else
        {
            $bibliotheques = null;
        }
    	return $this->render('Bibliotheque/ancreai.html.twig', array('ancreai_bibliotheques' => $bibliotheques,'source'=> $ReferentielPublic));
    }
    
    
    


    

    /**
     * Tableau Bibliotheque ANCREAI
     *
     * @Route("/ancreai", name="pericles3_bibliotheque_ancreai")
     * @Method("GET")
     */
    public function bibliothequeAncreaiAction()
    {
        $repositoryBibliotheque = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:BibliothequeAncreai');
        if ($this->getUser()->GetEtablissement())
        {
            $t=$this->getUser()->GetEtablissement()->GetReferentielPublic();
            $bibliotheques = $repositoryBibliotheque->findByReferentielPublic($t);
        }
        else
        {
            $bibliotheques = $repositoryBibliotheque->findByUserGestionnaire($this->getUser());
        }
    	return $this->render('Bibliotheque/ancreai.html.twig', array('ancreai_bibliotheques' => $bibliotheques));
    }

    /**
     * Tableau Bibliotheque ANCREAI
     *
     * @Route("/arsene/{id}", name="pericles3_bibliotheque_ancreai_show")
     * @Method("GET")
     */
    public function bibliothequeAncreaiShowAction(BibliothequeAncreai $BibliothequeAncreai)
    {
        $criteres=null;
        $repositoryCriteres = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        if ($this->GetUser()->GetEtablissement())
        {
            $criteres=$repositoryCriteres->findByRbppEtablissement($this->GetUser()->GetEtablissement(),$BibliothequeAncreai);
        }
        else
        {
        }
        return $this->render('Bibliotheque/ancreai_show.html.twig', array('bibliotheque' => $BibliothequeAncreai, 'criteres'=>$criteres));
        
    }



    private function FichierBibliotheque($bibliotheque,$etablissement)
    {
         
        
        $em = $this->getDoctrine()->getManager();
        $bibliotheque->setTypeMessage("fichier");
        $bibliotheque->setFichier(strtolower($_FILES['fichier']['name']));
        
        $this->get('Utils')->FolderUploadExisteCreate($bibliotheque->getRelativPathFolder());
        if (file_exists(WEB_DIR.'/upload/'.$bibliotheque->getRelativPath()))
        {
              $this->addFlash('warning', "Le fichier existe déjà");  
              return(false);
        }
        else 
        {
//            $this->addFlash('warning', "Le fichier ------------".WEB_DIR.'/upload/'.$bibliotheque->getRelativPath()."--------- existe pas");  
            move_uploaded_file($_FILES['fichier']['tmp_name'],  WEB_DIR.'/upload/'.$bibliotheque->getRelativPath()); 
            if ($etablissement)
            {
                $etablissement->SetSizeTotalFileUploadCache($this->container->get('Utils')->sizeTotalFileUpload($etablissement));
                $em->persist($etablissement);
                $em->flush();
            }
        }
    }
     
    
    private function editBibliotheque(Bibliotheque $bibliotheque , Request $request)
    {
        if ($this->validerDonnees($request->get('thematique'), $request->get('typeMessage'), $request->get('titre'), $request->get('href'), $request->get('lien'))) 
        {
            $em = $this->getDoctrine()->getManager();
                $bibliotheque->setUser($this->getUser());
                $bibliotheque->setDateUpdate(new \DateTime());
                $bibliotheque->setThematique($request->get('thematique'));
                $bibliotheque->setMessage($request->get('titre'));
                $bibliotheque->setHref($request->get('href'));
                        
                if (isset($_FILES["fichier"]))
                {
                    if ($_FILES["fichier"]["name"]<>"")
                    {
                        
                        $oldfilepath=WEB_DIR.'/upload/'.$bibliotheque->getRelativPath();
                        $folder_trash=WEB_DIR.'/upload/Trash/'.$bibliotheque->getRelativPathFolder();
                        if (file_exists($oldfilepath))
                        {
                            if (! file_exists($folder_trash)) 
                            {
                                mkdir($folder_trash, 0777, true);
                            }
                            copy($oldfilepath,  $folder_trash."/".$bibliotheque->getFichier());
                            unlink($oldfilepath);
                        }

                        $this->addFlash('debug', " ICI ");
                        if (! $this->FichierBibliotheque($bibliotheque,$bibliotheque->getEtablissement()))
                        {

                            $this->addFlash('debug', " La ");
                            return(false);
                        }
                    }
                    else
                    {
    //                        $this->FichierBibliotheque($bibliotheque,$bibliotheque->getEtablissement());
                        $bibliotheque->setFichier($request->get('ancien_fichier'));
                    }
                }
                else
                {
                    if ($request->get('href')) $bibliotheque->setTypeMessage("lien");
                    else $bibliotheque->setTypeMessage("texte");
                }
                $em->persist($bibliotheque);
                $em->flush();
                $this->addFlash('success', "La bibliothèque à bien été modifié ");
                return(true);
        }
    }
    
    
    private function addBibliotheque(Request $request,$sourcetype,$source)
    {
        if ($this->validerDonnees($request->get('thematique'), $request->get('typeMessage'), $request->get('titre'), $request->get('href'), $request->get('lien'))) 
        {
            $etablissement=null;
            $em = $this->getDoctrine()->getManager();

                $bibliotheque = new Bibliotheque();
                //$bibliotheque->setDomaineReferentiel($domaine);
                if ($sourcetype=="gestionnaire") 
                { 
                    $bibliotheque->setGestionnaire($source) ;
                }
                elseif ($sourcetype=="etablissement") 
                { 
                    $etablissement=$source;
                    $bibliotheque->setEtablissement($source) ;
                }
                else $this->addFlash('error', "le type de souce n'est pas defini : ".$sourcetype);
                
                $bibliotheque->setUser($this->getUser());
                $bibliotheque->setDateUpdate(new \DateTime());
                $bibliotheque->setThematique($request->get('thematique'));
                $bibliotheque->setMessage($request->get('titre'));
                $bibliotheque->setHref($request->get('href'));
                
                if (isset($_FILES["fichier"]))
                {
                    $this->FichierBibliotheque($bibliotheque,$etablissement);
                }
                else
                {

                    if ($request->get('href')) $bibliotheque->setTypeMessage("lien");
                    else  $bibliotheque->setTypeMessage("texte");
                }
                $em->persist($bibliotheque);
                $em->flush();
                $this->addFlash('success', "L'élément à bien été ajouté à la bibliothèque");
                return ($bibliotheque);
      
        }
    }

    
    /**
     * Create Bibliotheque
     *
     * @Route("/create/gestionnaire/{type}", name="pericles3_backoffice_bibliotheque_create_type")
     * @Method({"GET", "POST"})
     */
    public function createBackOfficeTypeAction($type,Request $request)
    { 
         $bibliotheque= new Bibliotheque();
    	return $this->render('BackOffice/Bibliotheque/create.html.twig', ['bibliotheque' => $bibliotheque,  'type' => $type]);
    }
    /**
     * Create Bibliotheque
     *
     * @Route("/create/gestionnaire_{id}", name="pericles3_backoffice_bibliotheque_gestionnaire_create")
     * @Method({"GET", "POST"})
     */
    public function createBackOfficeAction(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire, Request $request)
    {
        if ($request->getMethod() == 'POST') 
        {
            if ($this->addBibliotheque($request,"gestionnaire",$gestionnaire))
            {
                return $this->redirectToRoute('pericles3_bibliotheque_gestionnaire');
            }
        }
        else
        {
            $this->addFlash('debug','PAS POST');
        }
    	//return $this->render('BackOffice/Bibliotheque/create.html.twig');
    }
    
    

    /**
     * Create Bibliotheque
     *
     * @Route("/create/{type}", name="pericles3_bibliotheque_create")
     * @Method({"GET", "POST"})
     */
    public function createAction($type, Request $request)
    {
            return($this->createBibliotheque($this->getUser()->GetEtablissement(),$type, $request));
    }
    

    /**
     * Create Bibliotheque
     *
     * @Route("/create/etablissement_{id}/{type}", name="pericles3_bibliotheque_create_etablissement")
     * @Method({"GET", "POST"})
     */
    public function createBiblioEtabAction(Etablissement $Etablissement,$type, Request $request)
    {
            return($this->createBibliotheque($Etablissement,$type, $request));
    }
    

    private function createBibliotheque($Etablissement,$type, $request)
    {
        $bibliotheque= new Bibliotheque();
    	if ($request->getMethod() == 'POST') 
        {
            if ($type=="fichier")
            {
                if (! $request->files->get('fichier')) { $this->addFlash('alert',"Vous devez selectionner un fichier"); }   
                else { $fichier = $this->get('Utils')->CheckFile($request->files->get('fichier')); }
                if ($fichier['statut']) $this->addFlash('success',"Le fichier à bien été Uploadé");
                else {$this->addFlash('error',$fichier['message']);} 
            }
            if ($this->addBibliotheque($request,"etablissement",$Etablissement)) 
            {
                return  $this->redirectToRoute('pericles3_bibliotheque_etablissement');
                /*
                if ($this->getUser()->getEtablissement()) 
                else
                 * 
                 */
            }
    	}
    	return $this->render('Bibliotheque/create.html.twig', ['bibliotheque'=>$bibliotheque, 'type' => $type,'etablissement'=>$Etablissement]);
    }
        
    

    /**
     * update Bibliotheque
     * @Route("/update/{id}", name="pericles3_bibliotheque_update")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Bibliotheque $Bibliotheque, Request $request)
    {
        if ($request->getMethod() == 'POST') 
        {
            if ($this->editBibliotheque($Bibliotheque,$request))
            {
                $this->addFlash('success', "La preuve à bien été mis à jour");
                return $this->redirectToRoute('pericles3_bibliotheque_show', array('id' => $Bibliotheque->getId()));
            }
        }        
        
    	return $this->render('Bibliotheque/update.html.twig', array(
            'bibliotheque' => $Bibliotheque,
            'type' => $Bibliotheque->getTypeMessage()
        ));
    }
    
    /**
     * Create Bibliotheque
     *
     * @Route("/delete/{id}", name="pericles3_bibliotheque_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Bibliotheque $bibliotheque, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($bibliotheque->getTypeMessage()=='fichier')
        {
            $file=WEB_DIR.'/upload/'.$bibliotheque->getRelativPath();
            if (file_exists($file)) unlink($file);
        }
        $em->remove($bibliotheque);
        $em->flush();
        $this->addFlash('success', "L'élément à bien été supprimé.");
    	return $this->redirect($request->headers->get('referer'));
    }
    
    /**
     * Create Bibliotheque
     *
     * @Route("/delete_fichier_gestionnaire/{filename}", name="pericles3_bibliotheque_delete_fichier_gestionnaire")
     * @Method({"GET", "POST"})
     */
    public function deleteFichierGestionnaire($filename, Request $request)
    {
        $file=WEB_DIR.'/upload/'.$this->GetUser()->GetGestionnaire()->GetUploadFolderPath()."/bibliotheque/".$filename;
        $folder_trash=WEB_DIR.'/upload/Trash/'.$this->GetUser()->GetGestionnaire()->GetUploadFolderPath()."/bibliotheque/";
        if (file_exists($file))
        {
            if (! file_exists($folder_trash)) 
            {
                mkdir($folder_trash, 0777, true);
            }
            copy($file,  $folder_trash.$filename);
            unlink($file);
        }
        $this->addFlash('success', "Le fichier ".$filename." à bien été supprimé.");
        $this->addFlash('debug', "Le fichier ".$file." à bien été supprimé.");
    	return $this->redirect($request->headers->get('referer'));
    }
    
    
    
    
    
    
    
    private function validerDonnees($thematique, $typeMessage, $message, $href, $nomLien) {
    	$valide = true;
    	
    	if ($thematique == '') {
    		$valide = false;
    		$this->addFlash('error', 'Le champ "Thématique" est obligatoire');
    	}
    	if ($typeMessage == 'texte') {
    		if ($message == '') {
    			$valide = false;
    			$this->addFlash('error', 'Le champ "Titre" est obligatoire');
    		}
    	} elseif  ($typeMessage == 'lien'){
    		if ($message == '') {
    			$valide = false;
    			$this->addFlash('error', 'Le champ "Titre lien" est obligatoire');
    		}
    		if ($href == '') {
    			$valide = false;
    			$this->addFlash('error', 'Le champ "Lien" est obligatoire');
    		}
    	}
    	
    	return $valide;
    }
}