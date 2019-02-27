<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pericles3Bundle\Entity\Creai;
use Pericles3Bundle\Form\CreaiType;

/**
 * Creai controller.
 *
 * @Route("/backoffice/creai")
 */
class CreaiController extends Controller
{
    /**
     * Lists all Creai entities.
     *
     * @Route("/", name="backoffice_creai_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $creais = $em->getRepository('Pericles3Bundle:Creai')->findAll();

        return $this->render('BackOffice/creai/index.html.twig', array(
            'creais' => $creais,
        ));
    }
    
    
    /**
     * Lists all Creai entities.
     *
     * @Route("/liste_ct", name="backoffice_creai_list_ct")
     * @Method("GET")
     */
    public function indexCTAction()
    {
        $em = $this->getDoctrine()->getManager();
        $CTs = $em->getRepository('Pericles3Bundle:User')->ListCTCreai();
        return $this->render('BackOffice/creai/liste_ct.html.twig', array(
            'CTs' => $CTs,
        ));
    }
       
    /**
     * Lists all Creai entities.
     *
     * @Route("/liste_ct_refer", name="backoffice_creai_list_refer")
     * @Method("GET")
     */
    public function indexCTReferentielsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $CTs = $em->getRepository('Pericles3Bundle:User')->ListCTCreaiRef();
        return $this->render('BackOffice/creai/liste_ct.html.twig', array(
            'CTs' => $CTs,
        ));
    }
    
    
    
    
    

    /**
     * Creates a new Creai entity.
     *
     * @Route("/new", name="backoffice_creai_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $creai = new Creai();
        $form = $this->createForm('Pericles3Bundle\Form\CreaiType', $creai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($creai);
            $em->flush();

            return $this->redirectToRoute('backoffice_creai_show', array('id' => $creai->getId()));
        }

        return $this->render('BackOffice/creai/new.html.twig', array(
            'creai' => $creai,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Creai entity.
     *
     * @Route("/{id}", name="backoffice_creai_show")
     * @Method("GET")
     */
    public function showAction(Creai $creai)
    {
        $deleteForm = $this->createDeleteForm($creai);

        return $this->render('BackOffice/creai/show.html.twig', array(
            'creai' => $creai,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Creai entity.
     *
     * @Route("/{id}/edit", name="backoffice_creai_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Creai $creai)
    {
        $deleteForm = $this->createDeleteForm($creai);
        $editForm = $this->createForm('Pericles3Bundle\Form\CreaiType', $creai);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($creai);
            $em->flush();

            return $this->redirectToRoute('backoffice_creai_show', array('id' => $creai->getId()));
        }

        return $this->render('BackOffice/creai/edit.html.twig', array(
            'creai' => $creai,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Creai entity.
     *
     * @Route("/{id}", name="backoffice_creai_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Creai $creai)
    {
        $form = $this->createDeleteForm($creai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($creai);
            $em->flush();
        }

        return $this->redirectToRoute('backoffice_creai_index');
    }

    /**
     * Creates a form to delete a Creai entity.
     *
     * @param Creai $creai The Creai entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Creai $creai)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_creai_delete', array('id' => $creai->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
