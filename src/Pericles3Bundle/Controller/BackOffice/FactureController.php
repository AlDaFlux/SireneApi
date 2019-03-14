<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Pericles3Bundle\Entity\Facture;
use Pericles3Bundle\Entity\FacturePresta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Dompdf\Dompdf;



/**
 * Facture controller.
 *
 * @Route("backoffice/facture")
 */
class FactureController extends Controller
{
    /**
     * Lists all facture entities.
     *
     * @Route("/", name="facture_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('Pericles3Bundle:Facture')->findAll();
        $factures_a_echeances = $em->getRepository('Pericles3Bundle:Facture')->ProchainesFacturesAEcheances();
        $factures_a_payer_old = $em->getRepository('Pericles3Bundle:Facture')->findNonPayeeOld();
        $factures_non_finalisees = $em->getRepository('Pericles3Bundle:Facture')->findNonFinalisee();
        
//        $factures_a_payer_vielles = $em->getRepository('Pericles3Bundle:Facture')->factures_a_echeances();
        $sommeAPayer = $em->getRepository('Pericles3Bundle:Facture')->findSommeNonPayee();
        $gestionnaires_sans_facture = $em->getRepository('Pericles3Bundle:Gestionnaire')->findSansFacture();
        $etablissements_sans_facture = $em->getRepository('Pericles3Bundle:Etablissement')->findReelsSansFacture();

        
        
        return $this->render('BackOffice/facture/index.html.twig', array(
            'factures' => $factures,
            'gestionnaires_sans_facture' => $gestionnaires_sans_facture,
            'etablissements_sans_facture' => $etablissements_sans_facture,
            'factures_a_echeances' => $factures_a_echeances,
            'factures_a_payer_old' => $factures_a_payer_old,
            'factures_non_finalisees' => $factures_non_finalisees,
            'sommeAPayer' => $sommeAPayer['total'],
        ));
    }

    
    
    
    
    
    
    
     /**
     * Lists all facture entities.
     *
     * @Route("/testfile", name="facture_index_testfile")
     * @Method("GET")
     */
    public function indexTestFileAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('Pericles3Bundle:Facture')->findAll();
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../factures/';
        foreach ($factures as $facture)
        {
            $file = $publicResourcesFolderPath.$facture->GetYear()."/".$facture.".pdf";
            if (file_exists($file))
            {
                $facture->setFileName($facture.".pdf");
                $em->persist($facture);
                $this->AddFlash("success",$file);
            }
            else
            {
                $this->AddFlash("error",$file);
            }
        }
        $em->flush();
        return $this->render('BackOffice/facture/index.html.twig', array(
            'factures' => $factures,
        ));
    }

    
    /**
     * Lists all facture entities.
     *
     * @Route("/all", name="facture_index_all")
     * @Method("GET")
     */
    public function AllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('Pericles3Bundle:Facture')->findAll();
        return $this->render('BackOffice/facture/liste.html.twig', array(
                    'factures' => $factures,
                'titre' => "Toutes",
        ));
    }
    
    /**
     * Lists all facture entities.
     *
     * @Route("/prestasa", name="facture_index_prestas")
     * @Method("GET")
     */
    public function PrestaAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $prestas = $em->getRepository('Pericles3Bundle:FacturePresta')->findAll();
        return $this->render('BackOffice/facture/presta.html.twig', array(
                'prestas' => $prestas,
                'titre' => "Prestations",
        ));
    }
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/etablissement_{id}/infos/facture_{facture}", options={"expose"=true}, name="pericles3_etablissements_infos_prestas")
     * @Method({"GET", "POST"})
     */    
    public function indexInfoEtablissmentPrestaAction(\Pericles3Bundle\Entity\Etablissement $etablissement, Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $prestas = $em->getRepository('Pericles3Bundle:FacturePresta')->findByEtablissement($etablissement);
        
        return $this->render('BackOffice/facture/modal_prestas_etablissement.html.twig', array(
                'etablissement' => $etablissement,
                'facture' => $facture,
                'prestas' => $prestas,
                'titre' => "Prestations",
        ));
     
    }
    
    
    
    
    /**
     * Lists all facture entities.
     *
     * @Route("/prestas/regenere_date", name="facture_index_prestas_regenere")
     * @Method("GET")
     */
    public function PrestaGenereAction()
    {
        $em = $this->getDoctrine()->getManager();
        $prestas = $em->getRepository('Pericles3Bundle:FacturePresta')->findAll();
        
        foreach ($prestas as $presta)
        {
            $presta->setDateFin($presta->getDateFinCalcule());
            $em->persist($presta);
        }
        $em->flush();
        
        
        
        return $this->render('BackOffice/facture/presta.html.twig', array(
                'prestas' => $prestas,
                'titre' => "Prestations",
        ));
    }
    
     
    
    
    
    /**
     * Lists all facture entities.
     *
     * @Route("/presta_{id}", name="facture_index_presta_show")
     * @Method("GET")
     */
    public function PrestaOneAction(FacturePresta $presta)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('BackOffice/facture/presta.html.twig', array(
                'presta' => $presta,
                'titre' => $presta,
        ));
    }
    
    
    
    
    
       
    /**
     * Lists all facture entities.
     *
     * @Route("/year/{year}", name="facture_index_year")
     * @Method("GET")
     */
    public function YearAction($year)
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('Pericles3Bundle:Facture')->findYear($year);
        return $this->render('BackOffice/facture/liste.html.twig', array(
            'factures' => $factures,
            'titre' => $year,
        ));
    }
    
    
    
    
    

    
    /**
     * Lists all facture entities.
     *
     * @Route("/etab/alier", name="facture_index_etab_alier")
     * @Method("GET")
     */
    public function findEtabAlier()
    {
        $em = $this->getDoctrine()->getManager();

        $factures = $em->getRepository('Pericles3Bundle:Facture')->findEtabAlier();
       return $this->render('BackOffice/facture/index.html.twig', array(
            'factures' => $factures,
        ));
    }

    
    /**
     * Lists all facture entities.
     *
     * @Route("/afaire", name="facture_index_afaire")
     * @Method("GET")
     */
    public function FactureAFaire()
    {
        $em = $this->getDoctrine()->getManager();
        
        $gestionnaires_sans_facture = $em->getRepository('Pericles3Bundle:Gestionnaire')->findSansFacture();
        $etablissements_sans_facture = $em->getRepository('Pericles3Bundle:Etablissement')->findReelsSansFacture();
        $etablissements_facture_afaire= $em->getRepository('Pericles3Bundle:Etablissement')->findWithLastFacture();

        return $this->render('BackOffice/facture/facture_a_faire.html.twig', array(
            'gestionnaires_sans_facture' => $gestionnaires_sans_facture,
            'etablissements_sans_facture' => $etablissements_sans_facture,
            'etablissements_facture_afaire' => $etablissements_facture_afaire,
        ));
    }
   
    /**
     * Lists all facture entities.
     *
     * @Route("/renew", name="facture_renew")
     * @Method("GET")
     */
    public function findFactureARenouveller()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('Pericles3Bundle:Facture')->findFactureARenouvellerNum();
        return $this->render('BackOffice/facture/facture_arenew.html.twig', array('factures' => $factures,'titre' => "A renouveller"));
        
//        return $this->render('BackOffice/facture/liste.html.twig', array('factures' => $factures,'titre' => "A renouveller"));
    }
    
    
    
    
    
    
    /**
     * Lists all facture entities.
     *
     * @Route("/apayer", name="facture_apayer")
     * @Method("GET")
     */
    public function FactureAPaye()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('Pericles3Bundle:Facture')->findNonPayee();
        return $this->render('BackOffice/facture/liste.html.twig', array('factures' => $factures,'titre' => "Factures à payer"));
    }
 
    /**
     * Lists all facture entities.
     *
     * @Route("/nonfinalise", name="facture_nonfinalisee")
     * @Method("GET")
     */
    public function FactureAFinaliser()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository('Pericles3Bundle:Facture')->findNonFinalisee();
        return $this->render('BackOffice/facture/liste.html.twig', array('factures' => $factures,'titre' => "Factures à finaliser"));
    }
 
    
    /**
     * Creates a new facture entity.
     *
     * @Route("/{numFacture}/finalise", name="facture_finalise")
     * @Method({"GET", "POST"})
     */
    public function FinaliseAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();       
        $facture->setFinalise(true);
        $em->persist($facture);
        $em->flush();
        
        if ($facture->getGenere())
        {
           $this->GenerePDF($facture);
        }
        $this->AddFlash("success","La facture a été finalisée");
        return $this->redirectToRoute('facture_show', array('numFacture' => $facture->getNumfacture()));

    }
    
       
    /**
     * Creates a new facture entity.
     *
     * @Route("/{numFacture}/unfinalise", name="facture_unfinalise")
     * @Method({"GET", "POST"})
     */
    public function UnFinaliseAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();       
        $facture->setFinalise(false);
        if ($facture->getGenere())
        {
            $facture->setFileName(null);
        }
        $em->persist($facture);
        $em->flush();
        $this->AddFlash("success","La facture est maintenant ouvert aux modification");
        return $this->redirectToRoute('facture_show', array('numFacture' => $facture->getNumfacture()));
    }
    
    
     /**
     * Creates a new facture qsdqsd etab.
     *
     * @Route("/new_from_etab/{id}", name="facture_new_from_etab")
     * @Method({"GET"})
     */
    public function newFromEtabAction(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $em = $this->getDoctrine()->getManager();       
        $newFacture = new Facture();
        $newFacture->setConcerneGestionnaire(false);
        $newFacture->setEtablissement($etablissement);;
        $newFacture->setGenere(true);
        $newFacture->setNonRenouvelable(false);
        $newFacture->setDateEmission(new \DateTime());
        $newFacture->setFinalise(false);
        $newFacture->setNumFacture($this->GetNextFactureNum());
        $newPresta = new FacturePresta();
        $newPresta->setEtablissement($etablissement);
        $newPresta->setMontant($etablissement->getMontantFirst());
        $newFacture->addFacturePresta($newPresta);
        $newPresta->setRenouvellement(false);
        $em->persist($newPresta);
        $em->persist($newFacture);
        $em->flush();
        $datefin=$newPresta->getDateFinCalcule();
        $newPresta->setDateFin($datefin);
        $em->persist($newPresta);
        $em->flush();
        
        
        if ($etablissement->GetStockageEtablissement()->GetMontant())
        {
            $newPresta = new FacturePresta();
            $newPresta->setEtablissement($etablissement);
            $newPresta->setMontant($etablissement->GetStockageEtablissement()->GetMontant());
            $newFacture->addFacturePresta($newPresta);
            $newPresta->setRenouvellement(0);
            $newPresta->setCommentaire("Espace additionnel : ".$etablissement->GetStockageEtablissement());
            $newPresta->setDateFin($datefin);
            $em->persist($newFacture);
            $em->persist($newPresta);
            $em->flush();
        }

        
        $this->AddFlash("success","Nouvelle Facture crée ! ".$this->GetNextFactureNum());

        return $this->redirectToRoute('facture_show', array('numFacture' => $newFacture->getNumfacture()));
    }
    
    
     /**
     * Creates a new facture qsdqsd etab.
     *
     * @Route("/new_from_gestionnaire/{id}", name="facture_new_from_gestionnaire")
     * @Method({"GET"})
     */
    public function newFromGEstionnaireAction(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire)
    {
        $newFacture=$this->FactureGestionnaire($gestionnaire,$gestionnaire->getEtablissements());
        return $this->redirectToRoute('facture_show', array('numFacture' => $newFacture->getNumfacture()));
    }
    
    
     /**
     * Creates a new facture qsdqsd etab.
     *
     * @Route("/new_from_gestionnaire/{id}/etab_sans_fact", name="facture_new_from_gestionnaire_etab_sans_fact")
     * @Method({"GET"})
     */
    public function newFromGEstionnaireEtabSansFactAction(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire)
    {
        $newFacture=$this->FactureGestionnaire($gestionnaire,$gestionnaire->getEtablissementsSansPrestas());
        return $this->redirectToRoute('facture_show', array('numFacture' => $newFacture->getNumfacture()));
    }
    
    
    
    
    
    public function FactureGestionnaire($gestionnaire, $etablissements)
    {
         $em = $this->getDoctrine()->getManager();       
        $newFacture = new Facture();
        $newFacture->setConcerneGestionnaire(true);
        $newFacture->setGestionnaire($gestionnaire);
        $newFacture->setGenere(true);
        $newFacture->setNonRenouvelable(false);
        $newFacture->setDateEmission(new \DateTime());
        $newFacture->setFinalise(false);
        $newFacture->setNumFacture($this->GetNextFactureNum());
        $em->persist($newFacture);
        
        
        foreach ($etablissements as $etablissement)
        {
            $newPresta = new FacturePresta();
            $newPresta->setEtablissement($etablissement);
            $newPresta->setMontant($etablissement->getMontantFirst());
            $newFacture->addFacturePresta($newPresta);
            $newPresta->setRenouvellement(false);
            $em->persist($newPresta);
            $em->flush();
            $datefin=$newPresta->getDateFinCalcule();
            $newPresta->setDateFin($datefin);
            $em->persist($newPresta);
            $em->flush();
             
            
            
        if ($etablissement->GetStockageEtablissement()->GetMontant())
        {
            $newPresta = new FacturePresta();
            $newPresta->setEtablissement($etablissement);
            $newPresta->setMontant($etablissement->GetStockageEtablissement()->GetMontant());
            $newFacture->addFacturePresta($newPresta);
            $newPresta->setRenouvellement(0);
            $newPresta->setCommentaire("Espace additionnel : ".$etablissement->GetStockageEtablissement());
            $newPresta->setDateFin($datefin);
            $em->persist($newFacture);
            $em->persist($newPresta);
            $em->flush();
        }

            
        }
        $em->flush();
        $this->AddFlash("success","Nouvelle Facture crée ! ".$this->GetNextFactureNum());
        return($newFacture);
    }
    
    
    
    
    
    
    
    
    
    /**
     * Creates a new facture entity.
     *
     * @Route("/new_from_facture/{numFacture}", name="facture_new_fromfacture")
     * @Method({"GET", "POST"})
     */
    public function newFromFactureAction(Facture $facture,Request $request)
    {
        $em = $this->getDoctrine()->getManager();       
        $newFacture = new Facture();
        $newFacture->setConcerneGestionnaire($facture->getConcerneGestionnaire());
        if ($facture->getConcerneGestionnaire())
        {
            $newFacture->setGestionnaire($facture->getGestionnaire());
        }
        else
        {
            $newFacture->setEtablissement($facture->getEtablissement());;
        }
        
        $newFacture->setGenere(true);
        $newFacture->setNonRenouvelable(false);
        $newFacture->setDateEmission(new \DateTime());
        $newFacture->setFinalise(false);
        $newFacture->setNumFacture($this->GetNextFactureNum());

        $em->persist($newFacture);
        $em->flush();
        
        foreach ($facture->getFacturePrestas() as $presta )
        {
            if ($presta->getEtablissement() && $presta->isLastFacturePresta())
            {
                $newPresta = new FacturePresta();
                $newPresta->setEtablissement($presta->getEtablissement());
                $newPresta->setMontant($presta->getEtablissement()->getMontantRenew());
                $newFacture->addFacturePresta($newPresta);
                $newPresta->setRenouvellement($presta->getRenouvellement()+1);
                
                $em->persist($newPresta);
                $em->persist($newFacture);
                $datefin=$newPresta->getDateFinCalcule();
                $newPresta->setDateFin($datefin);
                $em->persist($newPresta);
                if ($presta->getEtablissement()->GetStockageEtablissement()->GetMontant())
                {
                    $newPresta = new FacturePresta();
                    $newPresta->setEtablissement($presta->getEtablissement());
                    $newPresta->setMontant($presta->getEtablissement()->GetStockageEtablissement()->GetMontant());
                    $newFacture->addFacturePresta($newPresta);
                    $newPresta->setRenouvellement($presta->getRenouvellement()+1);
                    $newPresta->setCommentaire("Espace additionnel : ".$presta->getEtablissement()->GetStockageEtablissement());
                    $newPresta->setDateFin($datefin);
                    $em->persist($newFacture);
                    $em->persist($newPresta);
                }
                
            }   
            $em->flush();
        }
        
        
        $this->AddFlash("success","---".$this->GetNextFactureNum());
        
        return $this->redirectToRoute('facture_show', array('numFacture' => $newFacture->getNumfacture()));

    }

    function GetNextFactureNum()
    {
        $em = $this->getDoctrine()->getManager();
        $lastFacture = $em->getRepository('Pericles3Bundle:Facture')->findLastFactureNum();
        $numLastFacture=$lastFacture['numFacture'];
        $last_year_facture=substr($numLastFacture,0,4);
        $current_year=date("Y");
        
        if ($current_year==$last_year_facture)
        {
            $intNumFacture=substr($numLastFacture,12);
            $numFacture=$current_year."_". strtoupper($this->getParameter('application_name'))."_".str_pad($intNumFacture+1,3,"0", STR_PAD_LEFT);
        }
        else
        {
            $numFacture=$current_year."_". strtoupper($this->getParameter('application_name'))."_001";
        }
        return($numFacture);
    }
    
    
    function GenerePDF(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $view = $this->renderView('BackOffice/facture/facture/facture_'.strtolower($this->getParameter('application_name')).'.html.twig',  array('facture'=>$facture));
        $dompdf = new DOMPDF();
        $dompdf->load_html($view);
        $dompdf->render();
        $filename=$facture.".pdf";
        $file_to_save=$this->GetFolderFacture($facture)."/".$filename;
        file_put_contents($file_to_save, $dompdf->output());
        $facture->setFileName($filename);
        $em->persist($facture);
        $em->flush();
    }
            
    
    
    /**
     * Creates a new facture entity.
     *
     * @Route("/new", name="facture_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $facture = new Facture();

        $facture->setNumFacture($this->GetNextFactureNum());

        $form = $this->createForm('Pericles3Bundle\Form\FactureType', $facture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            
            $em = $this->getDoctrine()->getManager();
            $facture->setFinalise(false);
            $facture->setGenere(true);

            $em->persist($facture);
            $em->flush();

            return $this->redirectToRoute('facture_show', array('numFacture' => $facture->getNumfacture()));
        }

        return $this->render('BackOffice/facture/new.html.twig', array(
            'facture' => $facture,
            'form' => $form->createView(),
        ));
    }

    
    
    
    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{numFacture}/file", name="facture_get_pdf")
     * @Method("GET")
     */
    public function showPdfAction(Facture $facture)
    {
        $file = $this->GetFolderFacture($facture)."/".$facture.".pdf";
        return new BinaryFileResponse($file);
    }
    
    public function GetFolderFacture(Facture $facture)
    {
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../factures/'.$facture->GetYear();
        if (! file_exists($publicResourcesFolderPath)) 
        {
            mkdir($publicResourcesFolderPath, 0777, true);
        }
        return ($publicResourcesFolderPath);
    }
    
    
    
    
    
     
    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{numFacture}/delete", name="facture_delete")
     * @Method("GET")
     */
    public function DeleteFactuAction(Facture $facture)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($facture);
            $em->flush();
            $this->AddFlash("success","La facture à bien été supprimée");
            return $this->redirectToRoute('facture_index');
    }
    
    
    
    
    /**
     * Deletes a facture entity.
     *
     * @Route("/delete_force/presta_{id}", name="facture_delete_presta")
     * @Method("GET")
     */
    public function deletePrestaAction(\Pericles3Bundle\Entity\FacturePresta $presta)
    {
        $numFacure=$presta->getFacture()->getNumfacture();
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_COMPTA_EDIT'))  
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($presta);
            $em->flush();
            $this->AddFlash("success","La facture presta a bien été supprimée");
        }
        else
        {
            $this->AddFlash("error","Vous n'avez pas les droits sufficants");
        }
            return $this->redirectToRoute('facture_show', array('numFacture' => $numFacure));
        
    }
 
    
    
    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{numFacture}/ficher", name="facture_show_pdf")
     * @Method({"GET", "POST"})
     */
    public function showPDFPreAction(Facture $facture)
    {
        return $this->render('BackOffice/facture/facture/facture_'.strtolower($this->getParameter('application_name')).'.html.twig', array(
            'facture' => $facture
        ));
    }
    
    
    
    
    /**
     * Annule la facture
     *
     * @Route("/fact_{numFacture}/avoir", name="facture_avoir")
     * @Method({"GET", "POST"})
     */
    public function avoirAction(Facture $facture)
    {

      $em = $this->getDoctrine()->getManager();
         

        $this->AddFlash("success","La facure a bien été supprimée (AVOIR)");
        $this->AddFlash("error","Pensez a passer les établissement/gestionnaire en mode <i>Fin de contrat</i>");
        $facture->setCommentaire($facture->GetCommentaire()." - Facture annulée par ".$this->GetUser()." le ".date("d/m/Y"));
        $facture->setNonRenouvelable(true);
        $facture->setFinalise(2);
        $em->persist($facture);
        
        $em->flush();

                
        return $this->redirectToRoute('facture_show', array('numFacture' => $facture->getNumfacture()));
    
    }
    
    
    /**
     * Finds and displays a facture entity.
     *
     * @Route("/fact_{numFacture}", name="facture_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request,Facture $facture)
    {
        
       $em = $this->getDoctrine()->getManager();
       
         if ($request->getMethod() == 'POST') 
        {
//            $this->AddFlash("success","POST");
            if ($request->get('type') == 'add_module_gestionnaire_free') 
            {
                $presta = new \Pericles3Bundle\Entity\FacturePresta();
                $presta->SetMontant(0);
                $presta->setRenouvellement(0);
                $presta->SetFacture($facture);
                $presta->SetGestionnaire($facture->getConcerne());
                $em->persist($presta);
                $em->flush();
                $presta->setDateFin($presta->getDateFinCalcule());
                $em->persist($presta);
                $em->flush();
            }
            if ($request->get('type') == 'add_module_gestionnaire_montant') 
            {
                $presta = new \Pericles3Bundle\Entity\FacturePresta();
                $presta->SetMontant($request->get('montant'));
                $presta->SetFacture($facture);
                $presta->setRenouvellement(0);
                $presta->SetGestionnaire($facture->getConcerne());
                $em->persist($presta);
                $em->flush();
                $presta->setDateFin($presta->getDateFinCalcule());
                $em->persist($presta);
                $em->flush();
            }
            if ($request->get('type') == 'add_module_etablisement') 
            {
                
                
                $this->AddFlash("success","ICIII");
                
                

                
                $etablissement = $em->getRepository('Pericles3Bundle:Etablissement')->findOneById($request->get('etab_id'));

                $presta = new \Pericles3Bundle\Entity\FacturePresta();
                $presta->SetMontant($request->get('montant'));
                $presta->SetFacture($facture);

                $this->AddFlash("success","Version :".$request->get('version'));
                
                $presta->setRenouvellement($request->get('version'));
                $presta->SetEtablissement($etablissement);
                $em->persist($presta);
                $em->flush();
                $presta->setDateFin($presta->getDateFinCalcule());
                $em->persist($presta);
                $em->flush();                
            }
            if ($request->get('type') == 'add_module_etablisement_stockage') 
            {
                $etablissement = $em->getRepository('Pericles3Bundle:Etablissement')->findOneById($request->get('etab_id'));
                $presta = new \Pericles3Bundle\Entity\FacturePresta();
                $presta->SetMontant($etablissement->GetStockageEtablissement()->getMontant());
                $presta->SetFacture($facture);
                $presta->setCommentaire("Espace additionnel : ".$etablissement->GetStockageEtablissement());
                $presta->setRenouvellement($request->get('version'));
                $presta->SetEtablissement($etablissement);
                $em->persist($presta);
                $em->flush();
                $presta->setDateFin($presta->getDateFinCalcule());
                $em->persist($presta);
                $em->flush();     
            }
            if ($request->get('type') == 'add_paiement') 
            {
                $moyendepaiement = $em->getRepository('Pericles3Bundle:FactureMoyenPaiement')->findOneById($request->get('optradio'));
                $facture->setMoyenPaiement($moyendepaiement);
                $facture->setPayele(new \DateTime($request->get('dateDePaiement')));
                $em->persist($moyendepaiement);
                $em->flush();
                $this->AddFlash("success",$moyendepaiement);
            }            
            if ($request->get('type') == 'add_relance') 
            {
                $relance = new \Pericles3Bundle\Entity\FactureRappel();
                $relance->setDateRappel(new \DateTime($request->get('dateRelance')));
                $relance->setFacture($facture);
                $relance->setLibelle($request->get('optradio'));
                if ($request->get('commentaire'))
                {
                    $relance->setLibelle($relance->getLibelle()." - ".$request->get('commentaire'));
                }
                $em->persist($relance);
                $em->flush();
                $this->AddFlash("success","Une relance a été enregistrée");
            }
            return $this->redirectToRoute('facture_show', array('numFacture' => $facture->getNumfacture()));
            
        }

        $lastFacture = $em->getRepository('Pericles3Bundle:Facture')->findLastFactureNum();

        
        return $this->render('BackOffice/facture/show.html.twig', array(
            'facture' => $facture,
            'lastFacture' => $lastFacture
        ));
    }

    /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/{numFacture}/edit", name="facture_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Facture $facture)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\FactureType', $facture);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

//            return $this->redirectToRoute('facture_index_etab_alier');
            return $this->redirectToRoute('facture_show', array('numFacture' => $facture->getNumfacture()));
        }

        return $this->render('BackOffice/facture/edit.html.twig', array(
            'facture' => $facture,
            'edit_form' => $editForm->createView(), 
        ));
    }

     /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/presta/{id}/edit", name="facture_presta_edit")
     * @Method({"GET", "POST"})
     */
    public function editPrestaAction(Request $request, FacturePresta $facturePresta)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\FacturePrestaType', $facturePresta);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('facture_index_presta_show', array('id' => $facturePresta->getId()));
        }
        
        $prestas=$facturePresta->getConcerne()->GetFacturePrestas();
        
        

        return $this->render('BackOffice/facture/edit_presta.html.twig', array(
            'prestas' => $prestas,
            'facture' => $facturePresta->getFacture(),
            'facture_presta' => $facturePresta,
            'edit_form' => $editForm->createView(), 
        ));
    }

    
    
    
    
}
