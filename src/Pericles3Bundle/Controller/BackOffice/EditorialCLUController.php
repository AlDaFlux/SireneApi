<?php


namespace Pericles3Bundle\Controller\BackOffice;


use Pericles3Bundle\Entity\EditorialCLU;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Editorialclu controller.
 *
 * @Route("backoffice/editorial/clu")
 */
class EditorialCLUController extends Controller
{
    /**
     * Lists all editorialCLU entities.
     *
     * @Route("/", name="backoffice_editorial_clu_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lastCGU= $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:EditorialCLU')->findLast();        
        $editorialCLUs = $em->getRepository('Pericles3Bundle:EditorialCLU')->findAll();

        return $this->render('BackOffice/editorial/clu/index.html.twig', array(
            'editorialCLUs' => $editorialCLUs,
            'lastCGU' => $lastCGU,
        ));
    }

    /**
     * Creates a new editorialCLU entity.
     *
     * @Route("/new", name="backoffice_editorial_clu_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $editorialCLU = new Editorialclu();
        $form = $this->createForm('Pericles3Bundle\Form\EditorialCLUType', $editorialCLU);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $file = $editorialCLU->getFichier();
            $fileName =$editorialCLU->getDatePublication()->format("Y_m_d")."_".strtolower($this->getParameter('application_name'))."_cgu.pdf";
            $editorialCLU->setFichier($fileName);
            $file->move($this->getParameter('clu_directory'),$fileName);
            $em->persist($editorialCLU);
            $em->flush();

            return $this->redirectToRoute('backoffice_editorial_clu_show', array('id' => $editorialCLU->getId()));
        }

        return $this->render('BackOffice/editorial/clu/new.html.twig', array(
            'editorialCLU' => $editorialCLU,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a editorialCLU entity.
     *
     * @Route("/{id}", name="backoffice_editorial_clu_show")
     * @Method("GET")
     */
    public function showAction(EditorialCLU $editorialCLU)
    {
        $deleteForm = $this->createDeleteForm($editorialCLU);

        return $this->render('BackOffice/editorial/clu/show.html.twig', array(
            'editorialCLU' => $editorialCLU,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing editorialCLU entity.
     *
     * @Route("/{id}/edit", name="backoffice_editorial_clu_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EditorialCLU $editorialCLU)
    {
        $deleteForm = $this->createDeleteForm($editorialCLU);
        $editForm = $this->createForm('Pericles3Bundle\Form\EditorialCLUType', $editorialCLU);
        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        
        
        if ($editForm->isSubmitted() && $editForm->isValid()) 
        {
            $file = $editorialCLU->getFichier();
            $fileName =$editorialCLU->getDatePublication()->format("Y_m_d")."_".strtolower($this->getParameter('application_name'))."_cgu.pdf";
            $editorialCLU->setFichier($fileName);
            $file->move($this->getParameter('clu_directory'),$fileName);
            $em->persist($editorialCLU);
            $em->flush();
            return $this->redirectToRoute('backoffice_editorial_clu_show', array('id' => $editorialCLU->getId()));
        }
        

        return $this->render('BackOffice/editorial/clu/edit.html.twig', array(
            'editorialCLU' => $editorialCLU,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a editorialCLU entity.
     *
     * @Route("/{id}", name="backoffice_editorial_clu_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EditorialCLU $editorialCLU)
    {
        $form = $this->createDeleteForm($editorialCLU);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($editorialCLU);
            $em->flush();
        }

        return $this->redirectToRoute('backoffice_editorial_clu_index');
    }

    /**
     * Creates a form to delete a editorialCLU entity.
     *
     * @param EditorialCLU $editorialCLU The editorialCLU entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EditorialCLU $editorialCLU)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_editorial_clu_delete', array('id' => $editorialCLU->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
