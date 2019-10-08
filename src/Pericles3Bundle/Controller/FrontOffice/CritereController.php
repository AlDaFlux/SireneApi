<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


use Pericles3Bundle\Entity\Constat;
use Pericles3Bundle\Entity\Critere;
//use Pericles3Bundle\Entity\ObjectifOperationnel;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;



/**
 * Critere controller.
 *
 * @Route("/eval/critere")
 */
class CritereController extends Controller
{
    
    private function getRepository()
    {
        return($this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere'));
    }

    
    /**
     * Index Bibliotheque
     *
     * @Route("/{id}", name="pericles3_critere")
     * @Method("GET")
     */        
    public function indexAction($id)
    {
        if ($this->getUser())
        {
            $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
            $critere = $repositoryCritere->find($id);
            if (! $critere)
            {
                throw $this->createNotFoundException("Le critere n'existe pas");
            }
            else
            {
                $repositoryQuestion = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Question');
                $questions = $repositoryQuestion->findByCritere($critere, 'referentiel.ordre');

                if ($critere->getReferentiel()->getReferentielPublic()!=$critere->getEtablissement()->getReferentielPublic())
                {
                    $this->addFlash('error', "Vous avez été rediriger sur le nouveau référentiel ! ");
                    $critere_cible=$critere->GetEvalCible($critere->getEtablissement()->getReferentielPublic());
                    if ($critere_cible) return $this->redirectToRoute('pericles3_critere',array('id' => $critere_cible->GetId()));
                    else return $this->render('Evaluation/Critere/index.html.twig', array('critere'=> $critere));
                }
                else
                {
                    return $this->render('Evaluation/Critere/index.html.twig', array('critere'=> $critere));
                }
            }
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }


    /**
     * Save Question
     *
     * @Route("/erase_questions/crit_{id}", name="pericles3_critere_erase_questions")
     * @Method({"GET", "POST"})
     */   
    public function eraseQuestionsAction(Critere $critere){
        
        $em=$this->getDoctrine()->getManager();
        
        foreach ($critere->getQuestions() as $question)
        {
            $question->SetReponse(null);
            $em->persist($question);
        }
        $em->flush();
        $etablissement=$critere->getEtablissement();
        $etablissement->SetNbQuestionsReponduesCache($etablissement->getNbQuestionsRepondues());
        $em->persist($etablissement);
        $em->flush();
        
        $this->AddFlash("success","Les question sont maintenant considérées comme non répondues ! ");
        return $this->redirectToRoute('pericles3_critere',array('id' => $critere->GetId()));
        
        
    }

    
    /**
     * Save Question
     *
     * @Route("/savequestions/crit_{id}", name="pericles3_critere_savequestions")
     * @Method({"GET", "POST"})
     */   
    public function saveQuestionsAction(Critere $critere, Request $request){
        $em=$this->getDoctrine()->getManager();
        
        $listQuestionsChecked = $request->get('listQuestionsChecked');
        
        $etablissement=$critere->getEtablissement();
        
        
        $repositoryQuestion = $em->getRepository('Pericles3Bundle:Question');
        foreach ($listQuestionsChecked as $question ) {
            $currentQuestion = $repositoryQuestion->find($question['idQuestion']);
            $currentQuestion->setReponse($question['reponse']);
            $em->persist($currentQuestion);
        }
        $em->flush();
        
        $etablissement->SetNbQuestionsReponduesCache($etablissement->GetNbQuestionsRepondues());
        $em->persist($etablissement);
        $em->flush();
        
        
        return new JsonResponse(true);
    }


    /**
     * Save Note
     *
     * @Route("/savenote", name="pericles3_critere_savenote")
     * @Method({"GET", "POST"})
     * OBSOLETE? 
     */
    public function saveNoteAction(Request $request){
        try {
            $idCritere = $request->get('idCritere');
            $noteSelected = $request->get('note');

            $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
            $critere = $repositoryCritere->find($idCritere);
            $critere->setArevoir(false);
            $critere->setNote($noteSelected);
 
            $em = $this->getDoctrine()->getManager();
            $em->persist($critere);
            $em->flush();
            
            $etablissement=$critere->GetEtablissement();
            $etablissement->SetNbCriteresNotesCache($etablissement->GetNbCriteresNotes());
            $em->persist($etablissement);
            $em->flush();
            
            
            return new JsonResponse(true);
        } catch (\Exception $e) {
            return new JsonResponse(false);
        }
    }
     

    /**
     * Save Note
     *
     * @Route("/savenoteget/{id}", name="pericles3_critere_savenote_get")
     * @Method({"GET", "POST"})
     */
    public function saveNoteGetAction(Critere $critere, Request $request){
        $noteSelected = $request->get('radNote');
        $this->AddFlash("success","La note a bien été modifié ! Nouvelle note : ".$noteSelected);
        $pourcent=$critere->getPourcentageNon();
        $critere->setArevoir(false);
        $critere->setNote($noteSelected);

        $em = $this->getDoctrine()->getManager();
        $em->persist($critere);
        $em->flush();
        if ($critere->GetNote()>5)
        {
            if ($pourcent>80 ) $this->AddFlash("error","Le critère a plus de 80% d'éléments d'appréciations avec une réponse négative. Il n'est pas conseillé de mettre une note supérieure à 5");
            if ($critere->getNbPreuves()==0 ) $this->AddFlash("error","Il est conseillé de rajouter des <a href='#preuves'>preuves de qualité</a> pour les critères avec une note supérieure à 5");
        }
        else
        {
            if ($critere->getNbObjectifsOperationnel()==0 ) $this->AddFlash("error","Il est conseillé de rajouter <a href='#ooa'>Objectifs Opérationnels d'Amélioration </a>pour les notes inférieuresou égale à  à 5");
        }
            
            
        $etablissement=$critere->GetEtablissement();
        $etablissement->SetNbCriteresNotesCache($etablissement->getNbCriteresWithNote());
        $em->persist($etablissement);
        $em->flush();

                
        return $this->render('Evaluation/Critere/eval_critere.html.twig', array(
        'critere' => $critere
        ));


            
            
            
    }
    
    
    


    
    /**
     * Save Note
     *
     * @Route("/{id}/addconstat/", name="pericles3_critere_addConstat")
     * @Method({"GET", "POST"})
     */  
    public function addConstatAction(Critere $critere,Request $request){

        $constatCommentaire = $request->get('constat');
        $constat = new Constat();
        $constat->setCommentaire($constatCommentaire);
        $constat->setDateCreate(new \DateTime());
        $constat->setUser($this->getUser());
        $critere->addConstat($constat);
        $em = $this->getDoctrine()->getManager();
        $em->persist($constat);
        $em->flush();
        return $this->redirectToRoute('pericles3_critere_constats',array('id' => $critere->GetId()));
    }
    

    
    

    
    /**
     * Upadte Constat
     *
     * @Route("/{id}/updateconstat", name="pericles3_critere_updateConstat")
     * @Method({"GET", "POST"})
     */  
    public function updateConstatAction(Critere $critere,Request $request){

        $idConstat = $request->get('idConstat');
        $constatCommentaire = $request->get('constat');
        $repositoryConstat = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Constat');
        $constat = $repositoryConstat->find($idConstat);
        $constat->setCommentaire($constatCommentaire);
        $constat->setDateCreate(new \DateTime());
        $constat->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($constat);
        $em->flush();
        return $this->redirectToRoute('pericles3_critere_constats',array('id' => $critere->GetId()));
    }

    
    
    

    
    /**
     * Save Note
     *
     * @Route("/{id}/deleteconstat/", name="pericles3_deleteConstat")
     * @Method({"GET", "POST"})
     */  
    public function deleteConstatAction(Constat $constat)
    {
        $Critere=$constat->getCritere();
        $em = $this->getDoctrine()->getManager();
        $em->remove($constat);
        $em->flush();
        return $this->redirectToRoute('pericles3_critere_constats',array('id' => $Critere->GetId()));
    }

    
   
    
    /**
     * Acocie un critere à l'objectif opérationnel
     *
     * @Route("/{id}/link_objectif", name="pericles3_critere_link_ooa")
     * @Method({"GET", "POST"})
     */
    public function linkObjectifAction(Request $request, Critere $Critere)
    {
        //ObjectifOperationnel $objectifOperationnel
        $em = $this->getDoctrine()->getManager();

        $repositoryObjectifsOperationnel=$em->getRepository('Pericles3Bundle:ObjectifOperationnel');
        $objectifOperationnel = $repositoryObjectifsOperationnel->find($request->get('add_oo'));

        $objectifOperationnel->addCritere($Critere);
        $Critere->addObjectif($objectifOperationnel);
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
                    return $this->redirectToRoute('pericles3_critere_liste_ooa',array('id' => $Critere->GetId()));
    }
    
        
      
    /**
     * liste les critères associées à l'objectif opérationnel.
     *
     * @Route("/{id}/liste_critere", name="pericles3_critere_liste_ooa")
     * @Method({"GET", "POST"})
     */
    public function listeObjectifsAction(Critere $Critere)
    {
        //ObjectifOperationnel $objectifOperationnel
       // $etablissement = $this->getUser()->getEtablissement();
        $repositoryObjectifOperationnel  = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ObjectifOperationnel');
        $objectifs_liste = $repositoryObjectifOperationnel->findByCritere($Critere->GetId());
        return $this->render('ObjectifsAmelioration/Operationnels/inc_liste.html.twig', array("critere"=>$Critere, 'objectifOperationnels' => $objectifs_liste,'etablissement'=>$Critere->getEtablissement()));
    }                         
    
        
        /**
     * liste les critères associées à l'objectif opérationnel.
     *
     * @Route("/{id}/menu", name="pericles3_critere_menu_main")
     * @Method({"GET", "POST"})
     */
    public function menuCritereMainAction(Critere $Critere)
    {
        return $this->render('Nuts/inc_menu_critere_main.html.twig', array('critere' => $Critere));
    }
    
    
            
        /**
     * liste les critères associées à l'objectif opérationnel.
     *
     * @Route("/{id}/titre", name="pericles3_critere_titre")
     * @Method({"GET", "POST"})
     */
    public function titreCritereMainAction(Critere $Critere)
    {
        return $this->render('Evaluation/Critere/inc_titre_critere.html.twig', array('critere' => $Critere));
    }
    
    
    
        
    
    /**
     * Liste les preuves
     *
     * @Route("/constats/critere_{id}", name="pericles3_critere_constats")
     * @Method({"GET", "POST"})
     */
    public function CritereConstatsAction(Critere $Critere)
    {
        $em = $this->getDoctrine()->getManager();
        $constats =  $em->getRepository('Pericles3Bundle:Constat')->findBy(['critere' => $Critere] );
        return ($this->render('Evaluation/Critere/inc_constats.html.twig', ['critere' => $Critere, 'constats' => $constats]));
    }
    
    
    
    
    
    
}
