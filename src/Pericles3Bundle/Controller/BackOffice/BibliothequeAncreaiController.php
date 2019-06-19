<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pericles3Bundle\Entity\BibliothequeAncreai;
use Pericles3Bundle\Entity\ReferentielPublic;

use Pericles3Bundle\Form\BibliothequeAncreaiType;

/**
 * BibliothequeAncreai controller.
 *
 * @Route("/backoffice/bibliothequeancreai")
 */
class BibliothequeAncreaiController extends Controller
{
    
    
     
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/", name="backoffice_bibliotheque_ancreai_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findBy(array(),array('dateUpdate' => 'DESC'));
        $ReferentielsPublic = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findNonEnCours();
        $editorial = $em->getRepository('Pericles3Bundle:Editorial')->findOneById(1);

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/bibliothequeancreai/index.html.twig', array(
                'bibliothequeAncreais' => $bibliothequeAncreais,
                'ReferentielsPublic' => $ReferentielsPublic,
                'editorial' => $editorial,
            ));
        }
    } 
    
     
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/anesm", name="backoffice_bibliotheque_ancreai_index_anesm")
     * @Method("GET")
     */
    public function indexANESMAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findAnesm();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/bibliothequeancreai/list.html.twig', array(
                'bibliothequeAncreais' => $bibliothequeAncreais,
                'titre' => "Liste des entrées ANESM"
            ));
        }
    } 
    
         
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/liens_morts", name="backoffice_bibliotheque_ancreai_liensmorts")
     * @Method("GET")
     */
    public function indexLienMortAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findLienARefaire();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/bibliothequeancreai/list.html.twig', array(
                'bibliothequeAncreais' => $bibliothequeAncreais,
                'titre' => "Liens morts"
            ));
        }
    } 
     
         
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/pdf", name="backoffice_bibliotheque_pdf")
     * @Method("GET")
     */
    public function indexPdfAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findPdf();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_SUPERVISOR'))        
        {
            return $this->render('BackOffice/bibliothequeancreai/list.html.twig', array(
                'bibliothequeAncreais' => $bibliothequeAncreais,
                'titre' => "PDFs"
            ));
        }
    } 
     

    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/pdf/getcache", name="backoffice_bibliotheque_pdf_cache")
     * @Method("GET")
     */
    public function indexPdfCacheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findPdfSansCache();

        $this->addFlash('success', "Récupération d'un pdf");
        
        
        foreach ($bibliothequeAncreais as $biblio) 
        {
            $content = file_get_contents($biblio->GetHref());
            $uploadPath = WEB_DIR.'/cache_biblio/';
            $path_parts=pathinfo($biblio->GetHref());
            $fichier=$path_parts['filename'].".".$path_parts['extension'];
            $this->addFlash('success', "L'élément va bien être rajouté ! : ".$fichier);
            
            $biblio->SetCache($fichier);
            $em->persist($biblio);
            $em->flush();
            file_put_contents($uploadPath.$fichier, $content);
        }
        return $this->redirectToRoute('backoffice_bibliotheque_pdf');
    } 
         
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/code_retour/{code_retour}", name="backoffice_bibliotheque_ancreai_code_retour")
     * @Method("GET")
     */
    public function indexcodeRetourAction($code_retour)
    {
        $em = $this->getDoctrine()->getManager();
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findCodeRetour($code_retour);

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            return $this->render('BackOffice/bibliothequeancreai/list.html.twig', array(
                'bibliothequeAncreais' => $bibliothequeAncreais,
                'titre' => "Erreur ".$code_retour
            ));
        }
    } 
     
     
    
     
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/public_{id}", name="backoffice_bibliotheque_ancreai_index_public")
     * @Method("GET")
     */
    public function indexPublicAction(ReferentielPublic $ReferentielPublic)
    {
        $em = $this->getDoctrine()->getManager(); 
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findByReferentielPublic($ReferentielPublic);
        return $this->render('BackOffice/bibliothequeancreai/list_bypublic.html.twig', array(
            'bibliothequeAncreais' => $bibliothequeAncreais,
            'ReferentielPublic' => $ReferentielPublic
        ));
    } 
        
     
    /**
     * Lists all BibliothequeAncreai entities.
     *
     * @Route("/public_{id}/rbpp", name="backoffice_bibliotheque_ancreai_rbpp_index_public")
     * @Method("GET")
     */
    public function indexPublicRbppAction(ReferentielPublic $ReferentielPublic)
    {
        $em = $this->getDoctrine()->getManager();
        
        $bibliothequeAncreais = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findByReferentielPublicArray($ReferentielPublic);
        $bibliothequeAncreaisCount = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findByReferentielPublicRbppCountRef($ReferentielPublic);
        
                
        foreach ($bibliothequeAncreais as $ba)
        {
           $NbibliothequeAncreais[$ba['id']]=$ba;
           $NbibliothequeAncreais[$ba['id']]['NbRef']=0;
        }

        foreach ($bibliothequeAncreaisCount as $ba)
        {
           $NbibliothequeAncreais[$ba['id']]['NbRef']=$ba['NbRef'];
        }
         
        return $this->render('BackOffice/bibliothequeancreai/list_bypublic_rbpp.html.twig', array(
            'bibliothequeAncreais' => $NbibliothequeAncreais, 
            'ReferentielPublic' => $ReferentielPublic
        ));
    }

     
    
     
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/public_{id}/add/new_{id_biblioancreai}", name="backoffice_bibliotheque_ancreai_addtorref_commit")
     * @Method("GET")
     */
    public function addBiblioToRefAction(ReferentielPublic $referentielPublic,$id_biblioancreai)
    {
        $em = $this->getDoctrine()->getManager();
        $bibliothequesAncreai=$em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findOneById($id_biblioancreai);
        
        
        $referentielPublic->addBibliothequesAncreai($bibliothequesAncreai);
        $bibliothequesAncreai->addReferentielPublic($referentielPublic);
        $em->persist($referentielPublic);
        $em->flush();
        /*
        $this->addFlash('success', "L'élément va bien être rajouté ! ");
            $this->CheckLink($bibliothequeAncreai);backoffice_bibliotheque_ancreai_edit
            */
        
        return $this->redirectToRoute('backoffice_bibliotheque_ancreai_addtorref', array('id' => $referentielPublic->getId()));
    
    }
    
    
     
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/public_{id}/delete/biblio_{id_biblioancreai}", name="backoffice_bibliotheque_ancreai_addtorref_delete")
     * @Method("GET")
     */
    public function DeleteRefAction(ReferentielPublic $referentielPublic,$id_biblioancreai)
    {
        $em = $this->getDoctrine()->getManager();
        $bibliothequesAncreai=$em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findOneById($id_biblioancreai);
        
        $referentielPublic->removeBibliothequesAncreai($bibliothequesAncreai);
        $bibliothequesAncreai->removeReferentielPublic($referentielPublic);
        $em->persist($referentielPublic);
        $em->flush();
        $this->addFlash('success', "L'élément à bien été supprimé ! ");
        return $this->redirectToRoute('backoffice_bibliotheque_ancreai_index_public', array('id' => $referentielPublic->getId()));
    }
    
    
    
    /**
     * Finds and displays a ReferentielPublic entity.
     *
     * @Route("/public_{id}/add", name="backoffice_bibliotheque_ancreai_addtorref")
     * @Method("GET")
     */
    public function showBibliothequeAction(ReferentielPublic $referentielPublic)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('BackOffice/bibliothequeancreai/add_bypublic.html.twig', array(
            'ReferentielPublic' => $referentielPublic,
            'BibliothequesAncreaiToAdd' => $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findByReferentielNotPublic($referentielPublic)
        ));
    }
    
    
    
    
    
    
    
    /**
     * Creates a new BibliothequeAncreai entity.
     *
     * @Route("/new", name="backoffice_bibliotheque_ancreai_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $bibliothequeAncreai = new BibliothequeAncreai();
        
        
        $form = $this->createForm('Pericles3Bundle\Form\BibliothequeAncreaiType', $bibliothequeAncreai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bibliothequeAncreai->setDateUpdate(new \DateTime());
            $bibliothequeAncreai->setDateCreate(new \DateTime());
            $bibliothequeAncreai->setCreatedBy($this->GetUser());
            $bibliothequeAncreai->setCodeRetour(0);
            $bibliothequeAncreai->setLastModifiedBy($this->GetUser());
            
              /*
            $file = $bibliothequeAncreai->getCache();
            if ($file)
            {
                $fileName = $file->getClientOriginalName();
                $bibliothequeAncreai->setCache($fileName);
                $file->move(
                    $this->getParameter('cache_biblio_directory'),
                    $fileName
                );
                
            }
            */
            
            
            $em->persist($bibliothequeAncreai);
            $em->flush();

            $this->CheckLink($bibliothequeAncreai);
            return $this->redirectToRoute('backoffice_bibliotheque_ancreai_show', array('id' => $bibliothequeAncreai->getId()));
        }

        return $this->render('BackOffice/bibliothequeancreai/new.html.twig', array(
            'bibliothequeAncreai' => $bibliothequeAncreai,
            'form' => $form->createView(),
        ));
    }

    
    /**
     * Creates a new BibliothequeAncreai entity.
     *
     * @Route("/public_{id}/new", name="backoffice_bibliotheque_ancreai_bypublic_new")
     * @Method({"GET", "POST"})
     */
    public function newByPubAction(ReferentielPublic $ReferentielPublic,Request $request)
    {
        
        $bibliothequeAncreai = new BibliothequeAncreai();
        $form = $this->createForm('Pericles3Bundle\Form\BibliothequeAncreaiType', $bibliothequeAncreai,["avec_public" => false]);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bibliothequeAncreai->setDateUpdate(new \DateTime());
            $bibliothequeAncreai->setDateCreate(new \DateTime());
            $bibliothequeAncreai->setCreatedBy($this->GetUser());
            $bibliothequeAncreai->setLastModifiedBy($this->GetUser());
            $bibliothequeAncreai->addReferentielPublic($ReferentielPublic);
            
            
            $file = $bibliothequeAncreai->getCache();
            $fileName = $file->getClientOriginalName();
            $bibliothequeAncreai->setCache($fileName);
            $file->move(
                $this->getParameter('cache_biblio_directory'),
                $fileName
            );
            $em->persist($bibliothequeAncreai);
            $em->flush();

            $this->CheckLink($bibliothequeAncreai);
            
            return $this->redirectToRoute('backoffice_bibliotheque_ancreai_index_public', array('id' => $ReferentielPublic->getId()));
        }

        return $this->render('BackOffice/bibliothequeancreai/new.html.twig', array(
            'bibliothequeAncreai' => $bibliothequeAncreai,
            'ReferentielPublic' => $ReferentielPublic,
            'form' => $form->createView(),
        ));
    }

     
    
    /**
     * Finds and displays a BibliothequeAncreai entity.
     *
     * @Route("/{id}", name="backoffice_bibliotheque_ancreai_show")
     * @Method("GET")
     */
    public function showAction(BibliothequeAncreai $bibliothequeAncreai)
    {
        $deleteForm = $this->createDeleteForm($bibliothequeAncreai);

        return $this->render('BackOffice/bibliothequeancreai/show.html.twig', array(
            'bibliothequeAncreai' => $bibliothequeAncreai,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing BibliothequeAncreai entity.
     *
     * @Route("/{id}/edit", name="backoffice_bibliotheque_ancreai_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, BibliothequeAncreai $bibliothequeAncreai)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\BibliothequeAncreaiType', $bibliothequeAncreai);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bibliothequeAncreai->setDateUpdate(new \DateTime());
            $bibliothequeAncreai->setLastModifiedBy($this->GetUser());
            
            $file = $bibliothequeAncreai->getCache();
            if ($file)
            {
                $fileName = $file->getClientOriginalName();
                $file->move($this->getParameter('cache_biblio_directory'),$fileName);
                $this->addFlash('success', "Code : ".$this->getParameter('cache_biblio_directory')."/".$fileName);
                $bibliothequeAncreai->setCache($fileName);
            }
            

            $em->persist($bibliothequeAncreai);
            $em->flush();
            $this->CheckLink($bibliothequeAncreai);
            return $this->redirectToRoute('backoffice_bibliotheque_ancreai_show', array('id' => $bibliothequeAncreai->getId()));

        }

        return $this->render('BackOffice/bibliothequeancreai/edit.html.twig', array(
            'bibliothequeAncreai' => $bibliothequeAncreai,
            'edit_form' => $editForm->createView()
        ));
    }
    
    
      /**
     * Displays a form to edit an existing BibliothequeAncreai entity.
     *
     * @Route("/{id}/edit_cache", name="backoffice_bibliotheque_ancreai_edit_cache")
     * @Method({"GET", "POST"})
     */
    public function editCacheAction(Request $request, BibliothequeAncreai $bibliothequeAncreai)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\BibliothequeAncreaiType', $bibliothequeAncreai, ["onlyfile"=>true]);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bibliothequeAncreai->setDateUpdate(new \DateTime());
            $bibliothequeAncreai->setLastModifiedBy($this->GetUser());
            $file = $bibliothequeAncreai->getCache();
            if ($file)
            {
                $fileName = $file->getClientOriginalName();
                $file->move($this->getParameter('cache_biblio_directory'),$fileName);
                $this->addFlash('success', "Code : ".$this->getParameter('cache_biblio_directory')."/".$fileName);
                $bibliothequeAncreai->setCache($fileName);
            }
            $em->persist($bibliothequeAncreai);
            $em->flush();
            $this->CheckLink($bibliothequeAncreai);
            return $this->redirectToRoute('backoffice_bibliotheque_ancreai_show', array('id' => $bibliothequeAncreai->getId()));
        }

        return $this->render('BackOffice/bibliothequeancreai/edit.html.twig', array(
            'bibliothequeAncreai' => $bibliothequeAncreai,
            'edit_form' => $editForm->createView()
        ));
    }
    
    
    
    
    
    /**
     * Displays a form to edit an existing BibliothequeAncreai entity.
     *
     * @Route("/{id}/checklink", name="backoffice_bibliotheque_ancreai_checklink")
     * @Method({"GET", "POST"})
     */
    public function checkLinkAction(BibliothequeAncreai $bibliothequeAncreai)
    {
        $this->CheckLink($bibliothequeAncreai);
        return $this->redirectToRoute('backoffice_bibliotheque_ancreai_show', array('id' => $bibliothequeAncreai->getId()));

    }
    
    
    
    
    
        public function CheckLink(BibliothequeAncreai $bibliothequeAncreai)
        {
            $em = $this->getDoctrine()->getManager();
            $a = @get_headers($bibliothequeAncreai->getHref());
            $code=substr($a[0],9,3);
            $bibliothequeAncreai->setLastTest(new \DateTime());
            $bibliothequeAncreai->setCodeRetour($code);
            $em->persist($bibliothequeAncreai);
            $em->flush();
            if ($code==200) $this->addFlash('success', "Code : ".$code);
            else  $this->addFlash('danger', "Code : ".$code);
        }
        

        
    

    /**
     * Deletes a BibliothequeAncreai entity.
     *
     * @Route("/{id}/delete", name="backoffice_bibliotheque_ancreai_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(BibliothequeAncreai $bibliothequeAncreai)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($bibliothequeAncreai);
        $em->flush();
        $this->addFlash('success', "L'élément à bien été supprimé");
        return $this->redirectToRoute('backoffice_bibliotheque_ancreai_index');
    }

    /**
     * Creates a form to delete a BibliothequeAncreai entity.
     *
     * @param BibliothequeAncreai $bibliothequeAncreai The BibliothequeAncreai entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(BibliothequeAncreai $bibliothequeAncreai)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backoffice_bibliotheque_ancreai_delete', array('id' => $bibliothequeAncreai->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
