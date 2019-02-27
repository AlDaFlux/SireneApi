<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Pericles3Bundle\Entity\FinessCategorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Finesscategorie controller.
 *
 * @Route("backoffice/finess/categorie")
 */
class FinessCategorieController extends Controller
{
    /**
     * Lists all finessCategorie entities.
     *
     * @Route("/", name="backoffice_finess_categorie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $finessCategories = $em->getRepository('Pericles3Bundle:FinessCategorie')->findAll();

        return $this->render('BackOffice/finesscategorie/index.html.twig', array(
            'finessCategories' => $finessCategories,
        ));
    }

    /**
     * Creates a new finessCategorie entity.
     *
     * @Route("/new", name="backoffice_finess_categorie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $finessCategorie = new Finesscategorie();
        $form = $this->createForm('Pericles3Bundle\Form\FinessCategorieType', $finessCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($finessCategorie);
            $em->flush();

            return $this->redirectToRoute('backoffice_finess_categorie_show', array('id' => $finessCategorie->getId()));
        }

        return $this->render('BackOffice/finesscategorie/new.html.twig', array(
            'finessCategorie' => $finessCategorie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a finessCategorie entity.
     *
     * @Route("/{id}", name="backoffice_finess_categorie_show")
     * @Method("GET")
     */
    public function showAction(FinessCategorie $finessCategorie)
    {
        $em = $this->getDoctrine()->getManager();
        $etablissements = $em->getRepository('Pericles3Bundle:Etablissement')->findFinessParCategorie($finessCategorie);
        return $this->render('BackOffice/finesscategorie/show.html.twig', array(
            'finessCategorie' => $finessCategorie,
            'etablissements' => $etablissements 
        ));
    }
    
    

    /**
     * Displays a form to edit an existing finessCategorie entity.
     *
     * @Route("/{id}/edit", name="backoffice_finess_categorie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FinessCategorie $finessCategorie)
    {
         
        $editForm = $this->createForm('Pericles3Bundle\Form\FinessCategorieType', $finessCategorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_finess_categorie_show', array('id' => $finessCategorie->getId()));
        }

        return $this->render('BackOffice/finesscategorie/edit.html.twig', array(
            'finessCategorie' => $finessCategorie,
            'edit_form' => $editForm->createView() 
             
        ));
    }

    /**
     * Deletes a finessCategorie entity.
     *
     * @Route("/{id}", name="backoffice_finess_categorie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FinessCategorie $finessCategorie)
    {
        $form = $this->createDeleteForm($finessCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($finessCategorie);
            $em->flush();
        }

        return $this->redirectToRoute('backoffice_finess_categorie_index');
    }
}
