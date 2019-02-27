<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pericles3Bundle\Entity\ProfilesRoles;

use Pericles3Bundle\Form\ProfilesRolesType;

/**
 * ProfilesRoles controller.
 *
 * @Route("/backoffice/profilesroles")
 */
class ProfilesRolesController extends Controller
{
    /**
     * Lists all ProfilesRoles entities.
     *
     * @Route("/", name="backoffice_profilesroles_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $profilesRoles = $em->getRepository('Pericles3Bundle:ProfilesRoles')->findAll();

        return $this->render('BackOffice/profilesroles/index.html.twig', array(
            'profilesRoles' => $profilesRoles,
        ));
    }

    /**
     * Creates a new ProfilesRoles entity.
     *
     * @Route("/new_{type}", name="backoffice_profilesroles_new")
     * @Method({"GET", "POST"})
     */
    public function newAction($type,Request $request)
    {
        
        $type= strtoupper($type);
                        
         

         
        $profilesRole = new ProfilesRoles();
        $form = $this->createForm('Pericles3Bundle\Form\ProfilesRolesType', $profilesRole,['tpe_user'=>$type]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $profilesRole->setTypeUser($type);
            $em->persist($profilesRole);
            $em->flush();

            return $this->redirectToRoute('backoffice_profilesroles_show', array('id' => $profilesRole->getId()));
        }

        return $this->render('BackOffice/profilesroles/new.html.twig', array(
            'profilesRole' => $profilesRole,
            'form' => $form->createView(),
        ));
    }

    
    /**
     * Finds and displays a ProfilesRoles entity.
     *
     * @Route("/{id}", name="backoffice_profilesroles_show")
     * @Method("GET")
     */
    public function showAction(ProfilesRoles $profilesRole)
    {
        $deleteForm = $this->createDeleteForm($profilesRole);

        return $this->render('BackOffice/profilesroles/show.html.twig', array(
            'profilesRole' => $profilesRole,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ProfilesRoles entity.
     *
     * @Route("/{id}/edit", name="backoffice_profilesroles_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProfilesRoles $profilesRole)
    {
        $deleteForm = $this->createDeleteForm($profilesRole);
        $editForm = $this->createForm('Pericles3Bundle\Form\ProfilesRolesType', $profilesRole,['tpe_user'=>$profilesRole->getTypeUser()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($profilesRole);
            $em->flush();
            return $this->redirectToRoute('backoffice_profilesroles_show', array('id' => $profilesRole->getId()));
        }
        

        return $this->render('BackOffice/profilesroles/edit.html.twig', array(
            'profilesRole' => $profilesRole,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ProfilesRoles entity.
     *
     * @Route("/{id}", name="backoffice_profilesroles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProfilesRoles $profilesRole)
    {
        $form = $this->createDeleteForm($profilesRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($profilesRole);
            $em->flush();
        }

        return $this->redirectToRoute('backoffice_profilesroles_index');
    }

    /**
     * Creates a form to delete a ProfilesRoles entity.
     *
     * @param ProfilesRoles $profilesRole The ProfilesRoles entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProfilesRoles $profilesRole)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_profilesroles_delete', array('id' => $profilesRole->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
