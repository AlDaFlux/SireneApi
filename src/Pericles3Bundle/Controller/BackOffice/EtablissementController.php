<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Pericles3Bundle\Controller\BackOffice\AdminController;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Pericles3Bundle\Entity\DemandeEtablissement;
use Pericles3Bundle\Entity\EtablissementCategory;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\Gestionnaire;
use Pericles3Bundle\Entity\User;
use Pericles3Bundle\Entity\ModeCotisation;
use Pericles3Bundle\Entity\PatchToDo;

use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;
use Symfony\Component\Validator\Constraints\Regex as RegexConstraint;


use Pericles3Bundle\Entity\Patch;

use Pericles3Bundle\Entity\Domaine;
use Pericles3Bundle\Entity\Dimension;
use Pericles3Bundle\Entity\Critere;
use Pericles3Bundle\Entity\Departement;
use Pericles3Bundle\Entity\Creai;


use Pericles3Bundle\Entity\Question;
use Pericles3Bundle\Entity\DomaineExterne;
use Pericles3Bundle\Entity\Finess;
use Pericles3Bundle\Entity\ReferentielPublic;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Dompdf\Dompdf;



/**
 * Gestionnaire controller.
 *
 * @Route("/backoffice/etablissement")
 */
class EtablissementController extends AdminController
{   
    
    
    
    
    
      /**
     * Lists all etablissements entities.
     *
     * @Route("/", name="pericles3_backoffice_etablissement")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $creai=$this->GetUser()->GetCreai();
            if ($creai)
            {
                $LastCreatedEtablissements = $em->getRepository('Pericles3Bundle:Etablissement')->findLastCreatedCreai($creai,16);
                
                $EtablissementsTests = $em->getRepository('Pericles3Bundle:Etablissement')->findNotReelsByCreai($creai);
            }
            else
            {
                $LastCreatedEtablissements = $em->getRepository('Pericles3Bundle:Etablissement')->findLastCreated(16);
                $EtablissementsTests=null;
            }
            return $this->render('BackOffice/Etablissement/accueil.html.twig',['EtablissementsTests'=>$EtablissementsTests, 'LastCreatedEtablissements'=>$LastCreatedEtablissements]);
        }
        else
        {
            return $this->render('BackOffice/Etablissement/index.html.twig',array("etablissements" => $this->GetEtablissements(),'index'=>true));
        }
            
    }
    
    

    /**
     * Recherche
     *
     * @Route("/search", name="pericles3_etablissement_search")
     * @Method({"GET", "POST"})
    */
    public function SearchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $occurence=$request->get('occurence');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            if ($this->getUser()->getAllEtablissement())
            {
                $creai=null;
            }
            else
            {
                $creai=$this->getUser()->GetCreai();
            }
        }

        if ($occurence) $etablissements=$em->getRepository('Pericles3Bundle:Etablissement')->FindByOccurence($occurence,$creai);
        else $etablissements=null;
        
        return $this->render('BackOffice/Etablissement/search.html.twig', ['occurence'=>$occurence, 'etablissements'=>$etablissements ]);
    }
    

    
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/all", name="pericles3_backoffice_etablissement_all")
     * @Method("GET")
     */
    public function indexAllAction()
    {
        $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
        $etablissements = $repositoryEtablisssment->findReels();
        return $this->render('BackOffice/Etablissement/index.html.twig',array("etablissements" => $etablissements,'index'=>true));
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/search_form", name="pericles3_backoffice_formsearch_etablissement")
     * @Method("GET")
     */
    public function searchEtablissementAction(Request $request)
    {
        $q = $request->query->get('term'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('Pericles3Bundle:Etablissement')->findLike($q);

        return $this->render('BackOffice/Etablissement/search_result.html.twig', ['results' => $results]);
    }
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/search_get_{id}", name="pericles3_backoffice_formsearch_get_etablissement")
     * @Method("GET")
     */
    public function getEtablissementAction($id = null)
    {
        $author = $this->getDoctrine()->getRepository('Pericles3Bundle:Etablissement')->find($id);
        return new Response($author->getNom());
    }
    
    
    
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/suivi", name="pericles3_backoffice_suivi_etablissement")
     * @Method("GET")
     */
    public function SuiviAction()
    {
        return $this->render('BackOffice/Etablissement/suivi.html.twig',array("etablissements" => $this->GetEtablissements(true)));
    }
    
     
    
   
    /**
     * Lists all etablissements entities.
     *
     * @Route("/suivi/public/{id}", name="pericles3_backoffice_suivi_etablissement_bybublic")
     * @Method("GET")
     */
    public function indexPublicSuiviAction(ReferentielPublic $ReferentielPublic)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/Etablissement/suivi.html.twig',array("ReferentielPublic"=> $ReferentielPublic, "etablissements" => $ReferentielPublic->getEtablissements()));
        }
    }
    
     
    
    
    
    
    private function GetEtablissements($reel=null)
    {
        if ($this->getUser()->IsAnEtablissement())
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
        
        $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE'))        
        {
            $etablissements = $this->getUser()->getEtablissements();
        }
        else
        {
            $creai=$this->getUser()->GetCreai();
            if ($creai)
            {
                $etablissements = $creai->GetEtablissements();
            }
            else
            {
                if ($reel) $etablissements = $repositoryEtablisssment->findReels();
                else $etablissements = $repositoryEtablisssment->findBy(array(), array('nom' => 'ASC'));
            }
        }
        return($etablissements);
    }
    
    
   
    /**
     * Lists all etablissements entities.
     *
     * @Route("/public/{id}", name="pericles3_backoffice_etablissement_bybublic")
     * @Method("GET")
     */
    public function indexPublicAction(ReferentielPublic $ReferentielPublic)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/Etablissement/index.html.twig',array("ReferentielPublic"=> $ReferentielPublic, "etablissements" => $ReferentielPublic->getEtablissements()));
        }
    }
    
       
    /**
     * Lists all etablissements entities.
     *
     * @Route("/cotisation/{id}", name="pericles3_backoffice_etablissement_bycotisation")
     * @Method("GET")
     */
    public function indexModecotisationAction(ModeCotisation $ModeCotisation)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findReelsParModeCotisation($ModeCotisation);
            return $this->render('BackOffice/Etablissement/index.html.twig',array("modeCotisation"=> $ModeCotisation, "etablissements" => $etablissements));
        }
    }
    
    
    /**
     * Lists etablissements by stockage.
     *
     * @Route("/stockage/{id}", name="pericles3_backoffice_etablissement_bystockage")
     * @Method("GET")
     */
    public function indexModeStockageAction(\Pericles3Bundle\Entity\StockageEtablissement $stockage)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findReelsParModeStockage($stockage);
            return $this->render('BackOffice/Etablissement/index.html.twig',array("modeCotisation"=> null, "etablissements" => $etablissements));
        }
    }
    
    
    
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/departement/{id}", name="pericles3_backoffice_etablissement_bydep")
     * @Method("GET")
     */
    public function indexDepAction(Departement $Departement)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/Etablissement/index.html.twig',array("Departement"=> $Departement, "etablissements" => $Departement->getEtablissements()));
        }
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/a_migrer", name="pericles3_backoffice_etablissement_a_migrer")
     * @Method("GET")
     */
    public function indexAMigrerAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $etablissements=$this->GetEtablissements();
            return $this->render('BackOffice/Etablissement/a_migrer.html.twig',array( "etablissements" =>$etablissements ));
        }
    }
    
    
    
      
    /**
     * Lists all etablissements entities.
     *
     * @Route("/reels", name="pericles3_backoffice_etablissement_reels")
     * @Method("GET")
     */
    public function indexReelAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findReels();
            return $this->render('BackOffice/Etablissement/index.html.twig',array("reel"=> true, "etablissements" => $etablissements));
        }
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/notreels", name="pericles3_backoffice_etablissement_notreels")
     * @Method("GET")
     */
    public function indexNotReelAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findNotReels();
            return $this->render('BackOffice/Etablissement/index.html.twig',array("reel"=> false, "etablissements" => $etablissements));
        }
    }
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/warning/reelssansfiness", name="pericles3_backoffice_etablissement_reelssansfiness")
     * @Method("GET")
     */
    public function indexReelSansFinessAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findReelsSansFiness();
            return $this->render('BackOffice/Etablissement/index.html.twig',array("reel"=> false, "etablissements" => $etablissements));
        }
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/warning/reelssansfactures", name="pericles3_backoffice_etablissement_reelssansfacture")
     * @Method("GET")
     */
    public function indexReelSansFactureAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_COMPTA_VIEW'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findReelsSansFacture();
            return $this->render('BackOffice/Etablissement/index.html.twig',array("reel"=> false, "etablissements" => $etablissements));
        }
    }
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/warning/factures_impayees", name="pericles3_backoffice_etablissement_factures_impayees")
     * @Method("GET")
     */
    public function indexWithFactureImpayeeAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_COMPTA_VIEW'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findWithFactureImpayee();
            return $this->render('BackOffice/Etablissement/index.html.twig',array("reel"=> false, "etablissements" => $etablissements));
        }
    }
    
    
       
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/warning/tests_with_facture", name="pericles3_backoffice_etablissement_tests_with_facture")
     * @Method("GET")
     */
    public function indexTestWithFactureAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_ETABLISSEMENT'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findTestAvecFature();
            return $this->render('BackOffice/Etablissement/index.html.twig',array("reel"=> false, "etablissements" => $etablissements));
        }
    }
    
    
    
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/warning/reelssansdepartements", name="backoffice_etablissement_sansdepartements")
     * @Method("GET")
     */
    public function indexReelSansDepartementsAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
            $etablissements = $repositoryEtablisssment->findReelsSansDepartement();
            return $this->render('BackOffice/Etablissement/index.html.twig',array("reel"=> false, "etablissements" => $etablissements));
        }
    }
    
    
    
    
    
    /**
     * Lists all etablissements entities.
     *
     * @Route("/categorie/{id}", name="pericles3_backoffice_etablissement_categorie")
     * @Method("GET")
     */
    public function indexCategorieAction(EtablissementCategory $EtablissementCategory)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/Etablissement/index.html.twig',array("EtablissementCategory"=> $EtablissementCategory, "etablissements" => $EtablissementCategory->GetEtablissements()));
        }
    }
    
    
    
    
    
    

    
    
    
    
          
    /**
     * Lists all etablissements entities.
     *
     * @Route("/creai/{id}", name="pericles3_backoffice_etablissement_bycreai")
     * @Method("GET")
     */
    public function indexCreaiAction(Creai $Creai)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/Etablissement/index.html.twig',array("Creai"=> $Creai, "etablissements" => $Creai->getEtablissements()));
        }
    }
    
    
      
    
    
    
    
    
       /**
     * Creates a new Gestionnaire entity.
     *
     * @Route("/new/byfiness", name="backoffice_etablissement_new_byfiness")
     * @Method({"GET", "POST"})
     */
    public function newByFinessAction(Request $request)
    {
        return ($this->addEtablissement($request));
    }
    
   
    
    
    
    /**
     * Ajoute un établissement au gestionnaire
     *
     * @Route("/gestionnaire_{id}/new", name="backoffice_gestionnaire_add_etablissement")
     * @Method({"GET", "POST"})
     */
    public function addEtablissementAction(Request $request, Gestionnaire $gestionnaire)
    {   
        return ($this->addEtablissement($request,$gestionnaire));
    }
    
    
    
    
    
    
    private function addEtablissement(Request $request,Gestionnaire $gestionnaire=NULL,$num_finess=null)
    {
        $em = $this->getDoctrine()->getManager();

                        
        $this->addFlash('error', "demande_id");
        $etablissement = new Etablissement();
        $form = $this->createForm('Pericles3Bundle\Form\EtablissementType', $etablissement,['code_finess' => false, 'gestionnaire' => false]);
        $form->handleRequest($request);
        if (! $num_finess ) $num_finess=$request->get('num_finess');
        $Finess=$this->GetFinessByCode($num_finess);
        if ($request->getMethod() == 'POST') {
            
          if ($form->isSubmitted()) 
            {
              if ($form->isValid())
              {
                if ($gestionnaire) $etablissement->setGestionnaire($gestionnaire);
                if ($request->get('etablissement')['finess'])
                {
                        $Finess=$this->GetFinessByCode($request->get('etablissement')['finess']);
                         if ($Finess) 
                         {
                             $etablissement->setFiness($Finess);
                             $Finess->SetEtablissement($etablissement);
                             $em->persist($Finess);
                         }
                }
                
                 
                
                
                $etablissement->setCreatedBy($this->GetUser());
                $etablissement->setCreatedDate(new \DateTime());
//                $etablissement->setStockageEtablissement($em->getRepository('Pericles3Bundle:StockageEtablissement')->findOneById(0));
                
                $em->persist($etablissement);
                $em->flush();
                $this->genereEtablissementData($etablissement);
                return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $etablissement->getId()));
              }
              else
              {
                  
                    
                     return $this->render('BackOffice/Etablissement/new.html.twig', array(
                            'etablissement' => $etablissement,
                            'form' => $form->createView(),
                             'gestionnaire'=> $gestionnaire
                        
                        ));
              }
            }
            else 
            { 
                if ($this->FinessExisteDispo($num_finess))
                {
                    
                        $demandeEtablissement=null;
                        $etablissement= $this->GetEtablissementByFiness($Finess);
                            if ($request->get('demande_id'))
                          {
                             $demandeEtablissement=$em->getRepository('Pericles3Bundle:DemandeEtablissement')->findOneById($request->get('demande_id'));
                             $etablissement->setModeCotisation($demandeEtablissement->GetModeCotisation());
                             $this->addFlash('success', "demande_id");
                          }
                          else
                          {
                             $this->addFlash('error', "demande_id");
                          }
                        $form = $this->createForm('Pericles3Bundle\Form\EtablissementType', $etablissement,['gestionnaire'=>false,"code_finess"=>$num_finess]);
                        return $this->render('BackOffice/Etablissement/new.html.twig', array(
                            'etablissement' => $etablissement,
                            'form' => $form->createView(),
                            'finess' => $Finess,
                            'demandeEtablissement'=> $demandeEtablissement,
                            'gestionnaire'=> $gestionnaire
                        ));
                    }
                }
            
        }    
       
        return $this->render('BackOffice/Etablissement/new_byfiness.html.twig', array(
            'num_finess' => $num_finess,'finess' => $Finess,'gestionnaire'=> $gestionnaire
        ));
    }
          
    
    
    
 
    
    function GetEtablissementByFiness(\Pericles3Bundle\Entity\Finess $Finess)
    {
       $etablissement = new Etablissement();
        
        if ($Finess->getDemandesEtablissement())
        {
            $etablissement->setReferentielPublic($Finess->getDemandesEtablissement()->getReferentielPublic());
        }
        $em = $this->getDoctrine()->getManager();

       $etablissement->setNom($Finess->GetRaisonSociale());
       $etablissement->setAdresse($Finess->GetAdresse());
       $etablissement->setFiness($Finess);
        $dep=$this->GetDepartementByPostal( $Finess->getCodePostal());
        $etablissement->setDepartement($dep);
        $etablissement->setCreai($dep->GetCreai());
        $etablissement->setCodePostal($Finess->getCodePostal());
        $etablissement->setVille($Finess->getVille());
        $etablissement->setTel($Finess->getTel());
        $etablissement->setFax($Finess->getFax());
        $etablissement->setCapaciteAcceuil($Finess->getCapaciteTotale1());
        
        $etablissement->setStockageEtablissement($em->getRepository('Pericles3Bundle:StockageEtablissement')->findOneById(0));

       
       return($etablissement);
    }
                                
                                
    private function GetFinessByCode($num_finess)
    {
        $repositoryFiness = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Finess');
        $Finess = $repositoryFiness->findOneByCodeFiness($num_finess);
        return ($Finess);
    }
                                    
    private function GetDepartementByPostal($codepostal)
    {
        $repositoryFiness = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Departement');
        $Departement = $repositoryFiness->findOneById(substr ($codepostal,0,2));
        return ($Departement);
    }
    
    private function FinessExisteDispo($num_finess)
    {
         $Finess=$this->GetFinessByCode($num_finess);
         if ($Finess)
         {
            if ($Finess->getHaveEtablissement())
            {
                $this->addFlash('error', "Le Finess est déja attribué à l'établissement ".$num_finess." . ");
            }
            else
            {
                return(true);    
            }
            
        }
        else
        {
            if (! (($num_finess)>='000000000' && ($num_finess)<'1000000000' ))  { $this->addFlash('error', "Numéro FINESS <b>".$num_finess."</b> est invalide, il doit être composé d'une série de 9 chiffres.");  }
            else  { $this->addFlash('error', "Le numéro FINESS <b>".$num_finess."</b> n'a pas été trouvé dans la base..."); }
            return(false);    
        }
    }
    
    
    
     /**
     * Creates a new Gestionnaire entity.
     *
     * @Route("/new_simple", name="backoffice_etablissement_new_simple")
     * @Method({"GET", "POST"})
     */
    public function GestionnaireEtablissementSimpleAction(Request $request)
    {
        return ($this->addEtablissementSansFiness($request));
    }
     
    
    /**
     * Ajoute un établissement au gestionnaire
     *
     * @Route("/gestionnaire_{id}/new_simple", name="backoffice_gestionnaire_add_etablissement_simple")
     * @Method({"GET", "POST"})
     */
    public function addGestionnaireEtablissementSimpleAction(Request $request, Gestionnaire $gestionnaire)
    {   
        return ($this->addEtablissementSansFiness($request,$gestionnaire));
    }
    
    
    

    public function addEtablissementSansFiness(Request $request,Gestionnaire $gestionnaire=NULL)
    {
        $etablissement = new Etablissement();
        $form = $this->createForm('Pericles3Bundle\Form\EtablissementType', $etablissement,['code_finess' => false, 'gestionnaire' => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
              if ($gestionnaire) $etablissement->setGestionnaire($gestionnaire);
              
            $etablissement->setCreatedBy($this->GetUser());
            $etablissement->setCreatedDate(new \DateTime());

          //  $etablissement->setStockageEtablissement($em->getRepository('Pericles3Bundle:StockageEtablissement')->findOneById(0));

            $em->persist($etablissement);
            $em->flush();
            $this->genereEtablissementData($etablissement);
            $this->addFlash('success', "L'établissement a bien été créé");
            return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $etablissement->getId()));
        }
        return $this->render('BackOffice/Etablissement/new.html.twig', array(
            'form' => $form->createView(),
            'gestionnaire' => $gestionnaire
        ));
    }

    
    
    
    
    
    
    private function genereEtablissementData($etablissement,ReferentielPublic $ReferentielPublic = null)
    {
         $em = $this->getEm();
         
            $repositoryDomaineExterne = $em->getRepository('Pericles3Bundle:DomaineExterne');
            if (! $ReferentielPublic) $ReferentielPublic=$etablissement->getReferentielPublic();
                
            $domaineExterneArray = array();
            if ($ReferentielPublic->getReferentielExterne())
            {
                $domaineExterne=true;
                $refsExterneNiv1 = $ReferentielPublic->getReferentielExterne()->GetReferentielExterneNiv1();
                 foreach ($refsExterneNiv1 as $refExterneNiv1) {
                         $domaineExterne = new DomaineExterne();
                            $domaineExterne->setEtablissement($etablissement);
                            $domaineExterne->setReferentielExterneN1($refExterneNiv1);
                            $em->persist($domaineExterne);
                           $em->flush();
                          // on stocke les domaines externes crées dans un tableau pour les affecté aux criteres
                    $domaineExterneArray[$refExterneNiv1->getOrdre()]=$domaineExterne->getId();
                 }
            }
            else
            {
                $domaineExterne=false;
            }
             
             
            $refDomaines = $ReferentielPublic->getReferentielDomaines();
            foreach ($refDomaines as $refDomaine) {
    			$domaine = new Domaine();
    			$domaine->setEtablissement($etablissement);
    			$domaine->setReferentiel($refDomaine);
    			$em->persist($domaine);
                        $refDimensions = $refDomaine->getChildren();
    			foreach ($refDimensions as $refDimension) {
    				$dimension = new Dimension();
                                
    				$dimension->setDomaine($domaine);
                                $domaine->addDimension($dimension);
    				$dimension->setReferentiel($refDimension);
    				$em->persist($dimension);
    				$em->persist($domaine);
    				
                                $refCriteres = $refDimension->GetChildren();
    				foreach ($refCriteres as $refCritere) {
    					$critere = new Critere();
    					$critere->setDimension($dimension);
                                        $dimension->addCritere($critere);
                                        $em->persist($dimension);
    					$critere->setReferentiel($refCritere);
    					$critere->setArevoir(false);
                                        
                                        // on récupere le domaine externe crée par l'id du ExterneNiv1
                                        if ($domaineExterne)
                                        {
                                            $DomaineExterne = $repositoryDomaineExterne->findOneById($domaineExterneArray[$refCritere->GetReferentielExterneNiv1()->GetOrdre()]);
                                            $critere->setDomaineExterne($DomaineExterne);
                                            $DomaineExterne->AddCritere($critere); /// Faux ? ??
                                            
                                        }
                                            
                                        
    					$em->persist($critere);
    					
                                        $refQuestions = $refCritere->GetChildren();
    					foreach ($refQuestions as $refQuestion) 
                                        {
    						$question = new Question();
    						$question->setCritere($critere);
                                                $critere->addQuestion($question);
            					$em->persist($critere);
                                                
    						$question->setReferentiel($refQuestion);
    						$em->persist($question);
    					}
                         
    				}
    			}   
           }
            $em->flush();   
    }
    
            
    /**
     * Displays a form to edit an existing ReferentielPublic entity.
     *
     * @Route("/genere_cache", name="etablissement_genere_cache")
     * @Method("GET")
     */
    public function genereCacheAction()
    {
        $nb=0;
        $em = $this->getDoctrine()->getManager();
        $etablissements = $em->getRepository('Pericles3Bundle:Etablissement')->findCacheAFaire(25);
        foreach ($etablissements  as $etablissement)
        {
            $nb++;
                $etablissement->GenereCache();
                $etablissement->SetSizeTotalFileUploadCache($this->container->get('Utils')->sizeTotalFileUpload($etablissement));
                $em->persist($etablissement);
                $em->flush();
                $this->addFlash('success', "-> " .$etablissement." Cache OK");
        }
        if (! $nb ) $this->addFlash('success', "Le cache semble être à jour");
        return $this->redirectToRoute('pericles3_backoffice_etablissement');        
    }
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/linkfiness", name="backoffice_etablissement_linkfiness")
     * @Method("GET")
     */
    public function linkFinessAction(Etablissement $Etablissement)
    {
        $em = $this->getDoctrine()->getManager();

        $finesses=null;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_ETABLISSEMENT'))
        {
            if ($Etablissement->getGestionnaire())
            {
                $finessGestionnaire = $Etablissement->getGestionnaire()->getFiness();
                if ($finessGestionnaire)
                {
                    $finesses=$finessGestionnaire->getEtablissements();
                }
                return $this->render('BackOffice/Etablissement/link_finess.html.twig',array("cur_etablissement" => $Etablissement,'finesses'=>$finesses, 'finessGestionnaire'=>$finessGestionnaire));  
            }
            else
            {
                return($this->linfFinessDepratement($Etablissement));
            }
            
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
    function linfFinessDepratement(Etablissement $Etablissement)
    {
        $em = $this->getDoctrine()->getManager();
        if ($Etablissement->getDepartement())
            {
                if ($Etablissement->getCodePostal())
                {
                    $finesses=$em->getRepository('Pericles3Bundle:Finess')->findByCodePostal($Etablissement->getCodePostal());
                }
                else
                {
                    $finesses=$em->getRepository('Pericles3Bundle:Finess')->findByCodeDepartement($Etablissement->getDepartement());
                }
                return $this->render('BackOffice/Etablissement/link_finess.html.twig',array("cur_etablissement" => $Etablissement,'finesses'=>$finesses));  
                
            }
            else
            {
                $this->AddFlash("error","Vous devez affecter un département à l'établissement avant de faire la liason finess");
                return $this->redirectToRoute('backoffice_etablissement_edit', array('id' => $Etablissement->getId()));
            }
    }
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/linkfiness/departement", name="backoffice_etablissement_linkfiness_departement")
     * @Method("GET")
     */
    public function linkFinessDepartementAction(Etablissement $Etablissement)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_ETABLISSEMENT'))
        {
            return($this->linfFinessDepratement($Etablissement));
       }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
         
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/linkfiness/go_{finess}", name="backoffice_etablissement_linkfiness_go")
     * @Method("GET")
     */
    public function linkFinessGoAction(Etablissement $Etablissement, Finess $finess)
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_ETABLISSEMENT'))
        {
            $Etablissement->setFiness($finess);
            $finess->SetEtablissement($Etablissement);
            $em->persist($finess);
            $em->flush();
            
            $this->AddFlash("success","L'établissement  <b>".$Etablissement."<b> a été rataché au  finess : <b>".$finess."</b>");
            return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
          
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/disque", name="backoffice_etablissement_view_disque")
     * @Method("GET")
     */
    public function showDisqueAction(Etablissement $etablissement)
    {
        if ($this->getUser()->ADroitEtablissement($etablissement) or $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
        {
             $dossiers_name[]="preuves";
            $dossiers_name[]="bibliotheque";

            foreach ($dossiers_name as $dossier_name)
            {
                $folder=WEB_DIR."/upload/".$etablissement->GetUploadFolderPath()."/".$dossier_name;
                $debugthis['folder']=$folder;
                $debugthis['name']=$dossier_name;
                $debugthis['size']= $this->get('Utils')->SizeFiles( $this->get('Utils')->scanDirectory($folder,$dossier_name,$etablissement));
                $debugthis['files']=$this->get('Utils')->scanDirectory($folder,$dossier_name,$etablissement);
                $dossiers[$dossier_name]=$debugthis;
            }
            
            return $this->render('BackOffice/Etablissement/watchdd.html.twig', ['dossiers'=>$dossiers, 'cur_etablissement'=>$etablissement]);
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
    
    
    
         
    
        /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}", name="backoffice_etablissement_view")
     * @Method("GET")
     */
    public function showAction(Etablissement $Etablissement)
    {
        if ($this->getUser()->ADroitEtablissement($Etablissement) or $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
        {
            return $this->render('BackOffice/Etablissement/show.html.twig',array("cur_etablissement" => $Etablissement));
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
        
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/patch/ref_{ref_cible}", name="backoffice_etablissement_patch_filiation")
     * @Method("GET")
     */
    public function showPatchfiliationIndexAction(Etablissement $Etablissement, $ref_cible)
    {
        $em = $this->getDoctrine()->getManager();
        $ReferentielPublicSource=$Etablissement->getReferentielPublic();
        $ReferentielPublicCible=$em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($ref_cible);
        
        $elements_nouveaux=$em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielCibleSansSource($ReferentielPublicCible);
        
        
        return $this->render('BackOffice/Etablissement/patch_filiation.html.twig',
                array("cur_etablissement" => $Etablissement,
                    "ReferentielPublicSource" => $ReferentielPublicSource,
                    "ReferentielPublicCible" => $ReferentielPublicCible,
                    "elements_nouveaux" => $elements_nouveaux,
                ));
    }
    
    
    
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/retropatch/ref_{ref_cible}", name="backoffice_etablissement_retropatch")
     * @Method("GET")
     */
    public function showRetroPatchIndexAction(Etablissement $Etablissement, $ref_cible)
    {
        $em = $this->getDoctrine()->getManager();
        $ReferentielPublicSource=$Etablissement->getReferentielPublic();
        $ReferentielPublicCible=$em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($ref_cible);
        
        $elements_nouveaux=$em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielCibleSansSource($ReferentielPublicSource);
        
        
        return $this->render('BackOffice/Etablissement/patch_retro.html.twig',
                array("cur_etablissement" => $Etablissement,
                    "ReferentielPublicSource" => $ReferentielPublicSource,
                    "ReferentielPublicCible" => $ReferentielPublicCible,
                    "elements_nouveaux" => $elements_nouveaux,
                ));
    }
    
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/eval_patch_etablissement/patch_{patch_id}", name="backoffice_eval_patch_etablissement")
     * @Method("GET")
     */
    public function evalPatchEtablissementAction(Etablissement $Etablissement,$patch_id)
    {
        $em = $this->getDoctrine()->getManager();
        $patch=$em->getRepository('Pericles3Bundle:Patch')->findOneById($patch_id);
        
        $ReferentielPublicSource=$Etablissement->getReferentielPublic();
        $ReferentielPublicCible=$patch->GetCible();
        
        
        $constatsDesuets=$em->getRepository('Pericles3Bundle:Constat')->FindReferentielDesuetEtablissementPatch($Etablissement,$patch);
        $preuvesDesuets=$em->getRepository('Pericles3Bundle:Preuve')->FindReferentielDesuetEtablissementPatch($Etablissement,$patch);
        
//        $elements_nouveaux=$em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielCibleSansSource($ReferentielPublicSource);

        return $this->render('BackOffice/patch/eval_patch_etablissement.html.twig',
                array("etablissement" => $Etablissement,
                    "ReferentielPublicSource" => $ReferentielPublicSource,
                    "ReferentielPublicCible" => $ReferentielPublicCible,
                    "patch" => $patch,
                    "constatsDesuets" => $constatsDesuets,
                    "preuvesDesuets" => $preuvesDesuets,
                ));
    }
    
    
    
    public function evalPatchEtablissementGenere(Etablissement $Etablissement, Patch $patch)
    {
         $em = $this->getDoctrine()->getManager();
        $ReferentielPublicSource=$Etablissement->getReferentielPublic();
        $ReferentielPublicCible=$patch->GetCible();
        $constatsDesuets=$em->getRepository('Pericles3Bundle:Constat')->FindReferentielDesuetEtablissementPatch($Etablissement,$patch);
        $preuvesDesuets=$em->getRepository('Pericles3Bundle:Preuve')->FindReferentielDesuetEtablissementPatch($Etablissement,$patch);

        //$engine = $this->container->get('templating');
        $view = $this->render('BackOffice/patch/eval_patch_etablissement_inc.html.twig',
                array("etablissement" => $Etablissement,
                    "ReferentielPublicSource" => $ReferentielPublicSource,
                    "ReferentielPublicCible" => $ReferentielPublicCible,
                    "patch" => $patch,
                    "constatsDesuets" => $constatsDesuets,
                    "preuvesDesuets" => $preuvesDesuets,
                ));
        $dompdf = new DOMPDF();
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->load_html($view);
        $dompdf->render();
        return ($dompdf);
    }
    
    
    
    public function EvalPatchGetFilename(Etablissement $Etablissement, Patch $patch)
    {
        return($Etablissement." - ".$patch." - ". date("d-m-Y").".pdf");
    }
    
    
    
    
    public function evalPatchEtablissementGenereFichier(Etablissement $Etablissement, Patch $patch)
    {
        $filename=$Etablissement." - ".$patch." - ". date("d-m-Y").".pdf";
        $this->evalPatchEtablissementGenere($Etablissement,$patch);
 
    //    $dompdf=$this->evalPatchEtablissementGenere($Etablissement,$patch);
        
//        file_put_contents($this->getParameter('cache_biblio_directory')."/".$filename, $dompdf->output());
        return($filename);
    }
    
    
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/eval_patch_etablissement/patch_{patch_id}/pdf", name="backoffice_eval_patch_etablissement_pdf")
     * @Method("GET")
     */
    public function evalPatchEtablissementPDFAction(Etablissement $Etablissement,$patch_id)
    {
        $em = $this->getDoctrine()->getManager();
        $patch=$em->getRepository('Pericles3Bundle:Patch')->findOneById($patch_id);
        $dompdf=$this->evalPatchEtablissementGenere($Etablissement,$patch);
        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
//        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'foo.pdf'));
        $response->sendHeaders();
        $response->setContent($dompdf->stream());
        //return new BinaryFileResponse($dompdf->output());
        return ($response);
    }
    
     
    

    
    
    
    /*
    if ($synthese_extension=="pdf")
        {
            //include($this->get('kernel')->getRootDir().'/../vendor/dompdf/dompdf_config.inc.php');
            //############### GENERATION PDF ###############
            
        }
        */
    
    
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/patch_sansdata/ref_{ref_cible}/go", name="backoffice_etablissement_patch_sans_data_transfert")
     * @Method("GET")
 
    public function showPatchGoSansTransfertIndexAction(Etablissement $Etablissement, $ref_cible)
    {
        $em = $this->getDoctrine()->getManager();
        $ReferentielPublicCible=$em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($ref_cible);
        $Etablissement->setReferentielPublic($ReferentielPublicCible);
        $em->persist($Etablissement);
        $this->genereEtablissementData($Etablissement,$ReferentielPublicCible);
        $em->flush();
        $this->AddFlash("success","----->Commetaire : ".$commetaire);
        return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));
    }
        */
    
     
     
    
    /**
     * Finds and displays a ReferentielPublic entity.
     
        "/{id}/patch/ref_{ref_cible}/gggoooo", name="backoffice_etablissement_patch_filiation_goooo")
        
  
    public function showPatchGoooIndexAction(Etablissement $Etablissement, $ref_cible)
    {
        $em = $this->getDoctrine()->getManager();
        $ReferentielPublicSource=$Etablissement->getReferentielPublic();
        $ReferentielPublicCible=$em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($ref_cible);
        
        $Etablissement->setReferentielPublic($ReferentielPublicCible);
        $em->persist($Etablissement);
        
        $this->genereEtablissementData($Etablissement,$ReferentielPublicCible);
        $em->flush();
        
        foreach($Etablissement->getDomainesReferentiel($ReferentielPublicCible) as $domaine)
        {
            $source=$domaine->GetEvalSource();
            if ($source)
            {
                foreach ($source->GetCommentaires() as $commetaire)
                {
                    $this->AddFlash("success","----->Commetaire : ".$commetaire);
                    $source->removeCommentaire($commetaire);
                    $domaine->addCommentaire($commetaire);
                }
                
                foreach ($source->GetPreuves() as $preuve)
                {
                    $this->AddFlash("success","----->preuve : ".$preuve);
                    $source->removePreuve($preuve);
                    $domaine->addPreuve($preuve);
                }
                
                foreach ($source->getObjectifsSrategique() as $ObjectifStrategique)
                {
                    $this->AddFlash("success","----->ObjectifStrategique : ".$ObjectifStrategique);
                    $source->removeObjectifsSrategique($ObjectifStrategique);
                    $domaine->addObjectifsSrategique($ObjectifStrategique);
                }
                 
                    $em->persist($source);
                    $em->persist($domaine);
                    $em->flush();
            }
         
            $this->AddFlash("success","++++++++++++++++++++++Domaine : ".$domaine);
            $this->AddFlash("success","++++++++++++++++++++++NbDim : ".$domaine->getNbDimensions());
            
            foreach ($domaine->GetDimensions() as $dimension )
            {
                $this->AddFlash("success","-------> Dimensions <-------: ".$dimension);
                // rien a réupérer sur les dimensions
                
            }
        }
        
        $em->flush();
        return $this->redirectToRoute('backoffice_etablissement_patch_analyse', array('id' => $Etablissement->getId(),'ref_cible'=>$ref_cible));

    }
       */
    
    
    
    
    
    
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/patch/{patch_id}", name="backoffice_etablissement_patch")
     * @Method("GET")
     */
    public function showPatchIndexAction(Etablissement $Etablissement, $patch_id)
    {
        $em = $this->getDoctrine()->getManager();
        $patch=$em->getRepository('Pericles3Bundle:Patch')->findOneById($patch_id);
        $ReferentielPublicSource=$patch->getSource();
        $ReferentielPublicCible=$patch->getCible();
        //$elements_nouveaux=$em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielCibleSansSource($ReferentielPublicCible);
        $elements_nouveaux=null;
        
        
        return $this->render('BackOffice/Etablissement/patch.html.twig',
                array("cur_etablissement" => $Etablissement,
                    "ReferentielPublicSource" => $ReferentielPublicSource,
                    "patch" => $patch,
                     "etape" => 1,
                    "ReferentielPublicCible" => $ReferentielPublicCible,
                    "elements_nouveaux" => $elements_nouveaux,
                ));
    }
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/patch/{patch_id}/todo", name="backoffice_etablissement_patch_todo")
     * @Method("GET")
     */
    public function PatchToDoAction(Etablissement $Etablissement, $patch_id)
    {
        $em = $this->getDoctrine()->getManager();
        $patch=$em->getRepository('Pericles3Bundle:Patch')->findOneById($patch_id);
        
        $patchToDo=new PatchToDo();
        $patchToDo->setPatch($patch);
        $patchToDo->setEtablissement($Etablissement);
        $em->persist($patchToDo);
        $em->flush();
        return $this->redirectToRoute('backoffice_patch_todo_show', array('id' => $patchToDo->getId()));
    }
    
    
    
    
    
    function etablisssementPatchGo(Etablissement $Etablissement,Patch $patch,$sauvegardes=true)
    {
        for ($etape=0;$etape<=4;$etape++)
        {
            $this->etablisssementPatchEtape($Etablissement,$patch,$etape);
        }
        $sauvegardeController = new \Pericles3Bundle\Controller\BackOffice\SauvegardeController();
        $sauvegardeController->SetOutput($this->GetOutput());
        $sauvegardeController->SetEm($this->GetEm());
        if ($sauvegardes)
        {
            $this->OutputOrFlashSuccess("-------------------------------------- ");
            $this->OutputOrFlashSuccess("------------ Sauvegardes ------------- ");
            $this->OutputOrFlashSuccess("-------------------------------------- ");
            foreach ($Etablissement->getSauvegardes() as $sauvegarde)
            {
                $this->OutputOrFlashSuccess("------>".$sauvegarde);
                $sauvegardeController->patchSauvegardeApply($sauvegarde, $patch);
            } 
        }
        elseif ($Etablissement->getNbSauvegardes())
        {
            $this->OutputOrFlashError("!!!!!   attention,  les sauvegardes ne sont pas patchés ! ");
        }
        else
        {
            $this->OutputOrFlashError("Pas de sauvegardes ! ");
        }
    }
 
    
    
    function deleteReferentielExterne(Etablissement $Etablissement)
    {
        $em=$this->GetEm();
        foreach ($Etablissement->getDomainesExterne() as $domaineExterne)
        {
            $this->Output("-->".$domaineExterne);
            foreach ($domaineExterne->getCriteres() as $critere)
            {
                $domaineExterne->removeCritere($critere);
                $critere->setDomaineExterne(null);
                $em->persist($critere);
                $em->persist($domaineExterne);
                $em->flush();
            }
            $em->remove($domaineExterne); 
            $em->flush();
        }
    }
    
    
    function etablisssementPatchEtape(Etablissement $Etablissement,Patch $patch,$etape)
    {
        $em=$this->GetEm();
        
        
        $ReferentielPublicSource=$patch->getSource();
        $ReferentielPublicCible=$patch->getCible();
        if ($etape==1)
        {
            $this->Output("Supresion du référenrtiel externe");
            $this->deleteReferentielExterne($Etablissement);
            
            $this->Output("Changement du référenrtiel");
            $Etablissement->setReferentielPublic($ReferentielPublicCible);
            $Etablissement->setPatch($patch);
            $em->persist($Etablissement);
            $em->flush();
            $this->Output("Génération des données");
            $this->genereEtablissementData($Etablissement,$ReferentielPublicCible);
            $this->Output("OK");
            $em->persist($Etablissement);
            $em->flush();
        }
        elseif($etape==2)
        {
            $em->flush();
            foreach($Etablissement->getDomainesReferentiel($ReferentielPublicCible) as $domaine)
            {
                $this->Output("Domaine :".$domaine);
                $source=null;
                $sourceRef=$patch->getReferentielSourceFromCible($domaine->GetReferentiel());
                if ($sourceRef)
                {
                    $source= $sourceRef->getDomaineEtablissementReferentiel($Etablissement, $ReferentielPublicSource);
                }
                if ($source)
                {
                      $this->OutputOrFlash("----->SOURCE<----: ".$source);
                      foreach ($source->GetCommentaires() as $commetaire)
                        {
                            $this->OutputOrFlash("----->Commetaire : ".$commetaire);
                            $source->removeCommentaire($commetaire);
                            $domaine->addCommentaire($commetaire);
                        }

                        foreach ($source->GetPreuves() as $preuve)
                        {
                            $this->OutputOrFlash("----->preuve : ".$preuve);
                            $source->removePreuve($preuve);
                            $domaine->addPreuve($preuve);
                        }

                        foreach ($source->getObjectifsSrategique() as $ObjectifStrategique)
                        {
                            $this->OutputOrFlash("----->ObjectifStrategique : ".$ObjectifStrategique);
                            $source->removeObjectifsSrategique($ObjectifStrategique);
                            $domaine->addObjectifsSrategique($ObjectifStrategique);
                        }
                        $em->persist($source);
                        $em->persist($domaine);
                        $em->flush();
                } 
            }
        }
        elseif ($etape==3)
        {
            $this->OutputOrFlash("----->Etape 3 : ");
            $nb_domaine=0;
            $nb_dimensions=0;
            $nb_criteres=0;
            $nb_preuves=0;
            $nb_constats=0;
            $nb_objectifs=0;
            
            
            $em->flush();
            
            foreach($Etablissement->getDomainesReferentiel($ReferentielPublicCible) as $domaine)
            {
                $nb_domaine++;
//                $this->Output("-------> domaine <-------: ".$domaine." (".$domaine->GetReferentielPublic().")");
                foreach ($domaine->GetDimensions() as $dimension )
                {
  //                  $this->Output("-------> dimension <-------: ".$dimension);
                    $nb_dimensions++;
                    foreach ($dimension->GetCriteres() as $critere)
                    {
                            $nb_criteres++;
                            $this->Output("-------> critere <-------: ".$critere);
                            $source=null;
                            $sourceRef=$patch->getReferentielSourceFromCible($critere->GetReferentiel());
                            if ($sourceRef)
                            {
                                $source= $sourceRef->getCritereEtablissementReferentiel($Etablissement, $ReferentielPublicSource);
                            }
                            if ($source)
                            {
                                foreach ($source->GetPreuves() as $preuve)
                                {
                                    $nb_preuves++;
                                    $this->Output("------------> preuves : ".$preuve);
                                    $source->removePreuve($preuve);
                                    $critere->addPreuve($preuve);
                                }
                                foreach ($source->GetConstats() as $constat)
                                {
                                    $this->Output("------------> constat : ".$constat);
                                    $nb_constats++;
                                    $source->removeConstat($constat);
                                    $critere->addConstat($constat);
                                } 

                                foreach ($source->getObjectifs() as $ooa)
                                {
                                    $this->Output("------------> Objectif opérationnel : ".$ooa);
                                    $nb_objectifs++;
                                    $source->removeObjectif($ooa);
                                    $ooa->addCritere($critere); 
                                    $critere->addObjectif($ooa);
                                }
                                $critere->setNote($source->GetNote());

                                $em->persist($critere);
                                $em->flush();

                            }
                        }
                    }
                    if (! $nb_dimensions )  $this->Output("<error> Pas de dimensions  pour l'établissement ".$Etablissement." avec le referentiel public cible ".$ReferentielPublicCible." </error>");
                    
                }
                
            if (! $nb_domaine )  $this->Output("<error> Pas de domaines pour l'établissement ".$Etablissement." avec le referentiel public cible ".$ReferentielPublicCible." </error>");
            
            $this->AddFlashIf("success"," Dimensions  : ".$nb_dimensions);
            $this->AddFlashIf("success"," Criteres : ".$nb_criteres);
            $this->AddFlashIf("success"," Preuves : ".$nb_preuves);
            $this->AddFlashIf("success"," Constats : ".$nb_constats);
            $this->AddFlashIf("success"," Objectifs : ".$nb_objectifs);

        }
        elseif ($etape==4)
        {
            $this->OutputOrFlash("----->Etape 3 : ");
            $nb_questions=0;
            foreach($Etablissement->getDomainesReferentiel($ReferentielPublicCible) as $domaine)
            {
                foreach ($domaine->GetDimensions() as $dimension )
                {
                    foreach ($dimension->GetCriteres() as $critere)
                    {
                            foreach ($critere->GetQuestions() as $question )
                            {
                                
                                $this->Output("Question :".$question);
                                $nb_questions++;
                                $source=null;
                                $sourceRef=$patch->getReferentielSourceFromCible($question->GetReferentiel());
                                if ($sourceRef)
                                {
                                    $source= $sourceRef->getQuestionEtablissementReferentiel($Etablissement, $ReferentielPublicSource);
                                }
                                if ($source)
                                {
                                    $question->setReponse($source->GetReponse());
                                    $em->persist($question);
                                }
                            }
                    }
                }
                $em->flush();
            }
            $this->OutputOrFlash("-----------> nb questionS : ".$nb_questions); 
            $this->OutputOrFlash("--> Supression des constats désuets: "); 
            $constats=$em->getRepository('Pericles3Bundle:Constat')->FindReferentielDesuet($Etablissement);
            foreach ($constats as $constat)
            {
                $this->OutputOrFlash("-----------> : ".$constat); 
                $em->remove($constat); 
            }
            $em->flush();
            $this->OutputOrFlash("--> Supression des preuves désuetes: "); 
            $preuves=$em->getRepository('Pericles3Bundle:Preuve')->FindReferentielDesuet($Etablissement);
            foreach ($preuves as $preuve)
            {
                $this->OutputOrFlash("----------->  : ".$preuve); 
                $em->remove($preuve); 
            }
            $em->flush();
            $this->OutputOrFlash("--> Supression des liasons Critere / Objectifs opérationnesl : "); 
            $objectifOperationnels =$em->getRepository('Pericles3Bundle:ObjectifOperationnel')->FindReferentielDesuet();
            foreach ($objectifOperationnels as $objectifOperationnel)
            {
                foreach ($objectifOperationnel->getCriteres() as $critere)
                {
                    if ($critere->IsObsolete())
                    {
                        $objectifOperationnel->removeCritere($critere);
                        $critere->removeObjectif($objectifOperationnel);
                         $em->persist($objectifOperationnel);
                         $em->persist($critere);
                        $this->OutputOrFlash("------  ".$critere."  ----->  : ".$objectifOperationnel); 
                    }
                }
            }
        }
        
    }
    
        /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/patch/{patch_id}/go/etape_{etape}", name="backoffice_etablissement_patch_go")
     * @Method("GET")
     */
    public function showPatchGoAction(Etablissement $Etablissement, $patch_id,$etape)
    {
        $em = $this->getDoctrine()->getManager();
        $patch=$em->getRepository('Pericles3Bundle:Patch')->findOneById($patch_id);
        $ReferentielPublicSource=$patch->getSource();
        $ReferentielPublicCible=$patch->getCible();
        
        if ($etape==1)
        {
            $this->AddFlashIf("success","Etape ".$etape);
            $this->etablisssementPatchEtape($Etablissement,$patch,$etape);
            $etape++;
        }
        elseif ($etape==2)
        {
            $this->AddFlashIf("success","Etape ".$etape);
            $this->etablisssementPatchEtape($Etablissement,$patch,$etape);
            $etape++;
        } 
        elseif ($etape==3)
        {
            $this->AddFlashIf("success","Etape ".$etape);
            $this->etablisssementPatchEtape($Etablissement,$patch,$etape);
            $etape++;
        }
        elseif ($etape==4)
        {
            $this->AddFlashIf("success","Etape ".$etape);
            $this->etablisssementPatchEtape($Etablissement,$patch,$etape);
            return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));
        }
        
        
        
        return $this->render('BackOffice/Etablissement/patch.html.twig',
                array("cur_etablissement" => $Etablissement,
                    "ReferentielPublicSource" => $ReferentielPublicSource,
                    "etape" => $etape,
                    "patch" => $patch,
                    "ReferentielPublicCible" => $ReferentielPublicCible
                ));
        
        
    }
    

    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/patch/ref_{ref_cible}/etape_{etape}", name="backoffice_etablissement_patch_filiation_go")
     * @Method("GET")
     */
    public function showPatchGoIndexAction(Etablissement $Etablissement, $ref_cible,$etape)
    {
        $em = $this->getDoctrine()->getManager();
        $ReferentielPublicSource=$Etablissement->getReferentielPublic();
        $ReferentielPublicCible=$em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($ref_cible);

        if ($etape==0)
        {
            $this->AddFlash("success","dataEtablissement");
            $Etablissement->setReferentielPublic($ReferentielPublicCible);
            $em->persist($Etablissement);
            $this->genereEtablissementData($Etablissement,$ReferentielPublicCible);
            $em->persist($Etablissement);
            $em->flush();
            $etape=1;
        }
        elseif ($etape==1)
        {
            foreach($Etablissement->getDomainesReferentiel($ReferentielPublicCible) as $domaine)
            {
                    $source=$domaine->GetEvalSource();
                    if ($source)
                    {
                        foreach ($source->GetCommentaires() as $commetaire)
                        {
                            $this->AddFlash("success","----->Commetaire : ".$commetaire);
                            $source->removeCommentaire($commetaire);
                            $domaine->addCommentaire($commetaire);
                        }

                        foreach ($source->GetPreuves() as $preuve)
                        {
                            $this->AddFlash("success","----->preuve : ".$preuve);
                            $source->removePreuve($preuve);
                            $domaine->addPreuve($preuve);
                        }

                        foreach ($source->getObjectifsSrategique() as $ObjectifStrategique)
                        {
                            $this->AddFlash("success","----->ObjectifStrategique : ".$ObjectifStrategique);
                            $source->removeObjectifsSrategique($ObjectifStrategique);
                            $domaine->addObjectifsSrategique($ObjectifStrategique);
                        }

                            $em->persist($source);
                            $em->persist($domaine);
                            $em->flush();
                    }

                    $this->AddFlash("success","++++++++++++++++++++++Domaine : ".$domaine);

                }
            $etape=2;
        
        }
        elseif ($etape==2)
        {
            
            $nb_dimensions=0;
            $nb_criteres=0;
            $nb_preuves=0;
            $nb_constats=0;
            $nb_objectifs=0;
            
            foreach($Etablissement->getDomainesReferentiel($ReferentielPublicCible) as $domaine)
            {
            foreach ($domaine->GetDimensions() as $dimension )
            {
                $nb_dimensions++;
                foreach ($dimension->GetCriteres() as $critere)
                {
                    $nb_criteres++;
                       // $this->AddFlash("success","-------> critere <-------: ".$critere);

                        $source=$critere->GetEvalSource();
                        if ($source)
                        {


                            foreach ($source->GetPreuves() as $preuve)
                            {
                                $nb_preuves++;
                                $source->removePreuve($preuve);
                                $critere->addPreuve($preuve);
                            }
                            foreach ($source->GetConstats() as $constat)
                            {
                                $nb_constats++;
                                $source->removeConstat($constat);
                                $critere->addConstat($constat);
                            } 

                            foreach ($source->getObjectifs() as $ooa)
                            {
                                $nb_objectifs++;
                                $source->removeObjectif($ooa);
                                $ooa->addCritere($critere); 
                                $critere->addObjectif($ooa);
                            }
                            $critere->setNote($source->GetNote());

                            $em->persist($critere);
                            $em->flush();
                         
                        }
                    }
                }
            }
            
            
            
           $etape=3; 
            $this->AddFlash("success"," Dimensions  : ".$nb_dimensions);
            $this->AddFlash("success"," Criteres : ".$nb_criteres);
            $this->AddFlash("success"," Preuves : ".$nb_preuves);
            $this->AddFlash("success"," Constats : ".$nb_constats);
            $this->AddFlash("success"," Objectifs : ".$nb_objectifs);

        }
        elseif ($etape==3)
        {
             
            $nb_questions=0;
            foreach($Etablissement->getDomainesReferentiel($ReferentielPublicCible) as $domaine)
            {
                foreach ($domaine->GetDimensions() as $dimension )
                {
                    foreach ($dimension->GetCriteres() as $critere)
                    {
                            foreach ($critere->GetQuestions() as $question )
                            {
                                $nb_questions++;
                                $source=$question->GetEvalSource();
                                if ($source)
                                {
                                    $question->setReponse($source->GetReponse());
                                    $em->persist($question);
                                }
                            }
                    }
                }
                $em->flush();
            }
            $this->AddFlash("success","-----------> nb questionS : ".$nb_questions); 
            return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));

        }
        
        return $this->render('BackOffice/Etablissement/patch_analyse.html.twig',
                array("cur_etablissement" => $Etablissement,
                    "elements_nouveaux" => Null,
                    "ReferentielPublicSource" => $ReferentielPublicSource,
                    "ReferentielPublicCible" => $ReferentielPublicCible,
                    "etape" => $etape
                ));
        
        
    }
    
    
    
    
    

            
    
    
    
    
    
    
     /**
     * Finds and displays a ReferentielPublic entity.
     *
     *  -- - -@Route("/{id}/patch/ref_{ref_cible}/to", name="backoffice_etablissement_patch_filiation_go")
     *   -- - - - - @Method("GET")
     */
    
    /*
    public function showPatchGoIndexAction(Etablissement $Etablissement, ReferentielPublic $ReferentielPublicCible)
    {
        $ReferentielPublicSource=$Etablissement->getReferentielPublic();
        $Etablissement->setReferentielPublic($ReferentielPublicCible);
        $em->persist($Etablissement);
     
        
 
        
                 * 
           
    }
    
          */
    
    
    
    
    
    
        
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/edit", name="backoffice_etablissement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Etablissement $Etablissement)
    {
        
        $editForm = $this->createForm('Pericles3Bundle\Form\EtablissementType', $Etablissement,['edit'=>true]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Etablissement);
            $em->flush();
            return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));
        }

        return $this->render('BackOffice/Etablissement/edit.html.twig', array(
            'etablissement' => $Etablissement,
            'form' => $editForm->createView()
        ));
        
    }
    
    
    
    
    /**
     * Finds and displays a DemandeEtablissement entity.
     *
     * @Route("/gestionnaire/create_etablissement/demande_{id}", name="demande_gestionnaire_create_etablissement_bydemande")
     * @Method({"GET"})
     */
    public function CreateEtabGestionnaireBydemandeAction(DemandeEtablissement $DemandeEtablissement)
    {
        $DemandeGestionnaire=$DemandeEtablissement->getDemandeGestionnaire();
        $etablissement = new Etablissement();
        $Finess=$DemandeEtablissement->GetFiness();
        $em = $this->getDoctrine()->getManager();

        $etablissement->setStockageEtablissement($em->getRepository('Pericles3Bundle:StockageEtablissement')->findOneById(0));
        $etablissement->setCategory($em->getRepository('Pericles3Bundle:EtablissementCategory')->findOneById(1));
        $etablissement->setModeCotisation($DemandeEtablissement->getModeCotisation());
        
        $etablissement->setGestionnaire($DemandeGestionnaire->getGestionnaire());
        $etablissement->SetDemandeEtablissement($DemandeEtablissement);
        $etablissement->setNom($DemandeEtablissement->getEtablissementNom());
        $etablissement->setReferentielPublic($DemandeEtablissement->getReferentielPublic());
        $etablissement->setCreai($DemandeEtablissement->getDemandeGestionnaire()->getCreai());
        
        

        
        if ($Finess) 
        {
            $etablissement->setFiness($Finess);
            $Finess->SetEtablissement($etablissement);
            $em->persist($Finess);
        }
        $etablissement->setCreatedBy($this->GetUser());
        $etablissement->setCreatedDate(new \DateTime());
        $em->persist($etablissement);
        $em->flush();
        $this->AddFlash("success","L'établissement a bien été créé.");


        $DemandeEtablissement->setEtat($em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById(3));
        $em->persist($DemandeEtablissement);
        $em->flush();

        $DemandeGestionnaire->setEtat($em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById(2));
        $em->persist($DemandeGestionnaire);
        $em->flush();
        
        
        $this->genereEtablissementData($etablissement);
        return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $DemandeEtablissement->getDemandeGestionnaire()->getGestionnaire()->getId()));
    }
    
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function listAction($gestionnaire)
    { 
        
        $repositoryEtablisssment = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement');
        if ($gestionnaire) 
        {
            $etablissements = $repositoryEtablisssment->findBy(array("gestionnaire" => $gestionnaire), array('nom' => 'ASC'));
        }   
        else
        {
            $etablissements = $repositoryEtablisssment->findBy(array(), array('nom' => 'ASC'));
        }
    	
        return $this->render('BackOffice/Etablissement/list.html.twig',array("etablissements" => $etablissements));
    }
    
    
    
      
    /**
     * Deletes a Etablissment entity.
     *
     * @Route("/delete/{id}", name="backoffice_etablissement_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteURLAction(Etablissement $Etablissement)
    {
        $em = $this->getDoctrine()->getManager();
        
        $demande= $Etablissement->getDemandeEtablissement();
        if ($demande)
        {
//            $Etablissement->setDemandeEtablissement(null);
            $demande->setEtablissement(null);
        }
        
                
        if ($Etablissement->getNbUsers()>0)
        {
            $this->addFlash('error', "Vous devez supprimer manuellement tous les utilisateurs avant de supprimer l'établissement");
            return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));
        }
        else
        {
       
            foreach ($Etablissement->getDomaines() as $Domaine )
            {
                foreach ($Domaine->getDimensions() as $Dimension  )
                {
                    foreach ($Dimension->getCriteres() as $Critere )
                    {
                        foreach ($Critere->GetQuestions() as $Question)
                        {
                             $em->remove($Question);
                        }
                        $em->flush();
                        $em->remove($Critere);
                    }
                    $em->flush();
                    $em->remove($Dimension);
                }
                $em->flush();
                $em->remove($Domaine);
            }
            $em->flush();
            $this->addFlash('success', "Les domaines / dimensions / critères ont bien été supprimés");
            
            
             $this->addFlash('success', "Vérification de la suppression");
             
            foreach ($Etablissement->getDomainesExterne() as $domaineExterne ) 
            {
                $em->remove($domaineExterne);
            }
            $em->flush();
            $this->addFlash('success', "Les domaines externes ont bien été supprimés");
            
            $em->remove($Etablissement);
            $em->flush();
        }
     
        
        return $this->redirectToRoute('pericles3_backoffice_etablissement');
    }
    

    
    
    /**
     * Deletes a Etablissment entity.
     *
     * @Route("/delete/{id}/force", name="backoffice_etablissement_delete_force")
     * @Method({"GET", "POST"})
     */
    public function deleteURLForceAction(Etablissement $Etablissement)
    {
        $em = $this->getDoctrine()->getManager();
        
        $demande= $Etablissement->getDemandeEtablissement();
        if ($demande)
        {
//            $Etablissement->setDemandeEtablissement(null);
            $demande->setEtablissement(null);
            $em->persist($demande);
        }
        $finess= $Etablissement->getFiness();
        if ($finess)
        {
            $finess->setEtablissement(null);
            $Etablissement->setFiness(null);
            $Etablissement->setNom($Etablissement->getNom()." - Supprimé");
            $this->addFlash('success', "l'établissement a bien été supprimé ! ");
            $em->persist($finess);
            $em->persist($Etablissement);
            $em->flush();
      
        } 
        
        
                 
            foreach ($Etablissement->getUsers() as $user)
            {
                $user->SetEmail(null);
                $user->SetUsername($user->getUsername(). (" - suprimmé le : ". date("d/m/Y h:i:s")));
                $this->addFlash('success', "Supression de USER : ".$user);
                $em->remove($user);
            }
 
            


//            $Etablissement->getFiness()->setEtablissement(null);
            
            
            
            
            $em->remove($Etablissement);
            $em->flush();
        
        
        return $this->redirectToRoute('pericles3_backoffice_etablissement');
    }
    

    
    
    
    
    
      
    /**
     * Deletes a Etablissment entity.
     *
     * @Route("/reboot/{id}", name="backoffice_etablissement_reboot")
     * @Method({"GET", "POST"})
     */
    public function rebootAction(Etablissement $Etablissement)
    {
        $em = $this->getDoctrine()->getManager();
         
            foreach ($Etablissement->getObjectifsOperationnel() as $ObjectifOperationnel)
            {
                foreach ($ObjectifOperationnel->getPreuves() as $preuve)
                {
                     $em->remove($preuve);
                }
                $em->remove($ObjectifOperationnel);
            }
            foreach ($Etablissement->getBibliotheques() as $Bibliotheque)
            {
                $em->remove($Bibliotheque);
            }
            
            $em->flush();                
            
       
            foreach ($Etablissement->getDomaines() as $Domaine )
            {
                foreach ($Domaine->getObjectifsSrategique() as $ObjectifSrategique )
                {
                        $em->remove($ObjectifSrategique);
                        $em->flush();
                }
                foreach ($Domaine->getCommentaires() as $commentaire )
                {
                        $em->remove($commentaire);
                        $em->flush();
                }
                
                foreach ($Domaine->getPreuves() as $preuve)
                {
                        $em->remove($preuve);
                        $em->flush();
                }
                
                
                
                
                
                foreach ($Domaine->getDimensions() as $Dimension  )
                {
                    foreach ($Dimension->getCriteres() as $Critere )
                    {
                            $Critere->SetNote(0);
                            $Critere->setArevoir(false);
                            $em->persist($Critere);
                            
                        foreach ($Critere->getPreuves() as $preuve)
                        {
                             $em->remove($preuve);
                        }
                            
                        
                        foreach ($Critere->getConstats() as $constat)
                        {
                             $em->remove($constat);
                        }
                            
                        foreach ($Critere->GetQuestions() as $Question)
                        {
                            $Question->SetReponse(null);
                            $em->persist($Question);
                        }

                        
                        /*
                        $em->flush();
                        $em->remove($Critere);
                         * 
                         */
                    }
                }
            }
            $em->flush();

            /*
            $em->flush();
             * 
             */
            $this->addFlash('success', "Les domaines / dimensions / critères ont bien été rebootés");
            $this->addFlash('success', "Vérification de la suppression");
              
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
        {
                return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));

        }
        else
        {
                    return $this->redirectToRoute('pericles3_backoffice');
        }
    }
    
    
    
      
    /**
     * Deletes a Etablissment entity.
     *
     * @Route("/delete_saisies/{id}", name="backoffice_etablissement_delete_saisies")
     * @Method({"GET", "POST"})
     */
    public function deleteSaisiesAction(Etablissement $Etablissement)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_ETABLISSEMENT'))
        {
        $em = $this->getDoctrine()->getManager();
        $em->flush();       
        foreach ($Etablissement->getDomaines() as $Domaine )
        {
            foreach ($Domaine->getObjectifsSrategique() as $ObjectifSrategique )
            {
                    $em->remove($ObjectifSrategique);
                    $em->flush();
            }
            foreach ($Domaine->getCommentaires() as $commentaire )
            {
                    $em->remove($commentaire);
                    $em->flush();
            }

            foreach ($Domaine->getPreuves() as $preuve)
            {
                    $em->remove($preuve);
                    $em->flush();
            }
            foreach ($Domaine->getDimensions() as $Dimension  )
            {
                foreach ($Dimension->getCriteres() as $Critere )
                {
                        $Critere->SetNote(0);
                        $Critere->setArevoir(false);
                        $em->persist($Critere);

                    foreach ($Critere->getPreuves() as $preuve)
                    {
                         $em->remove($preuve);
                    }
                    foreach ($Critere->getConstats() as $constat)
                    {
                         $em->remove($constat);
                    }
                    foreach ($Critere->GetQuestions() as $Question)
                    {
                        $Question->SetReponse(null);
                        $em->persist($Question);
                    }
                }
            }
        }
        foreach ($Etablissement->getObjectifsOperationnel() as $oo)
        {
            foreach ($oo->GetCriteres() as $crit)
            {
                $this->addFlash('success', "Supression du critere : ".$crit);
                $oo->removeCritere($crit);
                $crit->removeObjectif($oo);
            }
            $em->persist($oo);
        }
        
        $this->deleteSauvegarde($Etablissement);
            
        $em->flush();
        $this->addFlash('success', "Les domaines / dimensions / critères ont bien été rebootés");
       
                
        }
       return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));

    }
    
    
    
    
    
      
    /**
     * Deletes a Etablissment entity.
     *
     * @Route("/delete_oo/{id}", name="backoffice_etablissement_delete_oo")
     * @Method({"GET", "POST"})
     */
    public function deleteOOAction(Etablissement $Etablissement)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_ETABLISSEMENT'))
        {
        $em = $this->getDoctrine()->getManager();
        $em->flush();       
        foreach ($Etablissement->getObjectifsOperationnel() as $oo )
        {
            $em->remove($oo);
            $this->addFlash('success', "Supression de ".$oo);
        }
            
        $em->flush();
        $this->addFlash('success', "objectifs opérationnels  supprimés");
                
        }
       return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));

    }
    
    
    
      
    /**
     * Deletes a Etablissment entity.
     *
     * @Route("/delete_biblio/{id}", name="backoffice_etablissement_delete_biblio")
     * @Method({"GET", "POST"})
     */
    public function deletebiblioAction(Etablissement $Etablissement)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_ETABLISSEMENT'))
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();       
            foreach ($Etablissement->getBibliotheques() as $biblio )
            {
                $em->remove($biblio);
                $this->addFlash('success', "Supression de ".$biblio);
            }

            $em->flush();
            $this->addFlash('success', "Les bibliothèques de l'établissement ont été été supprimés");

        }
       return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));

    }
    
    
    
    
    
    
    /**
     * Deletes a Etablissment entity.
     *
     * @Route("/force_change_referentiel/etab_{id}/ref_{ref_id}", name="backoffice_etablissement_change_ref")
     * @Method({"GET", "POST"})
     */
    public function changeRefForceAction(Etablissement $Etablissement, ReferentielPublic $ref_id)
    {
        $em = $this->getDoctrine()->getManager();
   
        $this->addFlash('success', "Swith ".$Etablissement." de ".$Etablissement->GetReferentielPublic()." vers".$ref_id);
        $this->deleteReferentielEtablissement($Etablissement);
        $this->addFlash('success', "Supression des données");
        $Etablissement->setReferentielPublic($ref_id);
        $em->persist($Etablissement);
        $em->flush();
        $this->genereEtablissementData($Etablissement);
        $this->addFlash('success', "Génération des données");
        $em->persist($Etablissement);
        $em->flush();
        return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $Etablissement->getId()));
        
    }
     
    
    function deleteSauvegarde(Etablissement $Etablissement)
    {
          $em = $this->getDoctrine()->getManager();
        foreach ($Etablissement->getSauvegardes() as $Sauvegarde )
        {          
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
        $this->addFlash('success', "La sauvegarde ".$Sauvegarde." à bien été supprimée");
        $em->remove($Sauvegarde);
        }
        $em->flush();
    }
    
    
    function deleteReferentielEtablissement(Etablissement $Etablissement)
    {
            $em = $this->getDoctrine()->getManager();
            foreach ($Etablissement->getDomaines() as $Domaine )
            {
                foreach ($Domaine->getDimensions() as $Dimension  )
                {
                    foreach ($Dimension->getCriteres() as $Critere )
                    {
                        foreach ($Critere->GetQuestions() as $Question)
                        {
                             $em->remove($Question);
                        }
                        $em->flush();
                        $em->remove($Critere);
                    }
                    $em->flush();
                    $em->remove($Dimension);
                }
                $em->flush();
                $em->remove($Domaine);
                $em->flush();
            }
       
            
            
            $em->flush();
            $this->addFlash('success', "Les domaines / dimensions / critères ont bien été supprimés");
            foreach ($Etablissement->getDomainesExterne() as $domaineExterne ) 
            {
                $em->remove($domaineExterne);
            }
            $em->flush();
            $this->addFlash('success', "Les domaines externes ont bien été supprimés");
        }
    
    
        
    
     
}
