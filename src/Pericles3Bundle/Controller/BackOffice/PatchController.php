<?php

namespace Pericles3Bundle\Controller\BackOffice;
use Pericles3Bundle\Controller\BackOffice\AdminController;

use Pericles3Bundle\Entity\Patch;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\PatchReferentiel;
use Pericles3Bundle\Entity\PatchToDo;




use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;



/**
 * Patch controller.
 *
 * @Route("/backoffice/patch")
 */
class PatchController extends AdminController
{

    
    
    
    /**
     * Lists all patch entities.
     *
     * @Route("/", name="patch_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $patches = $em->getRepository('Pericles3Bundle:Patch')->findAll();
        $RefsFilationToPatchpatch = $em->getRepository('Pericles3Bundle:referentielPublic')->findWithParents();
        
        
        $ReferentielsPasDansPatchSource = $em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielPasDansPatchSourceAll();
        $ReferentielsPasDansPatchCible = $em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielPasDansPatchCibleAll();
        
        

        return $this->render('BackOffice/patch/index.html.twig', array(
            'patches' => $patches,
            'ReferentielsPasDansPatchSource' => $ReferentielsPasDansPatchSource,
            'ReferentielsPasDansPatchCible' => $ReferentielsPasDansPatchCible,
            'RefsFilationToPatchpatch' => $RefsFilationToPatchpatch,
        ));
    }
    
    
    
      /**
     * Lists all patchToDo entities.
     *
     * @Route("/todo", name="backoffice_patch_todo_index")
     * @Method("GET")
     */
    public function indexTodoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $patchToDos = $em->getRepository('Pericles3Bundle:PatchToDo')->findDefault();

        return $this->render('BackOffice/patch/todo_index.html.twig', array(
            'patchToDos' => $patchToDos,
        ));
    }
       
    /**
     * Lists all patchToDo entities.
     *
     * @Route("/todo/done", name="backoffice_patch_todo_done")
     * @Method("GET")
     */
    public function indexDoneAction()
    {
        $em = $this->getDoctrine()->getManager();
        $patchToDos = $em->getRepository('Pericles3Bundle:PatchToDo')->findDone();
        return $this->render('BackOffice/patch/todo_index.html.twig', array(
            'patchToDos' => $patchToDos,
        ));
    }
    
       
    /**
     * Lists all patchToDo entities.
     *
     * @Route("/todo/afaire", name="backoffice_patch_todo_afaire")
     * @Method("GET")
     */
    public function indexAFaireAction()
    {
        $em = $this->getDoctrine()->getManager();
        $patchToDos = $em->getRepository('Pericles3Bundle:PatchToDo')->findTodo();
        return $this->render('BackOffice/patch/todo_index.html.twig', array(
            'patchToDos' => $patchToDos,
        ));
    }
    
    
    
    
    
    
      /**
     * Finds and displays a patchToDo entity.
     *
     * @Route("/todo/{id}", name="backoffice_patch_todo_show")
     * @Method("GET")
     */
    public function showTodoAction(PatchToDo $patchToDo)
    {
        return $this->render('BackOffice/patch/todo_show.html.twig', array(
            'patchToDo' => $patchToDo 
            
        ));
    }
    
    
    /**
     * Deletes a patchToDo entity.
     *
     * @Route("/todo/{id}/delete", name="backoffice_patch_todo_delete")
     * @Method("GET")
     */
    public function deleteTodoAction(PatchToDo $patchToDo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($patchToDo);
        $em->flush();
        return $this->redirectToRoute('backoffice_patch_todo_index');
    }
    
    
      /**
     * Finds and displays a ReferentielPublic entity.
     
     * @   Route    ("/etablissement_{id}/patch/{patch_id}/todo", name="backoffice_patch_etablissement_patch_todo")
     * @    Method    ("GET")
 
    public function PatchToDoAction(Etablissement $Etablissement, $patch_id)
    {
        $em = $this->getDoctrine()->getManager();
        $patch=$em->getRepository('Pericles3Bundle:Patch')->findOneById($patch_id);
        $patchToDo=new PatchToDo();
        $patchToDo->setPatch($patch);
        $patchToDo->setEtablissement($Etablissement);
        $em->persist($patchToDo);
        $em->flush();
        return new JsonResponse($patchToDo->getId());
    }
    
        */
    

    

    /**
     * Finds and displays a patchReferentiel entity.
     *
     * @Route("/ref_{patchSource}/link_{patchCible}", name="patchreferentiel_link")
     * @Method("GET")
     */
    function patchRefLink(PatchReferentiel $patchSource,PatchReferentiel $patchCible)
    {
        $em = $this->getDoctrine()->getManager();
        if ((! $patchSource->GetAieul()) &&  $patchCible->GetAieul())
        {
            $patchSource->setPatcheRefAieul($patchCible->GetAieul());
        }
        
        $this->AddFlash('success',"atchCible->getPatcheRefCible() : ".$patchCible->getPatcheRefCible());

        $patchSource->setPatcheRefCible($patchCible->getPatcheRefCible());
        $patchSource->setValide(true);
        $em->persist($patchSource);
        $em->flush();
        $em->remove($patchCible);
        $em->flush();
        

        $this->AddFlash('success',"Liason effectuées");
        return $this->redirectToRoute('patchreferentiel_show', array('id' => $patchSource->getId()));
    }
    
    
    
    

    /**
     * Finds and displays a patchReferentiel entity.
     *
     * @Route("/ref_{id}", name="patchreferentiel_show")
     * @Method("GET")
     */
    public function showPatchRefRefAction(PatchReferentiel $patchReferentiel)
    {
        $em = $this->getDoctrine()->getManager();

        $patch=$patchReferentiel->GetPatch();
        
        
        $nextPatch=$em->getRepository('Pericles3Bundle:PatchReferentiel')->findNextRefFromPatch($patch,$patchReferentiel);
        $nextPatchToValide=$em->getRepository('Pericles3Bundle:PatchReferentiel')->findNextRefToValide($patch,$patchReferentiel);
        
        
        if ($patchReferentiel->getSource())
        {
            $nodes_without_child = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findCibleType($patch,$patchReferentiel->getSource()->getTypeReferentiel()->GetId());
        }
        else 
        {
            $nodes_without_child = null;
        }

//        $referentielPublicCible=$patchReferentiel->GetPatch()->getCible();
         
        return $this->render('BackOffice/patch/show_ref.html.twig', array(
            'nextPatch' => $nextPatch, 
            'nextPatchToValide' => $nextPatchToValide, 
            'patchReferentiel' => $patchReferentiel, 
            'nodes_without_child' => $nodes_without_child
        ));
    }
    

    /**
     * Finds and displays a patchReferentiel entity.
     *
     * @Route("/ref_{id}/valide", name="patchreferentiel_valide")
     * @Method("GET")
     */
    public function showPatchRefValideRefAction(PatchReferentiel $patchReferentiel)
    {
        $em = $this->getDoctrine()->getManager();
        $patchReferentiel->setValide(true);
        $em->persist($patchReferentiel);
        $em->flush();
        return $this->redirectToRoute('patchreferentiel_show', array('id' => $patchReferentiel->getId()));
    }
    
    /**
     * Finds and displays a patchReferentiel entity.
     *
     * @Route("/ref_{id}/unvalide", name="patchreferentiel_unvalide")
     * @Method("GET")
     */
    public function showPatchRefUnValideRefAction(PatchReferentiel $patchReferentiel)
    {
        $em = $this->getDoctrine()->getManager();
        $patchReferentiel->setValide(false);
        $em->persist($patchReferentiel);
        $em->flush();
        return $this->redirectToRoute('patchreferentiel_show', array('id' => $patchReferentiel->getId()));
    }
    
    
    
    
    
    public function AutoPatchreferentiel(Patch $patch)
    {
          $em = $this->getEm();
        $aieul=$patch->getPatcheRefPublicAieul();
        
        if ($patch->getCible()->GetSourceParent()==$patch->getSource())
        {
            $this->OutputOrFlash('Filiation');
            foreach($patch->getCible()->getReferentiels() as $ref)
            {
                $patchRef=new \Pericles3Bundle\Entity\PatchReferentiel;
                if ($ref->getSourceParent())
                {
                    $this->Output('<info>'.$ref.'</info>');

                    $patchRef->setPatcheRefSource($ref->getSourceParent());
                    $patchRef->setValide(true);
                }
                else
                {
                    $this->Output(''.$ref.'');
                    $patchRef->setValide(false);
                }
                $patchRef->setPatcheRefCible($ref);
                $patchRef->setPatch($patch);
                $em->persist($patchRef);
                $em->flush();
            }
        }
        elseif ($patch->getSource()->GetSourceParent()==$patch->getCible())
        {
            $this->OutputOrFlash('Filiation');
            foreach($patch->getSource()->getReferentiels() as $ref)
            {
                $patchRef=new \Pericles3Bundle\Entity\PatchReferentiel;
                if ($ref->getSourceParent())
                {
                    $this->Output('<info>'.$ref.'</info>');
                    $patchRef->setPatcheRefCible($ref->getSourceParent());
                    $patchRef->setValide(true);
                }
                else
                {
                    $this->Output(''.$ref.'');
                    $patchRef->setValide(false);
                }
                $patchRef->setPatcheRefSource($ref);
                $patchRef->setPatch($patch);
                $em->persist($patchRef);
                $em->flush();
            }
        }
        elseif ($patch->getHasPatchsIntermediares())
        {
            $this->OutputOrFlash('intermediare');
            foreach($patch->getSource()->getReferentiels() as $ref)
            {
                $patchRef=new \Pericles3Bundle\Entity\PatchReferentiel;
                $refCible=$patch->getPatchRefCibleByInter($ref);
                if ($refCible)
                {
                    $this->Output('<info>'.$ref.'</info>');
                    $patchRef->setPatcheRefCible($refCible);
                    $patchRef->setValide(true);
                }
                else
                {
                    $this->Output(''.$ref.'');
                    $patchRef->setValide(false);
                }
                $patchRef->setPatcheRefSource($ref);
                $patchRef->setPatch($patch);
                $em->persist($patchRef);
                $em->flush();
            }
            return (0);
        }
        elseif ($aieul)
        {
            $this->AddFlash('success','aieul : '.$aieul);
            //foreach($patch->getPatcheRefPublicSource()->getReferentielDomaines() as $ref)
            foreach($patch->getSource()->getReferentiels() as $ref)
            {
                $patchRef=null;
                $patchRefAieul=$ref->getAieul($aieul);
                if ($patchRefAieul) $patchRef = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findOneBypatcheRefAieul($patch,$patchRefAieul);
                if ($patchRef &&  $patchRefAieul)
                {
                    $this->Output('<info>'.$ref.'</info>');
                    $patchRef->setValide(true);
                }
                else
                {
                    $patchRef=new \Pericles3Bundle\Entity\PatchReferentiel;
                    $patchRef->setValide(false);
                    $patchRef->setPatcheRefAieul($patchRefAieul);
                    $this->Output(''.$ref.'');
                }
                $patchRef->setPatcheRefSource($ref);
                $patchRef->setPatch($patch);
                $em->persist($patchRef);
                $em->flush();
            }
            foreach($patch->getPatcheRefPublicCible()->getReferentiels() as $ref)
            {
                $patchRefAieul=$ref->getAieul($aieul);
                if ($patchRefAieul) $patchRef = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findOneBypatcheRefAieul($patch,$patchRefAieul);
                if ($patchRef &&  $patchRefAieul)
                {
                      $this->Output('<info>'.$ref.'</info>');
                }
                else
                {
                    $patchRef=new \Pericles3Bundle\Entity\PatchReferentiel;
                    $patchRef->setPatcheRefAieul($patchRefAieul);
                    $this->Output(''.$ref.'');

                }
                $patchRef->setPatcheRefCible($ref);
                $patchRef->setValide(true);
                $patchRef->setPatch($patch);
                $em->persist($patchRef);
                $em->flush();
            }
        }
        else  
        {
            foreach($patch->getPatcheRefPublicSource()->getReferentiels() as $ref)
            {
                $patchRef=new \Pericles3Bundle\Entity\PatchReferentiel;
                $patchRef->setPatcheRefSource($ref);
                $patchRef->setPatch($patch);
                $patchRef->setValide(false);
                $em->persist($patchRef);
                $em->flush();
            }
            foreach($patch->getPatcheRefPublicCible()->getReferentiels() as $ref)
            {
                $patchRef=new \Pericles3Bundle\Entity\PatchReferentiel;
                $patchRef->setPatcheRefCible($ref);
                $patchRef->setValide(false);
                $patchRef->setPatch($patch);
                $em->persist($patchRef);
                $em->flush();
            }
        }
    }
    
    
    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/autopatch", name="patch_autopatchdomain")
     * @Method("GET")
     */
    public function autopatchdomainAction(Patch $patch)
    {
        $this->AutoPatchreferentiel($patch);
        return $this->redirectToRoute('patch_show', array('id' => $patch->getId()));
    }
    

    /**
     * Creates a new patch entity.
     *
     * @Route("/{id}/new_by_inter", name="patch_new_by_inter")
     * @Method({"GET", "POST"})
     */
    public function newByInterAction(patch $patch, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $etape=0;

        $RefsIntermede=null;
        
        if ($request->get('ref_inter'))
        {
            $this->AddFlash('success',"Inter");
            $RefInter= $em->getRepository('Pericles3Bundle:Patch')->findOneById($request->get('ref_inter'));
            $patch->addPatchsIntermediare($RefInter);
            $em->persist($patch);
            $em->flush();
        }
        
        if ($patch->getNbPatchsIntermediares())
        {
            $RefsIntermede = $em->getRepository('Pericles3Bundle:Patch')->findWithSource($patch->getLastPatchsIntermediare()->GetCible());
        }
        else
        {
            $this->AddFlash('success',"pas getNbPatchsIntermediares");
            $RefsIntermede = $em->getRepository('Pericles3Bundle:Patch')->findWithSource($patch->GetSource());
        }
        return $this->render('BackOffice/patch/new_intermede.html.twig', array('etape' => $etape,'patch' => $patch, 'RefsIntermede'=>$RefsIntermede ));

    }
    
    
    
    
        
    
    
    
    
    
    /**
     * Creates a new patch entity.
     *
     * @Route("/new", name="patch_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $patch = new Patch();
        $form = $this->createForm('Pericles3Bundle\Form\PatchType', $patch);
        $form->handleRequest($request);

         
        
        if ($form->isSubmitted() && $form->isValid()) {
            
        $source=$form->getData()->getPatcheRefPublicSource();
        $cible=$form->getData()->getPatcheRefPublicCible();

        $aieulCommun=null;
        
        if ($cible->GetSourceParent()==$source)
        {
           // $aieulCommun=$source;
        }
        elseif ($source->GetSourceParent()==$cible)
        {
           // $aieulCommun=$cible;
        }
        else
        {
            if ($request->get('intermerdiare'))
            {
                $this->AddFlash('success',"INTERMEDIARE");
                $patch->setFini(false);
                $em->persist($patch);
                $em->flush();
                return $this->redirectToRoute('patch_new_by_inter', array('id' => $patch->getId()));
            }
            else
            {
                
                $cibleAieux=$cible->getAieuls();
                foreach($source->getAieuls() as $aieul)
                {
                    if ($cibleAieux->contains($aieul))
                    {
                        $aieulCommun=$aieul;
                        $this->AddFlash('success',$aieulCommun);
                    }
                }
                 
            }
        }
            

        
       
            if ($aieulCommun)
            {
                $patch->setPatcheRefPublicAieul($aieulCommun);
            }
            $patch->setFini(false);
            $em->persist($patch);
            $em->flush();
            $this->AddFlash('danger',"Pas dancetres en commun");
            return $this->redirectToRoute('patch_show', array('id' => $patch->getId()));
        }

        return $this->render('BackOffice/patch/new.html.twig', array(
            'patch' => $patch,
            'form' => $form->createView(),
        ));
    }
    
    
        
    
     public function AddRefPasDansSourceAction(Patch $patch)
    {
        $em = $this->GetEm();
        $ReferentielsPasDansPatchSource = $em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielPasDansPatchSource($patch->GetSource(),$patch);
        foreach ($ReferentielsPasDansPatchSource as $ReferentielPasDansPatchSource)
        {
                 $this->Output('<info>'.$ReferentielPasDansPatchSource." </info> ");
                $patchRefNew=new \Pericles3Bundle\Entity\PatchReferentiel;
                $patchRefNew->setPatch($patch);
                $patchRefNew->setPatcheRefSource($ReferentielPasDansPatchSource);
                $patchRefNew->setValide(false);
                $em->persist($patchRefNew);
                $em->flush();
        }
        $this->AddFlashIf('success',count($ReferentielsPasDansPatchSource)." ajoutés ! ");
        $this->Output('<info>'.count($ReferentielsPasDansPatchSource)." </info> ");
    }

    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/add_referentiels_pas_dans_patch_source", name="patch_add_referentiels_pas_dans_patch_source")
     * @Method("GET")
     */
    public function showAddRefPasDansSourceAction(Patch $patch)
    {
        $this->AddRefPasDansSourceAction($patch);
        return $this->redirectToRoute('patch_show', array('id' => $patch->getId()));
    }
    
    
     public function AddRefPasDanscibleAction(Patch $patch)
    {
        $em = $this->GetEm();
        $ReferentielsPasDansPatchCible = $em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielPasDansPatchCible($patch->GetCible(),$patch);
        foreach ($ReferentielsPasDansPatchCible as $ReferentielPasDansPatchCible)
        {
                $this->Output('<info>'.$ReferentielPasDansPatchCible." </info> ");
                $patchRefNew=new \Pericles3Bundle\Entity\PatchReferentiel;
                $patchRefNew->setPatch($patch);
                $patchRefNew->setPatcheRefCible($ReferentielPasDansPatchCible);
                $patchRefNew->setValide(false);
                $em->persist($patchRefNew);
                $em->flush();
        }
        $this->AddFlashIf('success',count($ReferentielsPasDansPatchCible)." ajoutés ! ");
        $this->Output('<info>'.count($ReferentielsPasDansPatchCible)." </info> ");
    }
    

    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/patch_add_referentiels_pas_dans_patch_cible", name="patch_add_referentiels_pas_dans_patch_cible")
     * @Method("GET")
     */
    public function showAddRefPasDanscibleAction(Patch $patch)
    {
        $this->AddRefPasDanscibleAction($patch);
        return $this->redirectToRoute('patch_show', array('id' => $patch->getId()));
    }
    


    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/bilan", name="patch_show_bilan")
     * @Method("GET")
     */
    public function showBilanAction(Patch $patch)
    {
        $em = $this->getDoctrine()->getManager();

        $ReferentielsLinked = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findLinked($patch);
        $ReferentielsSansCible = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findUnlinkSource($patch);
        $ReferentielsSansSource = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findUnlinkCible($patch);
        
        return $this->render('BackOffice/patch/show_bilan.html.twig', array(
            'patch' => $patch,
            'ReferentielsLinked' => $ReferentielsLinked,
            'ReferentielsSansCible' => $ReferentielsSansCible,
            'ReferentielsSansSource' => $ReferentielsSansSource,
        ));
    }
    
    
    

    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}", name="patch_show")
     * @Method("GET")
     */
    public function showAction(Patch $patch)
    {
        $em = $this->getDoctrine()->getManager();
        $PatchReferentiels = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findFromPatch($patch);

        $ReferentielsPasDansPatchSource = $em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielPasDansPatchSource($patch->GetSource(),$patch);
        $ReferentielsPasDansPatchCible = $em->getRepository('Pericles3Bundle:Referentiel')->FindReferentielPasDansPatchCible($patch->GetCible(),$patch);
        
        
        return $this->render('BackOffice/patch/show.html.twig', array(
            'patch' => $patch,
            'PatchReferentiels' => $PatchReferentiels,
            'ReferentielsPasDansPatchSource' => $ReferentielsPasDansPatchSource,
            'ReferentielsPasDansPatchCible' => $ReferentielsPasDansPatchCible,
        ));
    }
    
    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/unlink_patchRef", name="patch_unlink_patch_ref")
     * @Method("GET")
     */
    public function showUnlinkPatcheRefAction(PatchReferentiel $patchRef)
    {
    
        $em = $this->getDoctrine()->getManager();
        $patch=$patchRef->GetPatch();
        
            $aieul_source=null;
            $aieul_cible=null;
        
        if ($patch->GetAieul())
        {
            $aieul_source=$patchRef->getSource()->GetAieul($patch->GetAieul());
            $aieul_cible=$patchRef->getCible()->GetAieul($patch->GetAieul());
            $this->AddFlash('success',$aieul_source);
            $this->AddFlash('success',$aieul_cible);
        }
        
        
        
        
        /*
        if ($aieul)
        {
            $this->AddFlash('success','aieul : '.$aieul);
            //foreach($patch->getPatcheRefPublicSource()->getReferentielDomaines() as $ref)
            foreach($patch->getPatcheRefPublicSource()->getReferentiels() as $ref)
            {
                $patchRefAieul=$ref->getAieul($aieul);
         * */
        $patchRefNew=new \Pericles3Bundle\Entity\PatchReferentiel;
        $patchRefNew->setPatch($patchRef->getPatch());
        if ($aieul_cible) $patchRefNew->setPatcheRefAieul($aieul_cible);
        
        $patchRefNew->setPatcheRefCible($patchRef->getCible());
        $patchRefNew->setValide(false);
        $em->persist($patchRefNew);
        $em->flush();
        $this->AddFlash('success',"Patch ref crée : ".$patchRefNew->getId());
        
        $patchRef->setValide(false);
        if ($aieul_source) $patchRef->setPatcheRefAieul($aieul_source);
        $patchRef->setPatcheRefCible(null);
        
        $em->persist($patchRefNew);
        $em->flush();
        
        return $this->redirectToRoute('patchreferentiel_show', array('id' => $patchRef->getId()));
     }
    
    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/cible_unlink", name="patch_show_cible_unlink")
     * @Method("GET")
     */
    public function showUnlinkAction(Patch $patch)
    {
    
        $em = $this->getDoctrine()->getManager();
        $PatchReferentiels = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findUnlinkCible($patch);
        
        return $this->render('BackOffice/patch/show.html.twig', array(
            'patch' => $patch,
            'PatchReferentiels' => $PatchReferentiels
        ));
    }
    
    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/linked", name="patch_show_source_cible_linked")
     * @Method("GET")
     */
    public function showLinkedSourceAction(Patch $patch)
    {
        $em = $this->getDoctrine()->getManager();
        $PatchReferentiels = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findLinked($patch);
        return $this->render('BackOffice/patch/show.html.twig', array(
            'patch' => $patch,
            'PatchReferentiels' => $PatchReferentiels
        ));
    }
    
    
    
    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/source_unlink", name="patch_show_source_unlink")
     * @Method("GET")
     */
    public function showUnlinkSourceAction(Patch $patch)
    {
    
        $em = $this->getDoctrine()->getManager();
        $PatchReferentiels = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findUnlinkSource($patch);
        
        return $this->render('BackOffice/patch/show.html.twig', array(
            'patch' => $patch,
            'PatchReferentiels' => $PatchReferentiels
        ));
    }
    
    public function DeleteRef(Patch $patch)
    {
        $em = $this->getEm();
        foreach ($patch->getPatchReferentiels() as $ref)
        {
            $this->GetOutput()->write(".");
            $em->remove($ref);
            $em->flush();
        }

    }            
    
    /**
     * Finds and displays a patch entity.
     *
     * @Route("/{id}/delete_ref", name="patch_delete_referentiel")
     * @Method("GET")
     */
    public function ShowDeleteRefAction(Patch $patch)
    {
        $this->DeleteRef($patch);
        return $this->redirectToRoute('patch_show', array('id' => $patch->getId()));
    }
    
    
     /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/{id}/comparaison/autolink", name="patch_comparaison_autolink")
     * @Method({"GET", "POST"})
     */
    public function ComparaisonAutoLinkAction(Patch $patch)
    {
        
        $em = $this->getDoctrine()->getManager();
        $this->addFlash('success', "Linkageauto ");
        $PatchReferentielsSansCible = $em->getRepository('Pericles3Bundle:PatchReferentiel')->findUnlinkSource($patch);
        $i=0;
        foreach ($PatchReferentielsSansCible as $PatchReferentielSansCible)
        {
             $Refcible=$em->getRepository('Pericles3Bundle:Referentiel')->FindByLibellePublicType($PatchReferentielSansCible->getPatcheRefSource()->GetNom(),$patch->GetCible(),$PatchReferentielSansCible->getPatcheRefSource()->getTypeReferentiel()->GetId());
                
              if ($Refcible)
              {
                $i++;
                $this->addFlash('success', $Refcible." trouvé ");
                $patchRefCible=$em->getRepository('Pericles3Bundle:PatchReferentiel')->findFromPatchRefCible($patch,$Refcible);
                if (! $patchRefCible)
                {
                    throw $this->createAccessDeniedException("PATCH REF CIBLE ABSENT ! : ".$patch." :--->".$Refcible." (".$Refcible->GetId().")<-- :  (".$PatchReferentielSansCible->GetId().")");
                }
                if ($PatchReferentielSansCible->GetAieul() &&  $patchRefCible->GetAieul() )
                {
                    if ($PatchReferentielSansCible->GetAieul()->GetId() != $patchRefCible->GetAieul()->GetId())
                    {
                        $this->addFlash('erreur', " Aieux différents");
                    }
                    return $this->redirectToRoute('patch_show', array('id' => $patch->getId()));
                }
                elseif ((! $PatchReferentielSansCible->GetAieul()) &&  $patchRefCible->GetAieul() )
                {
                    $PatchReferentielSansCible->setPatcheRefAieul($patchRefCible->GetAieul());
                    $this->addFlash('danger', "AIEUL : ".$patchRefCible->GetAieul());
                }
                else
                {
                    $this->addFlash('danger', "PAS AIEUL");
                }
                $PatchReferentielSansCible->setPatcheRefCible($patchRefCible->getPatcheRefCible());
                $PatchReferentielSansCible->setValide(true);
                $em->persist($PatchReferentielSansCible);
                $em->flush();
                $em->remove($patchRefCible);
                $em->flush();
            }
            else
            {
//                $this->addFlash('error', $ref." --->Pas trouvé ");
            }
        }
        $this->addFlash('success', $i." mis  à jour sur ".count($PatchReferentielsSansCible));
        return $this->redirectToRoute('patch_show', array('id' => $patch->getId()));
    }
    
    
    

    /**
     * Displays a form to edit an existing patch entity.
     *
     * @Route("/{id}/edit", name="patch_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Patch $patch)
    {
        $deleteForm = $this->createDeleteForm($patch);
        $editForm = $this->createForm('Pericles3Bundle\Form\PatchType', $patch);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('patch_edit', array('id' => $patch->getId()));
        }

        return $this->render('BackOffice/patch/edit.html.twig', array(
            'patch' => $patch,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a patch entity.
     *
     * @Route("/{id}/delete", name="patch_delete")
     * @Method("get")
     */
    public function deleteAction(Patch $patch)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($patch);
            $em->flush();
            return $this->redirectToRoute('patch_index');
    }

    /**
     * Creates a form to delete a patch entity.
     *
     * @param Patch $patch The patch entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Patch $patch)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('patch_delete', array('id' => $patch->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
