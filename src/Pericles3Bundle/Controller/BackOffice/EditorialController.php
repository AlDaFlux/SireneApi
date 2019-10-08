<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pericles3Bundle\Entity\Editorial;
use Pericles3Bundle\Form\EditorialType;


    
/**
 * Editorial controller.
 *
 * @Route("/backoffice/editorial")
 */
class EditorialController extends Controller
{
    /**
     * Lists all Editorial entities.
     *
     * @Route("/", name="backoffice_editorial_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EDITORIAL_VALIDATEUR'))
        {
            $editorials = $em->getRepository('Pericles3Bundle:Editorial')->findAll();
        }
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_EDITORIAL_REDACTEUR'))
        {
            $editorials = $em->getRepository('Pericles3Bundle:Editorial')->findMineAndFinish($this->GetUser());
        }
        else
        {
            $editorials = $em->getRepository('Pericles3Bundle:Editorial')->findPubliees();
        }
            
        


        
        
        
        return $this->render('BackOffice/editorial/index.html.twig', array(
            'editorials' => $editorials,
        ));
    }

    /**
     * Creates a new Editorial entity.
     *
     * @Route("/new", name="backoffice_editorial_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $editorial = new Editorial();

        $editorial->setEtatPublication($em->getRepository('Pericles3Bundle:EditorialPublication')->findOneById(1));
        
        $options=["validator"=>$this->get('security.authorization_checker')->isGranted('ROLE_EDITORIAL_VALIDATEUR')];

        $form = $this->createForm('Pericles3Bundle\Form\EditorialType', $editorial,$options);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($editorial);
            $em->flush();

            return $this->redirectToRoute('backoffice_editorial_show', array('id' => $editorial->getId()));
        }

        return $this->render('BackOffice/editorial/new.html.twig', array(
            'editorial' => $editorial,
            'form' => $form->createView(),
        ));
    }
    
    
    

    /**
     * Finds and displays a Editorial entity.
     *
     * @Route("/show_first", name="backoffice_editorial_show_first")
     * @Method("GET")
     */
    public function showFirstAction()
    {        
        $em = $this->getDoctrine()->getManager();
        $editorial = $em->getRepository('Pericles3Bundle:Editorial')->findOneById(1);

        return $this->render('BackOffice/editorial/show_first.html.twig', array(
            'editorial' => $editorial
        ));
    }
    
    /**
     * Displays a form to edit an existing Editorial entity.
     *
     * @Route("/edit_first", name="backoffice_editorial_edit_first")
     * @Method({"GET", "POST"})
     */
    public function editFirstAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $editorial = $em->getRepository('Pericles3Bundle:Editorial')->findOneById(1);
        $editForm = $this->createForm('Pericles3Bundle\Form\EditorialType', $editorial,['simple'=>true]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($editorial);
            $em->flush();
            return $this->redirectToRoute('backoffice_editorial_show_first');
        }

        return $this->render('BackOffice/editorial/edit_first.html.twig', array(
            'editorial' => $editorial,
            'edit_form' => $editForm->createView()
        ));
    }

    
    

    /**
     * Finds and displays a Editorial entity.
     *
     * @Route("/{id}", name="backoffice_editorial_show")
     * @Method("GET")
     */
    public function showAction(Editorial $editorial)
    {
        $deleteForm = $this->createDeleteForm($editorial);

        return $this->render('BackOffice/editorial/show.html.twig', array(
            'editorial' => $editorial,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    

    /**
     * Displays a form to edit an existing Editorial entity.
     *
     * @Route("/{id}/edit", name="backoffice_editorial_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Editorial $editorial)
    {
        $deleteForm = $this->createDeleteForm($editorial);
         
        $options=["validator"=>$this->get('security.authorization_checker')->isGranted('ROLE_EDITORIAL_VALIDATEUR')];
 
        $editForm = $this->createForm('Pericles3Bundle\Form\EditorialType', $editorial,$options);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($editorial);
            $em->flush();

            return $this->redirectToRoute('backoffice_editorial_show', array('id' => $editorial->getId()));
        }

        return $this->render('BackOffice/editorial/edit.html.twig', array(
            'editorial' => $editorial,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Editorial entity.
     *
     * @Route("/{id}", name="backoffice_editorial_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Editorial $editorial)
    {
        $form = $this->createDeleteForm($editorial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($editorial);
            $em->flush();
        }

        return $this->redirectToRoute('backoffice_editorial_index');
    }

    /**
     * Creates a form to delete a Editorial entity.
     *
     * @param Editorial $editorial The Editorial entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Editorial $editorial)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_editorial_delete', array('id' => $editorial->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
