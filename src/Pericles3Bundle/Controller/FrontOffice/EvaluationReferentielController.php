<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


use Pericles3Bundle\Entity\CommentaireDomaine;
use Pericles3Bundle\Entity\DomaineObjectifStrategique;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\Referentiel;
use Pericles3Bundle\Entity\ReferentielPublic;
use Pericles3Bundle\Entity\Constat;
use Pericles3Bundle\Entity\Critere;
use Pericles3Bundle\Entity\Preuve;
use Pericles3Bundle\Entity\ObjectifOperationnel;
use \stdClass;


use Pericles3Bundle\Form\ObjectifOperationnelType;


/**
 * Evaluation controller.
 *
 * @Route("/eval")
 */
class EvaluationReferentielController extends Controller
{
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/referentiel/ref_public_{id}", name="pericles3_referentiel_ref")
     * @Method("GET")
     */    
    public function indexReferentielRefAction(ReferentielPublic $referentielPublic)
    {
        if (! $referentielPublic)
        {
            throw $this->createNotFoundException("Le référentiel n'existe pas");
        }
        
        return $this->render('Evaluation/Referentiel/referentielPublic.html.twig', 
                array('referentielPublic'=> $referentielPublic, 
                'etablissements'=> $this->GetUser()->getEtablissementsByRefPublic($referentielPublic) 
                    ));
    }
    
    
    
    
    
 
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/domaine/ref_{id}", name="pericles3_domaine_ref")
     * @Method({"GET", "POST"})
     */    
    public function indexDomaineRefAction(Referentiel $referentiel, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $formOO = $this->createForm('Pericles3Bundle\Form\DomaineObjectifStrategiqueType',null,['domaine_defined'=>true] );
        $formOO->handleRequest($request);
        $form_commentaire = $this->createForm('Pericles3Bundle\Form\CommentaireDomaineType',null);
        $form_commentaire->handleRequest($request);

        if ($formOO->getData())
        {
            $etablissements = $this->GetEtablissementsFromForm($request);
            foreach ($etablissements as $etablissement)
            {
                    $domaine=$referentiel->getDomaineEtablissement($etablissement);

                    $domaineObjectifStrategique = new DomaineObjectifStrategique();
                    $domaineObjectifStrategique->setUser($this->getUser());
                    $domaineObjectifStrategique->setDateCreate(new \DateTime(date("Y-m-d H:i:s")));
                    $domaineObjectifStrategique->setEtablissement( $etablissement);
                    $domaineObjectifStrategique->setDateEcheance($formOO->getData()->getDateEcheance());
                    $domaineObjectifStrategique->setCommentaire($formOO->getData()->getCommentaire());
                    $domaineObjectifStrategique->setStatut($formOO->getData()->getStatut());
                    $domaineObjectifStrategique->setDomaine($domaine);
                    $em->persist($domaineObjectifStrategique);
                    $em->flush();
            }
            $formOO = $this->createForm('Pericles3Bundle\Form\DomaineObjectifStrategiqueType',null,['domaine_defined'=>true] );
        }
        
        
        if ($form_commentaire->getData())
        {
            $etablissements = $this->GetEtablissementsFromForm($request);
            foreach ($etablissements as $etablissement)
            {
                    $domaine=$referentiel->getDomaineEtablissement($etablissement);
                    $commentaireDomaine = new CommentaireDomaine();
                    $commentaireDomaine->setCommentaire($form_commentaire->getData()->getCommentaire());
                    $commentaireDomaine->setDateCreate(new \DateTime());
                    $commentaireDomaine->setUser($this->getUser());
                    $domaine->addCommentaire($commentaireDomaine);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($commentaireDomaine);
                    $em->flush();
            }
            $formOO = $this->createForm('Pericles3Bundle\Form\DomaineObjectifStrategiqueType',null,['domaine_defined'=>true] );
        }
        
            
        
        if (! $referentiel)
        {
            throw $this->createNotFoundException("Le domaine n'existe pas");
        }
        foreach ($referentiel->getDomaines() as $dom)
        {
            if ($this->GetUser()->ADroitEtablissement($dom->GetEtablissement()))
            {
                $domaines[]=$dom;
            }
        }
        
         $ObjectifsStrategiques = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:DomaineObjectifStrategique')->findByGestionnaireReferentiel($this->getUser(),$referentiel);
         $syntheses = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:CommentaireDomaine')->findByDomaineRefUser($this->getUser(),$referentiel);
         $preuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve')->findByGestionnairePDV($this->getUser(),$referentiel);
         

        
        return $this->render('Evaluation/Referentiel/domaine.html.twig', 
                array('domaine_referentiel'=> $referentiel, 'domaines_byref'=> $domaines, 'ObjectifsStrategiques' =>$ObjectifsStrategiques , 'syntheses'=>$syntheses, "preuves"=>$preuves, 'form_commentaire'=>$form_commentaire->createView(), 'formOO'=>$formOO->createView()));
    }
    
    
    
    
    
    /**
     * New OO for Criter ref
     *
     * @Route("/domaine_ref_{id}/objectifs/add", name="pericles3_domaine_ref_eval_objectifs_add")
     * @Method({"GET", "POST"})
     */    
    public function indexDomaineRefObjectifsAdAction(Referentiel $referentiel, Request $request)
    { 
         $em = $this->getDoctrine()->getManager();
        $etablissements = $this->GetEtablissementsFromForm($request);
        foreach ($etablissements as $etablissement)
        {
                $critere=$referentiel->getCritereEtablissement($etablissement);
                $objectifOperationnel = new ObjectifOperationnel();
            
                $objectifOperationnel->setTitre($request->get('titre'));
                $objectifOperationnel->setEtablissement($etablissement);
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


                $objectifOperationnel->addCritere($critere);
                $critere->addObjectif($objectifOperationnel);

                $em->persist($objectifOperationnel);
                $em->flush();
                $this->addFlash('success', "Objectif crée ! ");
                     
            }
         $this->addFlash('success', "Objectif créesqsssssssss ! ");
        return $this->redirectToRoute('pericles3_critere_ref_eval_objectifs',array('id' => $referentiel->GetId()));
        
        
    }
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}", name="pericles3_critere_ref")
     * @Method("GET")
     */    
    public function indexCritereRefAction(Referentiel $referentiel)
    {
        if (! $referentiel)
        {
            throw $this->createNotFoundException("Le critere n'existe pas");
        }
        foreach ($referentiel->getCriteres() as $crit)
        {
            if ($this->GetUser()->ADroitEtablissement($crit->GetEtablissement()))
            {
                $criteres[]=$crit;
            }
        }
        return $this->render('Evaluation/Referentiel/critere/critere.html.twig', 
                array('critere_referentiel'=> $referentiel, 'criteres_byref'=> $criteres, 
                    ));
    }
    
    
    
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}/eval", name="pericles3_critere_ref_eval")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefEvalAction(Referentiel $referentiel, Request $request)
    {
        $em=$this->getDoctrine()->getManager();

        if (! $referentiel)
        {
            throw $this->createNotFoundException("Le critere n'existe pas");
        }
        foreach ($referentiel->getCriteres() as $crit)
        {
            if ($this->GetUser()->ADroitEtablissement($crit->GetEtablissement()))
            {
                $criteres[]=$crit;
            }
        }
         
        
        if ($request->get('change_note'))
        {
            foreach ($criteres as $critere)
            {
                $etablissements[]=$critere->GetEtablissement();
                $critere->SetNote($request->get('note_'.$critere->GetId()));
                $em->persist($critere);
//                $this->AddFlash("success","critere -> GetNote : ".$critere->GetNote());
            }
            $em->flush();
            $this->AddFlash("success","Les notes des critères ont bien été modifiées");
            foreach ($etablissements as $etablissement ) {
                $etablissement->SetNbCriteresNotesCache($etablissement->getNbCriteresWithNote());
                $em->persist($etablissement);
                $em->flush();
            }
            

            
            
            return $this->redirectToRoute('pericles3_critere_ref',array('id' => $referentiel->GetId()));
        }
                
        
        return $this->render('Evaluation/Referentiel/critere/critere_eval.html.twig', 
                array('critere_referentiel'=> $referentiel, 'criteres_byref'=> $criteres, 
                    ));
    }
    
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}/gantt", name="pericles3_critere_ref_eval_gantt")
     * @Method({"GET"})
     */    
    public function indexCritereRefObjectifsGanttAction(Referentiel $referentiel)
    {
        $em=$this->getDoctrine()->getManager();

        $objectifOperationnels = $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->findByCritereRefGestionnaire($this->getUser(),$referentiel);
        return $this->render('Evaluation/Referentiel/critere/critere_gantt.html.twig', 
                array('critere_referentiel'=> $referentiel, 'objectifOperationnels' => $objectifOperationnels
                    ));
    }
    
     
    
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}/objectifs", name="pericles3_critere_ref_eval_objectifs")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefObjectifsEvalAction(Referentiel $referentiel, Request $request)
    {
        $em=$this->getDoctrine()->getManager();

        if (! $referentiel)
        {
            throw $this->createNotFoundException("Le critere n'existe pas");
        }
        $objectifOperationnels = $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->findByCritereRefGestionnaire($this->getUser(),$referentiel);
        return $this->render('Evaluation/Referentiel/critere/critere_objectifs.html.twig', 
                array('critere_referentiel'=> $referentiel, 'objectifOperationnels' => $objectifOperationnels
                    ));
    }
    
    
       
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}/objectifs_list", name="pericles3_critere_ref_eval_objectifs_list")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefObjectifsListEvalAction(Referentiel $referentiel, Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $objectifOperationnels = $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->findByCritereRefGestionnaire($this->getUser(),$referentiel);
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig',array('critere_referentiel'=> $referentiel, 'objectifOperationnels' => $objectifOperationnels));
    }

    
    
    
    
    /**
     * New OO for Criter ref
     *
     * @Route("/critere_ref_{id}/objectifs/add", name="pericles3_critere_ref_eval_objectifs_add")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefObjectifsAdAction(Referentiel $referentiel, Request $request)
    { 
         $em = $this->getDoctrine()->getManager();
        $etablissements = $this->GetEtablissementsFromForm($request);
        foreach ($etablissements as $etablissement)
        {
                $critere=$referentiel->getCritereEtablissement($etablissement);
                $objectifOperationnel = new ObjectifOperationnel();
            
                $objectifOperationnel->setTitre($request->get('titre'));
                $objectifOperationnel->setEtablissement($etablissement);
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


                $objectifOperationnel->addCritere($critere);
                $critere->addObjectif($objectifOperationnel);

                $em->persist($objectifOperationnel);
                $em->flush();
                $this->addFlash('success', "Objectif crée ! ");
                     
            }
         $this->addFlash('success', "Objectif créesqsssssssss ! ");
        return $this->redirectToRoute('pericles3_critere_ref_eval_objectifs',array('id' => $referentiel->GetId()));
        
        
    }
    
    
    
    
    /**
     * New OO for Criter ref
     *
     * @Route("/critere_ref_{id}/preuve/add", name="pericles3_critere_ref_eval_preuve_add")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefPreuveAdAction(Referentiel $referentiel, Request $request)
    { 
        $em = $this->getDoctrine()->getManager();
        $etablissements = $this->GetEtablissementsFromForm($request);
        if ($request->get('bibliotheque_gestionnaire'))
        {
            $bibliotheque= $em->getRepository('Pericles3Bundle:Bibliotheque')->findOneById($request->get('bibliotheque_gestionnaire'));
        }
        
        foreach ($etablissements as $etablissement)
        {
                $critere=$referentiel->getCritereEtablissement($etablissement);
                $preuveQualite = new Preuve();
                $preuveQualite->setEtablissement($etablissement);
                $preuveQualite->setUser($this->GetUser());
                $preuveQualite->setBibliotheque($bibliotheque);
                $preuveQualite->setCommentaire($request->get('inputCommentairePreuve'));
                $preuveQualite->setTypePreuve("critere");
                $preuveQualite->setDateCreate(new \DateTime());
                $preuveQualite->setFichier("");
                $preuveQualite->setCritere($critere);
                $critere->addPreuve($preuveQualite);
                $em->persist($preuveQualite);
                $em->flush();
                $this->addFlash('success', "Preuve de qualité rajoutée ! ");
            }
            $this->addFlash('success', "Preuves de qualité rajoutée ! ");
        return $this->redirectToRoute('pericles3_critere_ref_eval_preuves',array('id' => $referentiel->GetId()));
    }
    /**
     * New OO for Criter ref
     *
     * @Route("/domaine/ref_{id}/addpvu", name="pericles3_domaine_ref_add_pvu")
     * @Method({"GET", "POST"})
     */    
    public function indexdomaineAddPVUAction(Referentiel $referentiel, Request $request)
    { 
        $em = $this->getDoctrine()->getManager();
        $etablissements = $this->GetEtablissementsFromForm($request);
        if ($request->get('bibliotheque_gestionnaire'))
        {
            $bibliotheque= $em->getRepository('Pericles3Bundle:Bibliotheque')->findOneById($request->get('bibliotheque_gestionnaire'));
        }
        
        foreach ($etablissements as $etablissement)
        {
                $domaine=$referentiel->getDomaineEtablissement($etablissement);
                $preuveQualite = new Preuve();
                $preuveQualite->setEtablissement($etablissement);
                $preuveQualite->setUser($this->GetUser());
                $preuveQualite->setBibliotheque($bibliotheque);
                $preuveQualite->setCommentaire($request->get('inputCommentairePreuve'));
                $preuveQualite->setTypePreuve("pdv");
                $preuveQualite->setDateCreate(new \DateTime());
                $preuveQualite->setFichier("");
                $preuveQualite->setDomaine($domaine);
                $domaine->addPreuve($preuveQualite);
                $em->persist($preuveQualite);
                $em->flush();
            }
            $this->addFlash('success', "Point de vue de l'usager rajoutés ! ");
        return $this->redirectToRoute('pericles3_domaine_ref',array('id' => $referentiel->GetId()));
    }
    
    
     

             
    
    
    
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}/constats", name="pericles3_critere_ref_eval_constats")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefConstatsEvalAction(Referentiel $referentiel, Request $request)
    {
        $em=$this->getDoctrine()->getManager();

        if (! $referentiel)
        {
            throw $this->createNotFoundException("Le critere n'existe pas");
        } 
        $constats= $em->getRepository('Pericles3Bundle:Constat')->findByCritereRefGestionnaire($this->getUser(),$referentiel);
        
        return $this->render('Evaluation/Referentiel/critere/critere_constat.html.twig', 
                array('critere_referentiel'=> $referentiel, 'constats' => $constats
                    ));
    }
     
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}/constats/add", options={"expose"=true}, name="pericles3_critere_ref_eval_constats_add")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefConstatsAddEvalAction(Referentiel $referentiel, Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $etablissements = $this->GetEtablissementsFromForm($request);
        $i=0;
        foreach ($etablissements as $etablissement)
        {
            $i++;
                $constatCommentaire = $request->get('constat');
                $critere=$referentiel->getCritereEtablissement($etablissement);
                $constat = new Constat();
                $constat->setCommentaire($constatCommentaire);
                $constat->setDateCreate(new \DateTime());
                $constat->setUser($this->getUser());
                $critere->addConstat($constat);
                $em->persist($constat);
                $em->flush();
        }
        $this->AddFlash("success","Ajout d'un constat : <b>".$request->get('constat')."</b> Pour ".$i." établissements");
        return $this->redirectToRoute('pericles3_critere_ref_eval_constats',array('id' => $referentiel->GetId()));
        /*
         $constats= $em->getRepository('Pericles3Bundle:Constat')->findByCritereRefGestionnaire($this->getUser(),$referentiel);
        return $this->render('Evaluation/Referentiel/critere/critere_constat.html.twig', 
                array('critere_referentiel'=> $referentiel, 'constats' => $constats 
                    ));
         * 
         */
    }
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/constats_edit/{id}", options={"expose"=true}, name="pericles3_critere_ref_eval_constats_edit")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefConstatsEditEvalAction(Constat $constat, Request $request)
    {
        if ($this->getUser()->ADroitEtablissement($constat->getCritere()->GetEtablissement()))
        {
            
            $constatCommentaire = $request->get('constat');
            $em=$this->getDoctrine()->getManager();
            $constat->setCommentaire("".$constatCommentaire);
            $constat->setUser($this->getUser());
            $em->persist($constat);
            $em->flush();
            $reponseConstat["date"]=$constat->getDateCreate()->format("Y-m-d h:m:s");
            $reponseConstat["user"]=$this->getUser()->getUsername();
                
            return new JsonResponse($reponseConstat);
        }
        else
        {
             throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }

         /*
        {
           
            return new JsonResponse(true);
            
        } 
          * 
          */
    }
    
    
    
    
    
     
    
    
    function GetEtablissementsFromForm($request)
    {
        $etablissements=  new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($this->getUser()->getEtablissements() as $etab)
        {
            if ($request->get('etab_'.$etab->getId())) $etablissements->add($etab);
        }
        return($etablissements);
    }

    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}/constats_only", name="pericles3_critere_ref_eval_constats_only")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefConstatsOnlyEvalAction(Referentiel $referentiel, Request $request)
    {
         $em=$this->getDoctrine()->getManager();
        $constats= $em->getRepository('Pericles3Bundle:Constat')->findByCritereRefGestionnaire($this->getUser(),$referentiel);
        return $this->render('Evaluation/Referentiel/critere/inc_constats.html.twig', 
                array('critere_referentiel'=> $referentiel, 'constats' => $constats
                    ));
    }
        
        
    /**
     * Save Note
     *
     * @Route("/{id}/deleteconstat_from_evalref", name="pericles3_delete_constat_from_eval_ref")
     * @Method({"GET", "POST"})
     */  
    public function deleteConstatAction(Constat $constat)
    {
        $Critere=$constat->getCritere()->GetReferentiel();
        $em = $this->getDoctrine()->getManager();
        $em->remove($constat);
        $em->flush();
        return $this->redirectToRoute('pericles3_critere_ref_eval_constats_only',array('id' => $Critere->GetId()));
    }

    
    

    
    
     /**
     * Index Evaluation Etablissement
     *
     * @Route("/question_ref_{id}", name="pericles3_question_ref")
     * @Method({"GET", "POST"})
     */    
    public function indexQuestionRefAction(Referentiel $referentiel, Request $request)
    {
        if (! $referentiel)
        {
            throw $this->createNotFoundException("La question 'existe pas");
        }
        foreach ($referentiel->getQuestions() as $quest)
        {
            if ($this->GetUser()->ADroitEtablissement($quest->GetEtablissement()))
            {
                $questions[]=$quest;
            }
        }
        return $this->render('Evaluation/Referentiel/question.html.twig', 
                array('question_referentiel'=> $referentiel, 'questions_byref'=> $questions, 
                    ));
    }
    
    
    
    /**
     * Save Question
     *
     * @Route("/question_ref_{id}/savequestions", name="pericles3_savequestions")
     * @Method({"GET", "POST"})
     */   
    public function saveQuestionsAction(Referentiel $referentiel,Request $request){
        $em=$this->getDoctrine()->getManager();
        
        $listQuestionsChecked = $request->get('listQuestionsChecked');
        
        foreach ($referentiel->getQuestions() as $quest)
        {
            if ($this->GetUser()->ADroitEtablissement($quest->GetEtablissement()))
            {
                $etablissements[]=$quest->GetEtablissement();
            }
        }
        $repositoryQuestion = $em->getRepository('Pericles3Bundle:Question');
        foreach ($listQuestionsChecked as $question ) {
            $currentQuestion = $repositoryQuestion->find($question['idQuestion']);
            $currentQuestion->setReponse($question['reponse']);
            $em->persist($currentQuestion);
        }
        $em->flush();
         foreach ($etablissements as $etablissement ) {
                $etablissement->SetNbQuestionsReponduesCache($etablissement->GetNbQuestionsRepondues());
                $em->persist($etablissement);
                $em->flush();
            }
        return new JsonResponse(true);
    }

    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/dimension/ref_{id}", name="pericles3_dimension_ref")
     * @Method("GET")
     */    
    public function indexDimensionRefAction(Referentiel $referentiel)
    {
        if (! $referentiel)
        {
            throw $this->createNotFoundException("La dimension n'existe pas");
        }
        foreach ($referentiel->getDimensions() as $dim)
        {
            if ($this->GetUser()->ADroitEtablissement($dim->GetEtablissement()))
            {
                $dimensions[]=$dim;
            }
        }
        return $this->render('Evaluation/Referentiel/dimension.html.twig', 
                array('dimension_referentiel'=> $referentiel, 'dimensions_byref'=> $dimensions, 
                    ));
    }
    
    
    
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/critere_ref_{id}/preuves", name="pericles3_critere_ref_eval_preuves")
     * @Method({"GET", "POST"})
     */    
    public function indexCritereRefPreuvesEvalAction(Referentiel $referentiel, Request $request)
    {
        $em=$this->getDoctrine()->getManager();

        if (! $referentiel)
        {
            throw $this->createNotFoundException("Le critere n'existe pas");
        } 
        $preuves = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve')->findByGestionnaireCritere($this->getUser(),$referentiel);
        
        return $this->render('Evaluation/Referentiel/critere/critere_preuves.html.twig', 
                array('critere_referentiel'=> $referentiel, 'preuves' => $preuves
                    ));
    }
    
    
    
    
    
    
    
}
