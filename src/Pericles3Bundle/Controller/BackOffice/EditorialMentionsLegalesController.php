<?php

namespace Pericles3Bundle\Controller\BackOffice;


use Pericles3Bundle\Entity\EditorialMentionsLegales;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

/**
 * Editorialmentionslegale controller.
 *
 * @Route("backoffice/mentionslegales")
 */
class EditorialMentionsLegalesController extends Controller
{
    /**
     * Lists all editorialMentionsLegale entities.
     *
     * @Route("/", name="backoffice_editorial_mentionslegales_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $editorialMentionsLegales = $em->getRepository('Pericles3Bundle:EditorialMentionsLegales')->findAll();
        $lastMention= $em->getRepository('Pericles3Bundle:EditorialMentionsLegales')->findLast();

        return $this->render('BackOffice/editorial/mentionslegales/index.html.twig', array(
            'editorialMentionsLegales' => $editorialMentionsLegales,
            'lastMention' => $lastMention,
        ));
    }

    /**
     * Creates a new editorialMentionsLegale entity.
     *
     * @Route("/new", name="backoffice_editorial_mentionslegales_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $editorialMentionsLegale = new EditorialMentionsLegales();
        $form = $this->createForm('Pericles3Bundle\Form\EditorialMentionsLegalesType', $editorialMentionsLegale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($editorialMentionsLegale);
            $em->flush();

            return $this->redirectToRoute('backoffice_editorial_mentionslegales_show', array('id' => $editorialMentionsLegale->getId()));
        }

        return $this->render('BackOffice/editorial/mentionslegales/new.html.twig', array(
            'editorialMentionsLegale' => $editorialMentionsLegale,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a editorialMentionsLegale entity.
     *
     * @Route("/{id}", name="backoffice_editorial_mentionslegales_show")
     * @Method("GET")
     */
    public function showAction(EditorialMentionsLegales $editorialMentionsLegale)
    {
        $deleteForm = $this->createDeleteForm($editorialMentionsLegale);

        return $this->render('BackOffice/editorial/mentionslegales/show.html.twig', array(
            'editorialMentionsLegale' => $editorialMentionsLegale,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing editorialMentionsLegale entity.
     *
     * @Route("/{id}/edit", name="backoffice_editorial_mentionslegales_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EditorialMentionsLegales $editorialMentionsLegale)
    {
        $deleteForm = $this->createDeleteForm($editorialMentionsLegale);
        $editForm = $this->createForm('Pericles3Bundle\Form\EditorialMentionsLegalesType', $editorialMentionsLegale);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_editorial_mentionslegales_show', array('id' => $editorialMentionsLegale->getId()));
        }

        return $this->render('BackOffice/editorial/mentionslegales/edit.html.twig', array(
            'editorialMentionsLegale' => $editorialMentionsLegale,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a editorialMentionsLegale entity.
     *
     * @Route("/{id}", name="backoffice_editorial_mentionslegales_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EditorialMentionsLegales $editorialMentionsLegale)
    {
        $form = $this->createDeleteForm($editorialMentionsLegale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($editorialMentionsLegale);
            $em->flush();
        }

        return $this->redirectToRoute('backoffice_editorial_mentionslegales_index');
    }

    /**
     * Creates a form to delete a editorialMentionsLegale entity.
     *
     * @param EditorialMentionsLegales $editorialMentionsLegale The editorialMentionsLegale entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EditorialMentionsLegales $editorialMentionsLegale)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_editorial_mentionslegales_delete', array('id' => $editorialMentionsLegale->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
