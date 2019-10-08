<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpKernel\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Pericles3Bundle\Entity\ObjectifOperationnel;
use Pericles3Bundle\Entity\Domaine;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\Critere;

use Pericles3Bundle\Form\ObjectifOperationnelType;

use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * ObjectifOperationnel controller.
 *
 * @Route("/paq/ooa")
 */
class ObjectifOperationnelController extends Controller
{
    
    private function getRepository()
    {
       return( $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ObjectifOperationnel'));
    }
    
    private function verificationAction($id)
    {
        $enti=$this->getRepository->find($id);
        if ($enti) {return($enti);}
        else {throw $this->createNotFoundException("L'objectif operationnel n'existe pas");}
    }
    
    private function GetObjectifsOperationnels($type="",$etablissement=null)
    {
        if (! $this->getUser())
        {
             throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
        else
        {
            if (! $etablissement) $etablissement = $this->getUser()->getEtablissement();
            
            if ($etablissement)
            {
                $objectifOperationnels = $this->getRepository()->findByEtablissement($etablissement,"",$type);
            }
            else
            {
                $objectifOperationnels = $this->getRepository()->findByGestionnaire($this->getUser(),"",$type);
            }

            return($objectifOperationnels);
        }   
    }
    private function GetObjectifsStrategiques($type="",$etablissement=null)
    {
        if (! $this->getUser())
        {
             throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
        else
        {
            if (! $etablissement) $etablissement = $this->getUser()->getEtablissement();
            if ($etablissement)
            {
                $ObjectifsStrategiques = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->findByEtablissement($etablissement);
            }
            else
            {
                $ObjectifsStrategiques =$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->findByGestionnaire($this->getUser());
            }
            return($ObjectifsStrategiques);
        }   
    }
            
  
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/gantt", name="pericles3_paq_gantt")
     * @Method("GET")
     */
    public function indexGanttAction()
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels();
        $objectifStrategiques=$this->GetObjectifsStrategiques();
        return $this->render('ObjectifsAmelioration/gantt.html.twig', array('objectifStrategiques' => $objectifStrategiques,'objectifOperationnels' => $objectifOperationnels));
    }
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/gantt/finis", name="pericles3_paq_gantt_finis")
     * @Method("GET")
     */
    public function indexGanttFinisAction()
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels("finis");
        return $this->render('ObjectifsAmelioration/gantt.html.twig', array('objectifOperationnels' => $objectifOperationnels));
    }
    
    
    /**
     * Lists all DomaineObjectifStrategique entities.
     *
     * @Route("/gantt/encours", name="pericles3_paq_gantt_encours")
     * @Method("GET")
     */
    public function indexGanttEnCoursAction()
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels("encours");
        return $this->render('ObjectifsAmelioration/gantt.html.twig', array('objectifOperationnels' => $objectifOperationnels));
    }
    
    
    
    
    
    /**
     * 
     *
     * @Route("/gantt/etablissement_{id}", name="pericles3_paq_gantt_etablissement")
     * @Method("GET")
     */
    public function indexGanttEtablissementAction(Etablissement $etablissement)
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels(null,$etablissement);
        return $this->render('ObjectifsAmelioration/gantt.html.twig', array('objectifOperationnels' => $objectifOperationnels,'etablissement'=>$etablissement));
    }
    
     
    
    
            
    
    /**
     * Lists all ObjectifOperationnel entities
     *
     * @Route("/list", name="paq_ooa_list")
     * @Method("GET")
     */    
    public function ListeAction()
    {
       $objectifOperationnels=$this->GetObjectifsOperationnels();
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels));
    }   
    
    
    /**
     * Lists all ObjectifOperationnel entities
     *
     * @Route("/list/inc", name="paq_ooa_list_inc")
     * @Method({"GET", "POST"})
     */    
    public function ListeIncAction()
    {
       $objectifOperationnels=$this->GetObjectifsOperationnels();
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnels));
    }   

    
    
    
    
    
    /**
     * Liste pour un etablissement
     *
     * @Route("/list/etablissement_{id}", name="paq_ooa_list_etablissement")
     * @Method("GET")
     */    
    public function ListeEtablissementAction(Etablissement $etablissement)
    {
       $objectifOperationnels = $this->getRepository()->findByEtablissement($etablissement);
       return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', ['objectifOperationnels' => $objectifOperationnels,'etablissement'=>$etablissement ]);
    }   
    
    
    /**
     * Lists all ObjectifOperationnel entities
     *
     * @Route("/list/finis", name="paq_ooa_list_filtre_finis")
     * @Method("GET")
     */    
    public function ListeFinisAction()
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels("finis");
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => "Finis"));
    }   
    

    
        
    /**
     * Lists encours
     *
     * @Route("/list/finis/inc", name="paq_ooa_list_filtre_finis_inc")
     * @Method({"GET", "POST"})
     */    
    public function ListeFinisIncAction()
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels("finis");
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => "Finis"));
    }   

    
        
    /**
     * Lists encours
     *
     * @Route("/list/encours/inc", name="paq_ooa_list_filtre_encours_inc")
     * @Method({"GET", "POST"})
     */    
    public function ListeEncoursIncAction()
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels("encours");
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => "En cours"));
    }   

    
        
    /**
     * Lists encours
     *
     * @Route("/list/encours", name="paq_ooa_list_filtre_encours")
     * @Method("GET")
     */    
    public function ListeEncoursAction()
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels("encours");
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => "En cours"));
    }   
    
    
    
    
    
    /**
     * Lists all ObjectifOperationnel entities
     *
     * @Route("/list/etablissement_{id}/finis", name="paq_ooa_list_etablissement_filtre_finis")
     * @Method("GET")
     */    
    public function ListeEtablissementFinisAction(Etablissement $etablissement)
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels("finis",$etablissement);
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => "Finis","etablissement"=>$etablissement));
    }   
    
        
    /**
     * Lists encours
     *
     * @Route("/list/etablissement_{id}/encours", name="paq_ooa_list_etablissement_filtre_encours")
     * @Method("GET")
     */    
    public function ListeEtablissementEncoursAction(Etablissement $etablissement)
    {
        $objectifOperationnels=$this->GetObjectifsOperationnels("encours",$etablissement);
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => "En cours","etablissement"=>$etablissement));
    }   
    
    
     
     /**
     * Lists all  entities.
     *
     * @Route("/list/domaine_{id}", name="paq_ooa_list_filtre_domaine")
     * @Method("GET")
     */
    public function ListeDomaineAction(Domaine $domaine)
    {
        if (! $this->getUser()) { throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants"); }
        $sous_titre=" ".$domaine->getNom();  
        $objectifOperationnels = $this->getRepository()->findDomaine($domaine->getEtablissement(),$domaine->getId());
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => $sous_titre,"domaine"=>$domaine));
    }
    
    
     /**
     * Lists all  entities.
     *
     * @Route("/list/domaine_{id}/inc", name="paq_ooa_list_filtre_domaine_inc")
     * @Method({"GET", "POST"})
     */
    public function ListeDomaineIncAction(Domaine $domaine)
    {
        if (! $this->getUser()) { throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants"); }
        $sous_titre=" ".$domaine->getNom();  
        $objectifOperationnels = $this->getRepository()->findDomaine($domaine->getEtablissement(),$domaine->getId());
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => $sous_titre,"domaine"=>$domaine));
    }
    
    
    
    
     /**
     * Lists all  entities.
     *
     * @Route("/list/priorite_{priorite}", name="paq_ooa_list_etablissement_filtre_priorite")
     * @Method("GET")
     */
    public function ListePrioriteAction($priorite)
    {
        if (! $this->getUser()) { throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants"); }
        $sous_titre=" Priorite ".$priorite;  
        $objectifOperationnels = $this->getRepository()->findByPriorite($this->getUser(),$priorite);
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => $sous_titre,"priorite"=>$priorite));
    }
    
        
     /**
     * Lists all  entities.
     *
     * @Route("/list/priorite_{priorite}_inc", name="paq_ooa_list_filtre_priorite_inc")
     * @Method({"GET", "POST"})
     */
    public function ListePrioriteIncAction($priorite)
    {
        if (! $this->getUser()) { throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants"); }
        $sous_titre=" Priorite ".$priorite;  
        $objectifOperationnels = $this->getRepository()->findByPriorite($this->getUser(),$priorite);
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => $sous_titre,"priorite"=>$priorite));
    }
    
        
        
     /**
     * Lists all  entities.
     *
     * @Route("/list/sans_date", name="paq_ooa_list_filtre_sansdate")
     * @Method({"GET", "POST"})
     */
    public function ListeSansDateAction()
    {
        if (! $this->getUser()) { throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants"); }
        $sous_titre="Sans date";  
        $objectifOperationnels = $this->getRepository()->findSansDate($this->getUser());
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => $sous_titre, "sansdate"=>true));
    }
    
    
     /**
     * Lists all  entities.
     *
     * @Route("/list/sans_date_inc", name="paq_ooa_list_filtre_sansdate_inc")
     * @Method({"GET", "POST"})
     */
    public function ListeSansDateIncAction()
    {
        if (! $this->getUser()) { throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants"); }
        $sous_titre="Sans date";  
        $objectifOperationnels = $this->getRepository()->findSansDate($this->getUser());
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => $sous_titre));
    }
    
        
    
        
     /**
     * Lists all  entities.
     *
     * @Route("/list/enretard", name="paq_ooa_list_filtre_enretard")
     * @Method({"GET", "POST"})
     */
    public function ListeEnRetardAction()
    {
        if (! $this->getUser()) { throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants"); }
        $sous_titre="En retard";  
        $objectifOperationnels = $this->getRepository()->findEnRetard($this->getUser());
        return $this->render('ObjectifsAmelioration/Operationnels/liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => $sous_titre, "enretard"=>true));
    }
    
    
     /**
     * Lists all  entities.
     *
     * @Route("/list/enretard/inc", name="paq_ooa_list_filtre_enretard_inc")
     * @Method({"GET", "POST"})
     */
    public function ListeEnRetardIncAction()
    {
        if (! $this->getUser()) { throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants"); }
        $sous_titre="En retard";    
        $objectifOperationnels = $this->getRepository()->findEnRetard($this->getUser());
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,"sous_titre" => $sous_titre));
    }
    
        
    
    
     
    
    /**
     * Lists all ObjectifOperationnel entities.
     *
     * @Route("/", name="paq_ooa_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $objectifOperationnels = $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->findAll();

        
        return $this->render('objectifoperationnel/index.html.twig', array(
            'objectifOperationnels' => $objectifOperationnels,
        ));
    }
    
    
    
    
    /**
     * Creates a new ObjectifOperationnel entity From critere.
     *
     * @Route("/new/critere_{id}", name="paq_ooa_new_from_critere")
     * @Method({"GET", "POST"})
     */
    public function newFromCritereAction(Critere $Critere,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $objectifOperationnel = new ObjectifOperationnel();
        if (true) {
            $objectifOperationnel->setTitre($request->get('titre'));
            $objectifOperationnel->setEtablissement($Critere->getEtablissement());
            $objectifOperationnel->setComplete(0);
            $objectifOperationnel->setDescription($request->get('description'));
            $objectifOperationnel->setMoyen($request->get('moyens'));
            $objectifOperationnel->setIndicateurs($request->get("indicateurs"));
            $objectifOperationnel->setPriorité($request->get("priorite"));
            $objectifOperationnel->setPilotéPar($request->get("pilotePar"));
            $dateDebut = $request->get('dateDebut');
            $dateFin = $request->get('dateFin');

            if ($dateDebut == '') $objectifOperationnel->setDateDebut(null);
            else $objectifOperationnel->setDateDebut(new \DateTime($dateDebut));
            if ($dateFin == '') $objectifOperationnel->setDateFin(null);
            else $objectifOperationnel->setDateFin(new \DateTime($dateFin));

            $objectifOperationnel->setDateCreate(new \DateTime());
            $objectifOperationnel->setUser($this->getUser());

            
            $objectifOperationnel->addCritere($Critere);
            $Critere->addObjectif($objectifOperationnel);
                try {
                    $em->persist($objectifOperationnel);
                    $em->flush();
                    $this->addFlash('success', "Objectif crée ! ");
                    }
                    catch (\Doctrine\DBAL\DBALException $e)
                    {
                         $this->addFlash('error', "Une erreur est survenue");
                         $objectifOperationnel->removeCritere($Critere);
                         $Critere->removeObjectif($objectifOperationnel);                    
                         return new JsonResponse(false);
                    }
            }
            return $this->redirectToRoute('pericles3_critere_liste_ooa',array('id' => $Critere->GetId()));
    }
    
    

    /**
     * Creates a new ObjectifOperationnel entity.
     *
     * @Route("/new", name="paq_ooa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        return($this->newOOA($this->getUser()->getEtablissement(),$request));
    }
    
    

    /**
     * Creates a new ObjectifOperationnel entity.
     *
     * @Route("/etablissement_{id}/new", name="paq_ooa_new_etablissement")
     * @Method({"GET", "POST"})
     */
    public function newEtablissementAction(Etablissement $etablissement,Request $request)
    {

        return($this->newOOA($etablissement,$request));
    }
    
    


    private function newOOA(Etablissement $etablissement, Request $request)
    {    
               

        $objectifOperationnel = new ObjectifOperationnel();
        $form = $this->createForm('Pericles3Bundle\Form\ObjectifOperationnelType', $objectifOperationnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $objectifOperationnel->setEtablissement($etablissement);
            $objectifOperationnel->setComplete(0);
            $objectifOperationnel->setDateCreate(new \DateTime());
            $objectifOperationnel->setUser($this->getUser());
            $em->persist($objectifOperationnel);
            $em->flush();
            return $this->redirectToRoute('paq_ooa_edit', array('id' => $objectifOperationnel->getId()));
        }
 
        return $this->render('ObjectifsAmelioration/Operationnels/new.html.twig', array(
            'objectifOperationnel' => $objectifOperationnel,
            'etablissement' => $etablissement,
            'ttt' => $etablissement,
            'form' => $form->createView(),
        ));
    }
    
    
        

    
    
    /**
     * Finds and displays a ObjectifOperationnel entity.
     *
     * @Route("/{id}", name="paq_ooa_show")
     * @Method("GET")
     */
    public function showAction(ObjectifOperationnel $objectifOperationnel)
    {
        $etablissement = $objectifOperationnel->getEtablissement();
        $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $criteres_liste = $repositoryCritere->findByEtablissement($etablissement);

        return $this->render('ObjectifsAmelioration/Operationnels/show.html.twig', array(
            'objectifOperationnel' => $objectifOperationnel,
            'criteres_liste' => $criteres_liste
        ));
    }

    
    
    
    
    
    
    /**
     * Displays a form to edit an existing ObjectifOperationnel entity.
     *
     * @Route("/{id}/edit", name="paq_ooa_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ObjectifOperationnel $objectifOperationnel)
    {
        $etablissement = $objectifOperationnel->getEtablissement();
        $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $criteres_liste = $repositoryCritere->findByEtablissement($etablissement);
        $objectifsStrategique = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->findByEtablissementAlpha($etablissement);
        if ($request->get('update')) { 
             $dateDebut = $request->get('dateDebut');
             $dateFin = $request->get('dateFin');
                if ($this->isValid($request)=='')
                {
                    if (is_numeric($request->get('objectif_stragegique')))
                    {
                        $objectifStrategique = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->findOneById($request->get('objectif_stragegique'));
                         $this->addFlash('success', $objectifStrategique);
                         $objectifOperationnel->setObjectifStrategique($objectifStrategique);

                    }
                    else
                    {
                         $objectifOperationnel->setObjectifStrategique(null);
                         $this->addFlash('success', "PAS ");
                    }
                        
                    
                    
                    $objectifOperationnel->setTitre($request->get('titre'));
                    $objectifOperationnel->setDescription($request->get('description'));
                    $objectifOperationnel->setMoyen($request->get('moyens'));
                    $objectifOperationnel->setIndicateurs($request->get("indicateurs"));
                    $objectifOperationnel->setComplete($request->get("complete"));
                    
                    $objectifOperationnel->setPriorité($request->get("priorite"));
                    $objectifOperationnel->setPilotéPar($request->get("pilotePar"));

                    if ($dateDebut == '')
                        $objectifOperationnel->setDateDebut(null);
                    else
                        $objectifOperationnel->setDateDebut(new \DateTime($dateDebut));

                    if ($dateFin == '')
                        $objectifOperationnel->setDateFin(null);
                    else
                        $objectifOperationnel->setDateFin(new \DateTime($dateFin));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($objectifOperationnel);
                    $em->flush();
                    $this->addFlash('success', "L'objectif opérationnel a bien été modifié ! ");

                    if ($request->get("complete")==100) 
                    {
                         $this->addFlash('fafini', "L'objectif opérationnel est terminé !!! ");
                        foreach ($objectifOperationnel->getCriteres() as $critere_a_revoir)
                         {
                            $critere_a_revoir->setArevoir(true);
                            $em->persist($critere_a_revoir);
                            $em->flush();
                         }
                    }
                    return $this->redirectToRoute('paq_ooa_show', array('id' => $objectifOperationnel->getId()));
                    }
            }
        
            $this->addFlash('warning', $request->get('update'));

        return $this->render('ObjectifsAmelioration/Operationnels/edit.html.twig', array(
            'objectifOperationnel' => $objectifOperationnel,
            'criteres_liste' => $criteres_liste,
            'objectifsStrategique' => $objectifsStrategique
        ));
    }
    
    public function isValid($data)
    {
        $messages_error="";
        /*
        if (!($data->get('complete')>=100 && $data->get('complete')<=100))
        {
            $messages_error['complete']="La valeur doit être entre 0 et 100";
        }
         * 
         */
        return($messages_error);
    }
    

    /**
     * Deletes a ObjectifOperationnel entity.
     *
     * @Route("/delete/{id}", name="paq_ooa_delete_get")
     * @Method({"GET", "POST"})
     */
    public function deleteGetAction(ObjectifOperationnel $objectifOperationnel)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($objectifOperationnel);
        $em->flush();
        $this->addFlash('success', "L'objectif opérationnel à bien été supprimé.");
        return $this->redirectToRoute('paq_ooa_list');
    }
    

    /**
     * Deletes a ObjectifOperationnel entity.
     *
     * @Route("/delete/{id}/ajax", name="paq_ooa_delete_ajax")
     * @Method({"GET", "POST"})
     */
    public function deleteAjaxAction(ObjectifOperationnel $objectifOperationnel)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($objectifOperationnel);
        $em->flush();
        $this->addFlash('success', "L'objectif opérationnel à bien été supprimé.");
        return new JsonResponse(true);
    }
    
    
    

    /**
     * Deletes a ObjectifOperationnel entity.
     *
     * @Route("/modal", name="paq_ooa_modal_ajax")
     * @Method({"GET", "POST"})
     */
    public function modalAjaxAction(Request $request)
    {
        $objectifOperationnel = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ObjectifOperationnel')->findOneById($request->get('id_objectif'));
        return $this->render('ObjectifsAmelioration/Operationnels/inc_modal_show.html.twig', array('objectifOperationnel' => $objectifOperationnel));
    }
    
    
    
    
    /**
     * Deletes a ObjectifOperationnel entity.
     *
     * @Route("/{id}", name="paq_ooa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ObjectifOperationnel $objectifOperationnel)
    {
        $form = $this->createDeleteForm($objectifOperationnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($objectifOperationnel);
            $em->flush();
        }

        return $this->redirectToRoute('paq_ooa_index');
    }

    /**
     * Creates a form to delete a ObjectifOperationnel entity.
     *
     * @param ObjectifOperationnel $objectifOperationnel The ObjectifOperationnel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ObjectifOperationnel $objectifOperationnel)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('paq_ooa_delete', array('id' => $objectifOperationnel->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
   
    
    /**
     * Acocie un critere à l'objectif opérationnel
     *
     * @Route("/{id}/link_critere", name="pericles3_ooa_link_critere")
     * @Method({"GET", "POST"})
     */
    public function linkCritereAction(Request $request, ObjectifOperationnel $objectifOperationnel)
    {
        $repositoryCriteres = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $Critere = $repositoryCriteres->find($request->get('add_critere'));
        $objectifOperationnel->addCritere($Critere);
        $Critere->addObjectif($objectifOperationnel);
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->persist($objectifOperationnel);
                    $em->flush();
                    $this->addFlash('success', "Le critère à bien été associé à l'objectif");
                    }
                    catch (\Doctrine\DBAL\DBALException $e)
                    {
                        if($e->getErrorCode() != 1062) {throw $e;}
                         $this->addFlash('error', "Le critère est déja associé à l'objectif");
                         $objectifOperationnel->removeCritere($Critere);
                         $Critere->removeObjectif($objectifOperationnel);                    
                    }
                    return $this->redirectToRoute('pericles3_ooa_liste_critere',array('id' => $objectifOperationnel->GetId()));
    }
    
       
    /**
     * désacocie un critere de l'objectif opérationnel
     *
     * @Route("/{id}/unlink_critere_ajax_{id_critere}", name="pericles3_ooa_unlink_critere_ajax")
     * @Method({"GET", "POST"})
     */
    public function unlinkResultsCritereAction(ObjectifOperationnel $objectifOperationnel,$id_critere)
    {
        $repositoryCriteres = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $Critere = $repositoryCriteres->find($id_critere);
        $this->unlinkCritereObjectif($objectifOperationnel,$Critere);
        return new JsonResponse(true);

//        return $this->redirectToRoute('pericles3_critere_liste_ooa',array('id' => $Critere->GetId()));
    }
    
    
               

      
    /**
     * liste les critères associées à l'objectif opérationnel.
     *
     * @Route("/last", name="pericles3_ooa_liste_inc_last")
     * @Method({"GET", "POST"})
     */
    public function listeObjectifsLastIncAction()
    {
        $em = $this->getDoctrine()->getManager();
        $etablissement = $this->getUser()->getEtablissement();
        if ($etablissement)
        {
            $objectifOperationnels =  $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->findDerniers($etablissement);
            return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnels,'etablissement'=>$etablissement));
        }
    } 
    
    
    
      
    /**
     * liste les critères associées à l'objectif opérationnel.
     *
     * @Route("/orphans", name="pericles3_ooa_liste_orphans")
     * @Method({"GET", "POST"})
     */
    public function listeObjectifsOrphansAction()
    {
        $em = $this->getDoctrine()->getManager();
        $etablissement = $this->getUser()->getEtablissement();
        if ($etablissement)
        {
            $objectifOperationnelsOrphan =  $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->findOrphans($etablissement);
            return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array('objectifOperationnels' => $objectifOperationnelsOrphan,'etablissement'=>$etablissement, 'liste_objectif_toreload'=>'liste_objectifs_orphans'));
        }
    }                         
    
    

    
    public function unlinkCritereObjectif(ObjectifOperationnel $objectifOperationnel, Critere $critere)
    {
        $objectifOperationnel->removeCritere($critere);
        $critere->removeObjectif($objectifOperationnel); 
        $em = $this->getDoctrine()->getManager();
        $em->persist($objectifOperationnel);
        $em->flush();
        $this->addFlash('success', "La critère à bien été désassocié ");
    }
    
    
    
      
    /**
     * désacocie un critere de l'objectif opérationnel
     *
     * @Route("/{id}/unlink_critere_{id_critere}", name="pericles3_ooa_unlink_critere")
     * @Method({"GET", "POST"})
     */
    public function unlinkCritereAction(Request $request, ObjectifOperationnel $objectifOperationnel,$id_critere)
    {
        $repositoryCriteres = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $Critere = $repositoryCriteres->find($id_critere);
        $objectifOperationnel->removeCritere($Critere);
        $Critere->removeObjectif($objectifOperationnel); 
        $em = $this->getDoctrine()->getManager();
        $em->persist($objectifOperationnel);
        $em->flush();
        $this->addFlash('success', "La critère à bien été désassocié ");
        return ($this->redirectToRoute('pericles3_ooa_liste_critere',array('id' => $objectifOperationnel->GetId())));
    }
   
      
    /**
     * liste les critères associées à l'objectif opérationnel.
     *
     * @Route("/{id}/liste_critere", name="pericles3_ooa_liste_critere")
     * @Method({"GET", "POST"})
     */
    public function listeCriteresAction( ObjectifOperationnel $objectifOperationnel)
    {
        $etablissement = $objectifOperationnel->getEtablissement();
        $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $criteres_liste = $repositoryCritere->findByEtablissement($etablissement);
        return $this->render('ObjectifsAmelioration/Operationnels/list_criteres.html.twig', array('objectifOperationnel' => $objectifOperationnel,'criteres_liste' => $criteres_liste));
    }
    
    
    
}
