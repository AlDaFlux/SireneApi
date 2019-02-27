<?php


namespace Pericles3Bundle\Controller\BackOffice;


use Pericles3Bundle\Entity\Pericles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

/**
 * Pericle controller.
 *
 * @Route("backoffice/pericles")
 */
class PericlesController extends Controller
{
    /**
     * Lists all pericle entities.
     *
     * @Route("/", name="backoffice_pericles_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pericles = $em->getRepository('Pericles3Bundle:Pericles')->findAll();
        return $this->render('BackOffice/pericles/index.html.twig', array(
            'pericles' => $pericles,
        ));
    }

    
    
    /**
     * Creates a new pericle entity.
     *
     * @Route("/reaffecte", name="backoffice_pericles_reaffecte")
     * @Method({"GET"})
     */
    public function newReaffecteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pericles = $em->getRepository('Pericles3Bundle:Pericles')->findAll();
       // $pericl = $em->getRepository('Pericles3Bundle:Pericles')->findOneById(273);
 
        foreach ($pericles as $pericl)
        { 
            /*
            if (strlen($pericl->GetFinessText())==8)
            {
                $pericl->SetFinessText("0".$pericl->GetFinessText());
            }
             * 
             */
     
            if (strlen($pericl->GetFinessText())==9)
            {
                /*
                $repositoryFiness = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Finess');
                $Finess = $repositoryFiness->findOneByCodeFiness($pericl->GetFinessText());
                if ($Finess)
                {
                    $pericl->setFinessEtablissement($Finess);
                    $em->persist($pericl);
                    $em->flush();
                    if ($Finess->getHaveEtablissement())
                    {
                        $pericl->setEtablissement($Finess->getEtablissement());
                        $em->persist($pericl);
                        $em->flush();
                    }
                    
                }
                 *  
                 */
                
                
              
                    $this->AddFlash("success",$pericl->GetFinessText());
                    
                $repositoryFinessGestionnaire = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:FinessGestionnaire');
                $Finess = $repositoryFinessGestionnaire->findOneByCodeFiness($pericl->GetFinessText());
               
                if ($Finess)
                {
                    $this->AddFlash("success",$pericl->GetFinessText());
                    $pericl->setFinessGestionnaire($Finess);
                    $em->persist($pericl);
                    $em->flush();
                    
                    $this->AddFlash("success",$pericl." A gestionnaire");
                    
                    if ($Finess->getHaveGestionnaire())
                    {
                        $this->AddFlash("success",$pericl." A gestionnaire : ".$Finess->getGestionnaire());
                        $pericl->setGestionnaire($Finess->getGestionnaire());
                        $em->persist($pericl);
                        $em->flush();
                    }
                    
                }
                else
                {
                    $this->AddFlash("success","Pas finess gestionnaire");
                }
                
            }
 
 
            if ($pericl->GetFinessEtablissement())
            {
                $gestionnaire_finess=$pericl->GetFinessEtablissement()->GetGestionnaire();
                
                $pericl->SetFinessGestionnaire($gestionnaire_finess);
                $gestionnaire_finess->addPericle($pericl);
                
                
                
                if ($gestionnaire_finess->getHaveGestionnaire())
                    {
                        $this->AddFlash("success",$pericl." A gestionnaire : ".$gestionnaire_finess->getGestionnaire());
                        $pericl->setGestionnaire($gestionnaire_finess->getGestionnaire());
                        $em->persist($pericl);
                        $em->flush();
                    }
                    
                $em->persist($pericl);
                $em->flush();
                $em->persist($gestionnaire_finess);
             
                $em->flush();
          }
          
        
        }
        
        return $this->redirectToRoute('backoffice_pericles_index');

        
    }
    
    
    
    
    /**
     * Creates a new pericle entity.
     *
     * @Route("/cotisation", name="backoffice_pericles_cotisation")
     * @Method({"GET"})
     */
    public function newcotisationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pericles = $em->getRepository('Pericles3Bundle:Pericles')->findAll();
        $cotisPericles = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ModeCotisation')->findOneById(3);
        foreach ($pericles as $pericl)
        { 
            $etablissement=$pericl->GetEtablissement();
            if ($etablissement)
            {
                    $etablissement->SetModeCotisation($cotisPericles);
                    $em->persist($etablissement);
            }
            $em->flush();
        }
        return $this->redirectToRoute('backoffice_pericles_index');
    }
    
    
    
    /**
     * Creates a new pericle entity.
     *
     * @Route("/majdepartement", name="backoffice_pericles_majdepartement")
     * @Method({"GET"})
     */
    public function majDepartementAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pericles = $em->getRepository('Pericles3Bundle:Pericles')->findAll();
        
        
        
        //$cotisPericles = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ModeCotisation')->findOneById(3);
        foreach ($pericles as $pericl)
        { 
//            $this->AddFlash("success", "-".$pericl);
            $etablissement=$pericl->GetEtablissement();
            $finess=$pericl->GetFinessEtablissement();
            if ($etablissement)
            {
                $pericl->SetDepartement($etablissement->GetDepartement());
                $em->persist($pericl);
            }
            elseif($finess)
            {
                if ($finess->GetDepartement())
                {
                    $pericl->SetDepartement($finess->GetDepartement());
                    $em->persist($pericl);
                }
            }
            else
            {
                $adresse_blocs= explode(" ", $pericl->GetAdresse());
                foreach ($adresse_blocs as  $adresse_bloc)
                {
                    if (strlen($adresse_bloc)==5 && is_numeric($adresse_bloc))
                    {
                        $dep=substr($adresse_bloc, 0,2);
                        $departement = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Departement')->findOneById($dep);
                        $pericl->SetDepartement($departement);
                        $em->persist($pericl);
                    }
                }
                
            }
            $em->flush();
        }
        return $this->redirectToRoute('backoffice_pericles_prospects');
    }
    
    
    
    
    
    /**
     * Creates a new pericle entity.
     *
     * @Route("/comparaison", name="backoffice_pericles_comparaison")
     * @Method({"GET"})
     */
    public function newcomparaisonAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pericles = $em->getRepository('Pericles3Bundle:Pericles')->findsansEtab();
        //$cotisPericles = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ModeCotisation')->findOneById(3);
        $etabs = $em->getRepository('Pericles3Bundle:Etablissement')->findPericlesSansPericles();
        
     
            return $this->render('BackOffice/pericles/comparaison.html.twig', array(
            'pericles' => $pericles,
            'etabs' => $etabs,
        ));
    }
    
    
    
    /**
     * Creates a new pericle entity.
     *
     * @Route("/prospects", name="backoffice_pericles_prospects")
     * @Method({"GET"})
     */
    public function newProspectsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pericles=null;
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_SUPERVISOR')) 
        {
            $pericles = $em->getRepository('Pericles3Bundle:Pericles')->findsansEtab();
        }
        elseif ($this->GetUser()->GetCreai())
        {
            $pericles = $em->getRepository('Pericles3Bundle:Pericles')->findSansEtabParCreai($this->GetUser()->GetCreai());
            
        }
        
            return $this->render('BackOffice/pericles/prospects.html.twig', array(
            'pericles' => $pericles 
        ));
    }
    
    
    
    
    
    
    
    
    /**
     * Creates a new pericle entity.
     *
     * @Route("/new", name="backoffice_pericles_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $pericle = new Pericle();
        $form = $this->createForm('Pericles3Bundle\Form\PericlesType', $pericle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pericle);
            $em->flush();

            return $this->redirectToRoute('backoffice_pericles_show', array('id' => $pericle->getId()));
        }

        return $this->render('BackOffice/pericles/new.html.twig', array(
            'pericle' => $pericle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a pericle entity.
     *
     * @Route("/{id}", name="backoffice_pericles_show")
     * @Method("GET")
     */
    public function showAction(Pericles $pericle)
    {
        $deleteForm = $this->createDeleteForm($pericle);

        return $this->render('BackOffice/pericles/show.html.twig', array(
            'pericle' => $pericle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing pericle entity.
     *
     * @Route("/{id}/edit", name="backoffice_pericles_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Pericles $pericle)
    {
        $deleteForm = $this->createDeleteForm($pericle);
        $editForm = $this->createForm('Pericles3Bundle\Form\PericlesType', $pericle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_pericles_show', array('id' => $pericle->getId()));
        }

        return $this->render('BackOffice/pericles/edit.html.twig', array(
            'pericle' => $pericle,
            'edit_form' => $editForm->createView() 
        ));
    }

    /**
     * Deletes a pericle entity.
     *
     * @Route("/{id}", name="backoffice_pericles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Pericles $pericle)
    {
        $form = $this->createDeleteForm($pericle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pericle);
            $em->flush();
        }

        return $this->redirectToRoute('backoffice_pericles_index');
    }

    /**
     * Creates a form to delete a pericle entity.
     *
     * @param Pericles $pericle The pericle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pericles $pericle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_pericles_delete', array('id' => $pericle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
