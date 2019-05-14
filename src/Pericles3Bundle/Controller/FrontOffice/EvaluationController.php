<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


use Pericles3Bundle\Entity\CommentaireDomaine;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\Referentiel;
use Pericles3Bundle\Entity\ReferentielPublic;
use Pericles3Bundle\Entity\Constat;
use \stdClass;


/**
 * Evaluation controller.
 *
 * @Route("/eval")
 */
class EvaluationController extends Controller
{
    
    /**
     * Index Evaluation
     *
     * @Route("/", name="pericles3_evaluation")
     * @Method("GET")
     */
    public function homeAction()
    {
        if ($this->getUser())
        {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE') )
            {
                   $etablissements=$this->getUser()->GetEtablissements();
                   return $this->render('Evaluation/index.html.twig', array('etablissements'=> $etablissements));
            }
            else 
            {
                    $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
                    $criteres_pfaibles = $repositoryCritere->findPointFaibles($this->getUser()->GetEtablissement());
                    return $this->render('Evaluation/Domaine/home.html.twig', array('criteres_pfaibles'=> $criteres_pfaibles));
            }
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
    
    
    /**
     * Index Evaluation
     *
     * @Route("/etablissements", name="evaluation_etablissements")
     * @Method("GET")
     */
    public function etblissementsEvalAction()
    {
        if ($this->getUser())
        {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE') )
            {
                   $etablissements=$this->getUser()->GetEtablissements();
                   return $this->render('Evaluation/etablissements/index.html.twig', array('etablissements'=> $etablissements));
            }
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
    
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/etablissement_{id}", name="pericles3_eval_etablissement")
     * @Method("GET")
     */
    public function EtablissementAction(Etablissement $Etablissement)
    {
        return $this->render('Evaluation/Domaine/home.html.twig', array('etablissement'=>$Etablissement,'domaines'=> $Etablissement->getDomaines()));
    }

    
    
    
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/dimension_{id}", name="pericles3_dimension")
     * @Method("GET")
     */
    public function indexDimensionAction($id)
    {
        if ($this->getUser())
        {
            $repositoryDimension = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Dimension');
            $dimension = $repositoryDimension->find($id);
            if (! $dimension)
            {
                throw $this->createNotFoundException("La dimension n'existe pas");
            }
            return $this->render('Evaluation/Dimension/index.html.twig', array('dimension'=> $dimension));
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits suffisants");
        }
    }
    
     public function listCritereAction($dimensionId,$current_critere_id=0)
    {
    	$repositoryDimension = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Dimension');
    	$dimension = $repositoryDimension->find($dimensionId);
        $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $criteres = $repositoryCritere->findByDimension($dimension, 'referentiel.ordre');
        return $this->render('Evaluation/Dimension/list_critere.html.twig', array('criteres'=> $criteres,"current_critere_id" => $current_critere_id));
    }

    public function CorpsListCritereAction($dimensionId)
    {
        $repositoryDimension = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Dimension');
        $dimension = $repositoryDimension->find($dimensionId);
        $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $criteres = $repositoryCritere->findByDimension($dimension, 'referentiel.ordre');

        return $this->render('Evaluation/Dimension/corps_list_critere.html_2.twig', array('criteres'=> $criteres));
    }


    public function listDimensionAction($id,$current_dimension_id=0)
    {
        $repositoryDomaine = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Domaine');
        $domaine = $repositoryDomaine->find($id);

        $repositoryDimension = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Dimension');
        $dimensions = $repositoryDimension->findByDomaine($domaine, 'referentiel.ordre');

        return $this->render('Evaluation/Domaine/list_dimension.html.twig', array('dimensions'=> $dimensions, 'domaine'=> $domaine,"current_dimension_id"=>$current_dimension_id));
    }

    
    
    
    
    
    
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/referentiel", name="pericles3_referentiel_ref_index")
     * @Method("GET")
     */    
    public function indexReferentielAction()
    {
        $etablissements=$this->GetUser()->GetEtablissements();
        foreach ($etablissements as $etablissement)
        {
            $etabsByRef[$etablissement->GetReferentielPublic()->GetID()][]=$etablissement;
        }
        return $this->render('Evaluation/Referentiel/index.html.twig', 
                array(
                'etablissements'=> $etablissements, 
                'etabsByRef'=> $etabsByRef 
                    ));
    }
     
    
    
    
    
    
    
 
    /**
     * Index Evaluation Etablissement
     *
     * @Route("/domaine_{id}", name="pericles3_domaine")
     * @Method("GET")
     */    
    public function indexDomaineAction($id)
    {
        $repositoryDomaine = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Domaine');
        $domaine = $repositoryDomaine->find($id);
        if (! $domaine)
        {
            throw $this->createNotFoundException("Le domaine n'existe pas");
        }
        $repositoryCritere = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Critere');
        $criteres_pfaibles = $repositoryCritere->findPointFaiblesDomaine($domaine);
        $criteres_pforts = $repositoryCritere->findPointFortsDomaine($domaine);
        return $this->render('Evaluation/Domaine/index.html.twig', array('domaine'=> $domaine,'criteres_pfaibles'=>$criteres_pfaibles,'criteres_pforts'=>$criteres_pforts));
    }

    
    
    
    
   /**
     * 
     *
     * @Route("/domaine/addcommentaire", name="pericles3_domaine_addCommentaire")
     * @Method({"GET", "POST"})
     */    
    public function addCommentaireAction(Request $request)
    {

        $idDomaine = $request->get('idDomaine');
        $commentaire = $request->get('commentaire');

        $repositoryDomaine = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Domaine');
        $domaine = $repositoryDomaine->find($idDomaine);

        $commentaireDomaine = new CommentaireDomaine();
        $commentaireDomaine->setCommentaire($commentaire);
        $commentaireDomaine->setDateCreate(new \DateTime());
        $commentaireDomaine->setUser($this->getUser());
        $domaine->addCommentaire($commentaireDomaine);

        $em = $this->getDoctrine()->getManager();
        $em->persist($commentaireDomaine);
        $em->flush();

        $result = [];
        foreach ($domaine->getCommentaires() as $comment ) {
            $commentaireToPush=new stdClass();
            $commentaireToPush->id = $comment->getId();
            $commentaireToPush->commentaire = $comment->getCommentaire();
            $commentaireToPush->username = $comment->getUser()->getUsername();
            $commentaireToPush->dateCreate = date_format($comment->getDateCreate(),"d-m-Y H:i:s");
            array_push($result, $commentaireToPush);
            }

        return new JsonResponse($result);
    }

        
    /**
     * 
     * @Route("/domaine/updatecommentaire", name="pericles3_domaine_updateCommentaire")
     * @Method({"GET", "POST"})
     */   
    public function updateCommentaireAction(Request $request){

        $idDomaine = $request->get('idDomaine');
        $idCommentaireDomaine = $request->get('idCommentaireDomaine');
        $commentaireDomaineCom = $request->get('commentaireDomaine');

        $repositoryDomaine = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Domaine');
        $domaine = $repositoryDomaine->find($idDomaine);

        $repositoryCommentaireDomaine = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:CommentaireDomaine');
        $commentaireDomaine = $repositoryCommentaireDomaine->find($idCommentaireDomaine);


        $commentaireDomaine->setCommentaire($commentaireDomaineCom);
        $commentaireDomaine->setDateCreate(new \DateTime());
        $commentaireDomaine->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($commentaireDomaine);
        $em->flush();

        $result = [];
        foreach ($domaine->getCommentaires() as $comment ) {
            $commentaireToPush=new stdClass();
            $commentaireToPush->id = $comment->getId();
            $commentaireToPush->commentaire = $comment->getCommentaire();
            $commentaireToPush->username = $comment->getUser()->getUsername();
            $commentaireToPush->dateCreate = date_format($comment->getDateCreate(),"d-m-Y H:i:s");
            array_push($result, $commentaireToPush);
        }

        return new JsonResponse($result);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Recherche
     *
     * @Route("/search", name="pericles3_eval_search")
     * @Method({"GET", "POST"})
    */
    public function SearchAction(Request $request)
    {
        $results=  new \Doctrine\Common\Collections\ArrayCollection();
        $occurence=$request->get('occurence');
        $etablissement=$this->getUser()->GetEtablissement();
        if ($occurence && $etablissement)
        {
            $results=$this->SearchEvalEtablissement($etablissement,$occurence,$results);
        }
        return $this->render('Evaluation/search.html.twig', ['occurence'=>$occurence, 'results'=>$results ]);
    }
    
    
       
    /**
     * Recherche
     *
     * @Route("/search/etablissement_{id}", name="pericles3_eval_search_etablissement")
     * @Method({"GET", "POST"})
    */
    public function SearchEtablissementAction(Etablissement $etablissement,Request $request)
    {
        $results=  new \Doctrine\Common\Collections\ArrayCollection();
        $occurence=$request->get('occurence'); 
        if ($occurence && $etablissement)
        {
            $results=$this->SearchEvalEtablissement($etablissement,$occurence,$results);
        }
        return $this->render('Evaluation/search.html.twig', ['occurence'=>$occurence, 'etablissement'=>$etablissement, 'results'=>$results ]);
    }   
    
    
    
    public function SearchEvalEtablissement($etablissement,$occurence,$results)
    {
            $results['Domaines']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Referentiel')->FindByEtablissementOccurenceDomaine($etablissement,$occurence);;
            $results['Dimensions']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Referentiel')->FindByEtablissementOccurenceDimension($etablissement,$occurence);
            $results['Criteres']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Referentiel')->FindByEtablissementOccurenceCritere($etablissement,$occurence);
            $results['Synthèse du domaine']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:CommentaireDomaine')->FindByEtablissementOccurence($etablissement,$occurence);
            $results['Point de vue de l\'usager']=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Preuve')->FindByEtablissementOccurencePdv($etablissement,$occurence);
            return($results);
    }

    
    
    
    
    
    
    

    /**
     * Recherche
     *
     * @Route("/get_pastilles/etablissement_{id}", name="pericles3_evaluation_etablissement_get_pastilles_menu")
     * @Method({"GET", "POST"})
    */
    public function GetPastillesAction(Etablissement $Etablissement)
    {
            return $this->render('Nuts/inc_pastilles_domaines.html.twig',array('domaines'=> $Etablissement->getDomaines()));
    }
    

    
    
        
}
