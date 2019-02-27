<?php

namespace Pericles3Bundle\Controller\BackOffice;


use Pericles3Bundle\Entity\QualiEvalReferentiel;
use Pericles3Bundle\Entity\Referentiel;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Qualievalreferentiel controller.
 *
 * @Route("backoffice/qualieval")
 */
class QualiEvalReferentielController extends Controller
{
    /**
     * Lists all qualiEvalReferentiel entities.
     *
     * @Route("/", name="backoffice_qualieval_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qualiEvalReferentiels = $em->getRepository('Pericles3Bundle:QualiEvalReferentiel')->findAll();

        return $this->render('BackOffice/qualievalreferentiel/index.html.twig', array(
            'qualiEvalReferentiels' => $qualiEvalReferentiels,
        ));
    }
    
    /**
     * Lists all qualiEvalReferentiel entities.
     *
     * @Route("/arbo", name="backoffice_qualieval_arborescence")
     * @Method("GET")
     */
    public function arboAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qualiEvalReferentiels = $em->getRepository('Pericles3Bundle:QualiEvalReferentiel')->findN1();

        return $this->render('BackOffice/qualievalreferentiel/arbre.html.twig', array(
            'qualiEvalReferentiels' => $qualiEvalReferentiels,
        ));
    }
    

    

    /**
     * Creates a new qualiEvalReferentiel entity.
     *
     * @Route("/new", name="backoffice_qualieval_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qualiEvalReferentiel = new Qualievalreferentiel();
        $form = $this->createForm('Pericles3Bundle\Form\QualiEvalReferentielType', $qualiEvalReferentiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qualiEvalReferentiel);
            $em->flush();

            return $this->redirectToRoute('backoffice_qualieval_show', array('id' => $qualiEvalReferentiel->getId()));
        }

        return $this->render('BackOffice/qualievalreferentiel/new.html.twig', array(
            'qualiEvalReferentiel' => $qualiEvalReferentiel,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qualiEvalReferentiel entity.
     *
     * @Route("/{id}", name="backoffice_qualieval_show")
     * @Method("GET")
     */
    public function showAction(QualiEvalReferentiel $qualiEvalReferentiel)
    {

        $em = $this->getDoctrine()->getManager();
         

        return $this->render('BackOffice/qualievalreferentiel/show.html.twig', array(
            'qualiEvalReferentiel' => $qualiEvalReferentiel 
        ));
    }
    
    /**
     * Finds and displays a qualiEvalReferentiel entity.
     *
     * @Route("/{id}/link/public_{id_public}", name="backoffice_qualieval_link")
     * @Method("GET")
     */
    public function linkAction(QualiEvalReferentiel $qualiEvalReferentiel, $id_public)
    {

        $em = $this->getDoctrine()->getManager();

        $referentielPublic  = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findOneById($id_public);
        $referentiels  = $em->getRepository('Pericles3Bundle:Referentiel')->FindByPublic($referentielPublic );

        return $this->render('BackOffice/qualievalreferentiel/show.html.twig', array(
            'qualiEvalReferentiel' => $qualiEvalReferentiel,
            'referentielPublic' => $referentielPublic,
            'referentiels' => $referentiels  
        ));
    }
    
     
      /**
     * Finds and displays a qualiEvalReferentiel entity.
     *
     * @Route("/{id}/link_go/{referentiel}", name="backoffice_qualieval_link_go")
     * @Method("GET")
     */
    public function linkGoAction(QualiEvalReferentiel $qualiEvalReferentiel, Referentiel $referentiel)
    {
        $em = $this->getDoctrine()->getManager();

        $referentielPublic  = $referentiel->getReferentielPublic();
        
        if ($referentielPublic->GetId()==35) // adulte
        {
            $qualiEvalReferentiel->setReferentielAdulte($referentiel);
        }
        elseif ($referentielPublic->GetId()==34) // enfant
        {
            $qualiEvalReferentiel->setReferentielEnfant($referentiel);
        }
        $em->persist($qualiEvalReferentiel);
        $em->flush();

    
        return $this->redirectToRoute('backoffice_qualieval_show', array('id' => $qualiEvalReferentiel->getId()));

    }
    
    
      /**
     * Finds and displays a qualiEvalReferentiel entity.
     *
     * @Route("/{id}/unlink_go/{id_public}", name="backoffice_qualieval_unlink_go")
     * @Method("GET")
     */
    public function unlinkGoAction(QualiEvalReferentiel $qualiEvalReferentiel, $id_public)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id_public==35) // adulte
        {
            $qualiEvalReferentiel->setReferentielAdulte(null);
        }
        elseif ($id_public==34) // enfant
        {
            $qualiEvalReferentiel->setReferentielEnfant(null);
        }
        $em->persist($qualiEvalReferentiel);
        $em->flush();
        return $this->redirectToRoute('backoffice_qualieval_show', array('id' => $qualiEvalReferentiel->getId()));
    }
    

    /**
     * Displays a form to edit an existing qualiEvalReferentiel entity.
     *
     * @Route("/{id}/edit", name="backoffice_qualieval_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, QualiEvalReferentiel $qualiEvalReferentiel)
    {
        $deleteForm = $this->createDeleteForm($qualiEvalReferentiel);
        $editForm = $this->createForm('Pericles3Bundle\Form\QualiEvalReferentielType', $qualiEvalReferentiel);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_qualieval_edit', array('id' => $qualiEvalReferentiel->getId()));
        }

        return $this->render('BackOffice/qualievalreferentiel/edit.html.twig', array(
            'qualiEvalReferentiel' => $qualiEvalReferentiel,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qualiEvalReferentiel entity.
     *
     * @Route("/{id}", name="backoffice_qualieval_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, QualiEvalReferentiel $qualiEvalReferentiel)
    {

        $form = $this->createDeleteForm($qualiEvalReferentiel);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qualiEvalReferentiel);
            $em->flush();
        }

        return $this->redirectToRoute('backoffice_qualieval_index');
    }

    /**
     * Creates a form to delete a qualiEvalReferentiel entity.
     *
     * @param QualiEvalReferentiel $qualiEvalReferentiel The qualiEvalReferentiel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(QualiEvalReferentiel $qualiEvalReferentiel)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_qualieval_delete', array('id' => $qualiEvalReferentiel->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
