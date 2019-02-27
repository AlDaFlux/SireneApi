<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Pericles3Bundle\Entity\DemandeEtablissement;
use Pericles3Bundle\Entity\DemandeEtat;
use Pericles3Bundle\Entity\DemandeGestionnaire;
use Pericles3Bundle\Entity\Creai;
use Pericles3Bundle\Entity\Gestionnaire;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\DemandeInfos;

use Pericles3Bundle\Form\DemandeEtablissementType;
use Pericles3Bundle\Form\DemandeInfosType;
use Pericles3Bundle\Form\DemandeGestionnaireType;



/**
 * DemandeEtablissement controller.
 *
 * @Route("/backoffice/demande")
 */
class DemandeController extends Controller
{
    
    
    
    
    
    /**
     * Lists all DemandeEtablissement entities.
     *
     * @Route("/", name="backoffice_demande_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $creai=$this->getUser()->GetCreai();
        
        $demandeEtablissementsNonFines=null;
        $demandeEtablissementsFines=null;
        $demandeInfosSansCreai=null;
        $demandeInfosNonFines=null;
        $demandeInfosFines=null;
     
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_TRAITEMENT_DEMANDE'))
        {
            $demandeEtablissementsNonFines  = $em->getRepository('Pericles3Bundle:DemandeEtablissement')->findNonFini();
            $demandeEtablissementsFines  = $em->getRepository('Pericles3Bundle:DemandeEtablissement')->findFini(2);
            $demandeInfosSansCreai = $em->getRepository('Pericles3Bundle:DemandeInfos')->findNonFiniSansCreai();
        }
         
        if ($creai && $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $demandeInfosNonFines  = $em->getRepository('Pericles3Bundle:DemandeInfos')->findNonFiniCreai($creai);
            $demandeInfosFines   = $em->getRepository('Pericles3Bundle:DemandeInfos')->findFiniCreai($creai,5);
        }
        
        return $this->render('BackOffice/Demande/index.html.twig', array(
                'demandeEtablissementsNonFinies' => $demandeEtablissementsNonFines,
                'demandeEtablissementsFinies' => $demandeEtablissementsFines,
                'demandeInfosSansCreai'=>$demandeInfosSansCreai,
            'demandeInfosNonFines' => $demandeInfosNonFines,
            'demandeInfosFines' => $demandeInfosFines,
        ));


        
        
        
    }
 
    
    
    /**
     * Lists all DemandeEtablissement entities.
     *
     * @Route("/etablissement", name="backoffice_demande_etablissement_index")
     * @Method("GET")
     */
    public function indexEtablissementAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $creai=$this->getUser()->GetCreai();
        if ($creai)
        {
            $demandeEtablissements  = $em->getRepository('Pericles3Bundle:DemandeEtablissement')->findByCreai($creai);
        }
        else
        {
            $demandeEtablissements  = $em->getRepository('Pericles3Bundle:DemandeEtablissement')->findAll();
        }
        
        
        return $this->render('BackOffice/Demande/Etablissement/liste.html.twig', array(
            'demandeEtablissements' => $demandeEtablissements,
            'targetCreai' => $creai,

        ));
    }
         /**
     * Lists all DemandeEtablissement entities.
     *
     * @Route("/etablissement/all", name="backoffice_demande_etablissement_index_all")
     * @Method("GET")
     */
    public function indexEtablissementAllAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_TRAITEMENT_DEMANDE') or $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_SUPERVISOR'))
        {
           $em = $this->getDoctrine()->getManager();
           $demandeEtablissements  = $em->getRepository('Pericles3Bundle:DemandeEtablissement')->findAll();
            return $this->render('BackOffice/Demande/Etablissement/liste.html.twig', array(
                'demandeEtablissements' => $demandeEtablissements,
            ));
           }
    }
        
    
           

    
     
    
    
    
    
    /**
     * Creates a new DemandeEtablissement entity.
     *
     * @Route("/etablissement/new", name="demande_etablissement_new")
     * @Method({"GET", "POST"})
     */
    public function newEtablissementazeaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $demandeEtablissement = new DemandeEtablissement();
        $form = $this->createForm('Pericles3Bundle\Form\DemandeEtablissementType', $demandeEtablissement);
        $form->handleRequest($request);
        $email=$form->getData()->GetEmail();
        
        if ($em->getRepository('Pericles3Bundle:User')->nbParMail($email))
        {
            $this->addFlash('error', "L'utilisateur <i>".$email."</i> à déja un compte ARSENE : ");
        }
        elseif ($em->getRepository('Pericles3Bundle:DemandeEtablissement')->nbParMail($email))
        {
            $this->addFlash('error', "Une demande de création d'établissement avec le mail <i>".$email."</i> à déja éfféctué : ");
        }
        elseif ($em->getRepository('Pericles3Bundle:DemandeEtablissement')->nbParFiness($demandeEtablissement->getFinessCode()))
        {
            $this->addFlash('error', "Une demande de création d'établissement avec le FINESS <i>".$demandeEtablissement->getFinessCode()."</i> à déja éfféctué  ");
        }
        elseif ($form->isSubmitted() && $form->isValid()) 
        {
            $num_finess=$demandeEtablissement->getFinessCode();
            $Finess=$this->GetFinessByCode($num_finess);
            if ($Finess) 
            {
                $demandeEtablissement->setFiness($Finess);
                $Finess->setDemandesEtablissement($demandeEtablissement);
                $em->persist($Finess);
                $this->AddFlash("success","Le code finess a été trouvé");
            }
            else
            {
                if ($num_finess) $this->AddFlash("error","Aucune correspondance pour le code finess <b>'".$num_finess."'</b> n'a été trouvé");
                else $this->AddFlash("warning","Pas de FINESS !! ");
            }
            
            $demandeEtablissement->setCreai($this->getUser()->GetCreai());
            $demandeEtablissement->setEtat($em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById(1));
            $demandeEtablissement->setDateDemande(new \DateTime(date("Y-m-d H:i:s")));
            $em->persist($demandeEtablissement);
            $em->flush();

            $this->EnvoiMailDemandeEtablissement($demandeEtablissement,$this->getParameter('mail_to'));
            
            return $this->redirectToRoute('demande_etablissement_show', array('id' => $demandeEtablissement->getId()));
        }
        return $this->render('BackOffice/Demande/Etablissement/new.html.twig', array(
            'demandeEtablissement' => $demandeEtablissement,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a DemandeEtablissement entity.
     *
     * @Route("/etablissement/{id}", name="demande_etablissement_show")
     * @Method("GET")
     */
    public function showAction(DemandeEtablissement $demandeEtablissement)
    {

        return $this->render('BackOffice/Demande/Etablissement/show.html.twig', array(
            'demandeEtablissement' => $demandeEtablissement
        ));
    }

    

    
    private function GetFinessByCode($num_finess)
    {
        $repositoryFiness = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Finess');
        $Finess = $repositoryFiness->findOneByCodeFiness($num_finess);
        return ($Finess);
    }
    
    /**
     * Displays a form to edit an existing DemandeEtablissement entity.
     *
     * @Route("/etablissement/{id}/edit", name="demande_etablissement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DemandeEtablissement $demandeEtablissement)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\DemandeEtablissementType', $demandeEtablissement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandeEtablissement);
            $em->flush();
            $this->AddFlash("success","La demande a bien été modifiée.");
            return $this->redirectToRoute('backoffice_demande_index');
        }

        return $this->render('BackOffice/Demande/Etablissement/edit.html.twig', array(
            'demandeEtablissement' => $demandeEtablissement,
            'edit_form' => $editForm->createView()
        ));
    }
    
    
    /**
     * Displays a form to edit an existing DemandeEtablissement entity.
     *
     * @Route("/etablissement/{id}/editancreai", name="demande_etablissement_editancreai")
     * @Method({"GET", "POST"})
     */
    public function editAncreaiAction(Request $request, DemandeEtablissement $demandeEtablissement)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\DemandeEtablissementType', $demandeEtablissement,['ancreai'=>true]);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandeEtablissement);
            $em->flush();
            $this->AddFlash("success","La demande a bien été modifiée.");
            if ($demandeEtablissement->getDemandeGestionnaire())
            {
                return $this->redirectToRoute('demande_gestionnaire_show', array('id' => $demandeEtablissement->getDemandeGestionnaire()->getId()));
            }
            else
            {
                return $this->redirectToRoute('backoffice_demande_index');
            }
        }
        return $this->render('BackOffice/Demande/Etablissement/edit.html.twig', array(
            'demandeEtablissement' => $demandeEtablissement,
            'edit_form' => $editForm->createView()
        ));
    }
    
    /**
     * Displays a form to edit an existing DemandeEtablissement entity.
     *
     * @Route("/gestionnaire/{id}/edit", name="demande_gestionnaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editGestionnaireAction(Request $request, DemandeGestionnaire $demandeGestionnaire)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\DemandeGestionnaireType', $demandeGestionnaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandeGestionnaire);
            $em->flush();
            $this->AddFlash("success","La demande a bien été modifiée.");
            return $this->redirectToRoute('backoffice_demande_index');
        }

        return $this->render('BackOffice/Demande/Gestionnaire/edit.html.twig', array(
            'demandeEtablissement' => $demandeGestionnaire,
            'edit_form' => $editForm->createView()
        ));
    }
    
    
    
    /**
     * Displays a form to edit an existing DemandeEtablissement entity.
     *
     * @Route("/gestionnaire/{id}/editancreai", name="demande_gestionnaire_editancreai")
     * @Method({"GET", "POST"})
     */
    public function editGestionnaireAncreaiAction(Request $request, DemandeGestionnaire $demandeGestionnaire)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\DemandeGestionnaireType', $demandeGestionnaire,['ancreai'=>true]);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandeGestionnaire);
            $em->flush();

            if ($demandeGestionnaire->getEtat()->getId()==3)
            {
                $fini=$em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById(3);

                $etabs='';
                foreach ($demandeGestionnaire->getDemandesEtablissement() as $DemandeEtablissement)
                {
                    $etabs.="<li>".$DemandeEtablissement;
                    $DemandeEtablissement->setEtat($fini);
                    $em->persist($DemandeEtablissement);
                    $em->flush();
                }
                $this->AddFlash("success","Les demande des établissements  : ".$etabs. "<br> -> sont conisdérés comme terminées");
            }

            $this->AddFlash("success","La demande a bien été modifiée.");
            return $this->redirectToRoute('demande_gestionnaire_show', array('id' => $demandeGestionnaire->getId()));
        }
        return $this->render('BackOffice/Demande/Gestionnaire/edit.html.twig', array(
            'demandeEtablissement' => $demandeGestionnaire,
            'edit_form' => $editForm->createView()
        ));
    }
    
    
    
    
    /**
     * Displays a form to edit an existing DemandeEtablissement entity.
     *
     * @Route("/etablissement/{id}/changestate/{id_state}", name="demande_etablissementchangestate")
     * @Method({"GET", "POST"})
     */
    public function etablissementChangeStateAction(Request $request, DemandeEtablissement $demandeEtablissement,  $id_state)
    {
        $em = $this->getDoctrine()->getManager();
        $demandeEtablissement->setEtat($em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById($id_state));
        
        $em->persist($demandeEtablissement);
        $em->flush();
        return $this->redirectToRoute('demande_etablissement_show', array('id' => $demandeEtablissement->getId()));
    }
    
    
    
    
    
    
    
    /**
     * Displays a form to edit an existing DemandeEtablissement entity.
     *
     * @Route("/etablissement/{id}/valider", name="demande_etablissement_valide")
     * @Method({"GET", "POST"})
     */
    public function valideAction(Request $request, DemandeEtablissement $demandeEtablissement)
    {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandeEtablissement);
            $em->flush();
            $this->AddFlash("success","Votre demande à bien été enregistrée, elle sera prise en compte dans les plus brefs délais");
            return $this->redirectToRoute('pericles3_backoffice');
    }
    
    
 
    
    /**
     * Deletes a DemandeEtablissement entity.
     *
     * @Route("/etablissement/delete/{id}", name="demande_etablissement_delete_get")
     * @Method("GET")
     */
    public function deleteGetAction(Request $request, DemandeEtablissement $demandeEtablissement)
    {
        $demandeGestionnaire = $demandeEtablissement->getDemandeGestionnaire();
        
            $em = $this->getDoctrine()->getManager();
            $em->remove($demandeEtablissement);
            $em->flush();
            $this->AddFlash("success","Votre demande à été suprimée");
            if ($demandeGestionnaire)
            {
                return $this->redirectToRoute('demande_gestionnaire_show', array('id' => $demandeGestionnaire->getId()));
            }
            else
            {
                return $this->redirectToRoute('pericles3_backoffice');
            }
    }

     /**
     * Deletes a DemandeEtablissement entity.
     *
     * @Route("/gestionnaire/delete/{id}", name="demande_gestionnaire_delete_get")
     * @Method("GET")
     */
    public function deleteGestionnaireGetAction(Request $request, DemandeGestionnaire $demandeGestionnaire)
    {
        if ($demandeGestionnaire->getNbEtablissements())
        {
            $this->AddFlash("danger","impossible de supprimé la demande gestionnaire sans supprimer les demandes établissements");
            return $this->redirectToRoute('demande_gestionnaire_show', array('id' => $demandeGestionnaire->getId()));
        }
        else
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($demandeGestionnaire);
            $em->flush();
            $this->AddFlash("success","Votre demande à été suprimée");
            return $this->redirectToRoute('pericles3_backoffice');
        }
    }

    

    
    
    
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Lists all DemandeEtablissement entities.
     *
     * @Route("/gestionnaire", name="backoffice_demande_gestionnaire_index")
     * @Method("GET")
     */
    public function indexGestionnaireAction()
    {
        $em = $this->getDoctrine()->getManager();
        $creai=$this->getUser()->GetCreai();
        if ($creai)
        {
            $demandesGestionnaire  = $em->getRepository('Pericles3Bundle:DemandeGestionnaire')->findByCreai($creai);
        }
        else
        {
            $demandesGestionnaire  = $em->getRepository('Pericles3Bundle:DemandeGestionnaire')->findAll();
        }
        return $this->render('BackOffice/Demande/Gestionnaire/liste.html.twig', array(
            'demandesGestionnaire' => $demandesGestionnaire,
            'targetCreai' => $creai,
        ));
    }
        
    
     /**
     * Lists all DemandeEtablissement entities.
     *
     * @Route("/gestionnaire/all", name="backoffice_demande_gestionnaire_index_all")
     * @Method("GET")
     */
    public function indexGestionnaireAllAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_TRAITEMENT_DEMANDE') or $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_SUPERVISOR'))
        {
                $em = $this->getDoctrine()->getManager();
                $demandesGestionnaire  = $em->getRepository('Pericles3Bundle:DemandeGestionnaire')->findAll();
                return $this->render('BackOffice/Demande/Gestionnaire/liste.html.twig', array(
                    'demandesGestionnaire' => $demandesGestionnaire,
                ));
        }
    }
        
    
    
    
    /**
     * Creates a new DemandeEtablissement entity.
     *
     * @Route("/gestionnaire/new", name="demande_gestionnaire_new")
     * @Method({"GET", "POST"})
     */
    public function newGestionnaireAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $demandeGestionnaire = new DemandeGestionnaire();
        $form = $this->createForm('Pericles3Bundle\Form\DemandeGestionnaireType', $demandeGestionnaire);
        $form->handleRequest($request);
        
        $email=$form->getData()->GetEmail();
        if ($em->getRepository('Pericles3Bundle:User')->nbParMail($email))
        {
            $this->addFlash('error', "L'utilisateur <i>".$email."</i> à déja un compte ARSENE : ".$em->getRepository('Pericles3Bundle:User')->nbParMail($email));
        }
        elseif ($form->isSubmitted() && $form->isValid()) {
             
            
            $demandeGestionnaire->setCreai($this->getUser()->GetCreai());
            $demandeGestionnaire->setEtat($em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById(1));

                    
            $demandeGestionnaire->setDateDemande(new \DateTime(date("Y-m-d H:i:s")));
            $em->persist($demandeGestionnaire);
            $em->flush();
            
            
            return $this->redirectToRoute('demande_gestionnaire_show', array('id' => $demandeGestionnaire->getId()));
        }
        
        return $this->render('BackOffice/Demande/Gestionnaire/new.html.twig', array(
            'demandeGestionnaire' => $demandeGestionnaire,
            'form' => $form->createView(),
        ));
    }


    
        /**
     * Finds and displays a DemandeEtablissement entity.
     *
     * @Route("/gestionnaire/{id}", name="demande_gestionnaire_show")
     * @Method({"GET", "POST"})
     */
    public function showGestionnaireAction(Request $request,DemandeGestionnaire $demandeGestionnaire)
    {
        $em = $this->getDoctrine()->getManager();
        $demandeEtablissement = new DemandeEtablissement();
        $form = $this->createForm('Pericles3Bundle\Form\DemandeEtablissementType', $demandeEtablissement,['gestionnaire'=>true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $num_finess=$demandeEtablissement->getFinessCode();
            $Finess=$this->GetFinessByCode($num_finess);
            if ($Finess) 
            {
                $demandeEtablissement->setFiness($Finess);
                $Finess->setDemandesEtablissement($demandeEtablissement);
                $em->persist($Finess);
                $this->AddFlash("success","Le code finess a été trouvé");
            }
            else
            {
                if ($num_finess) $this->AddFlash("error","Aucune correspondance pour le code finess <b>'".$num_finess."'</b> n'a été trouvé");
                else $this->AddFlash("error","Pas de FINESS !! ");
            }
            $demandeEtablissement->setEtat($em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById(1));
            $demandeEtablissement->setDateDemande(new \DateTime(date("Y-m-d H:i:s")));
            $demandeEtablissement->setCreai($this->getUser()->GetCreai());
            $demandeEtablissement->setDemandeGestionnaire($demandeGestionnaire);
            
            $em->persist($demandeEtablissement);
            $em->flush();

            $this->AddFlash("success","Etablissement rajouté");
            
            unset($demandeEtablissement);
            unset($form);
            $demandeEtablissement = new DemandeEtablissement();
            $form = $this->createForm('Pericles3Bundle\Form\DemandeEtablissementType', $demandeEtablissement,['gestionnaire'=>true]);
        } 

        return $this->render('BackOffice/Demande/Gestionnaire/show.html.twig', array(
            'demandeGestionnaire' => $demandeGestionnaire, 
            'form' => $form->createView()
        ));
    }

    
    
        /**
     * Finds and displays a DemandeEtablissement entity.
     *
     * @Route("/gestionnaire/{id}/validemail", name="demande_gestionnaire_envoimail")
     * @Method({"GET", "POST"})
     */
    public function showGestionnaireMailAction(Request $request,DemandeGestionnaire $demandeGestionnaire)
    {
        $message = \Swift_Message::newInstance()
          ->setSubject("[ARSENE] - Demande de création de gestionnaire ")
          ->setFrom($this->getParameter('mail_from'))
          ->setTo($this->getParameter('mail_to'))
          ->setBody($this->renderView(
                          'Email/demandeCreationGestionnaire.html.twig',
                          array('demandeGestionnaire' => $demandeGestionnaire)
          ),
                          'text/html'
          );
          $this->get('mailer')->send($message);
            $this->AddFlash("success","Un mail a été envoyer au CREAI pour traitement.");
        return $this->redirectToRoute('backoffice_demande_index');

    }

    
    
    /**
     * Finds and displays a DemandeEtablissement entity.
     *
     * @Route("/gestionnaire/{id}/generate", name="demande_gestionnaire_create_bydemande")
     * @Method({"GET", "POST"})
     */
    public function CreateByGestionnaireAction(Request $request,DemandeGestionnaire $demandeGestionnaire)
    {
            $em = $this->getDoctrine()->getManager();
            $gestionnaire = new Gestionnaire();

            
            $gestionnaire->setNom($demandeGestionnaire->getGestionnaireNom());
            $gestionnaire->setAdresse($demandeGestionnaire->getAdresse());
            $gestionnaire->setCodePostal($demandeGestionnaire->getCodePostal());
            $gestionnaire->setVille($demandeGestionnaire->getVille());
            $gestionnaire->setDemandeGestionnaire($demandeGestionnaire);         
            $gestionnaire->setCreatedBy($this->GetUser());
            $gestionnaire->setCreai($demandeGestionnaire->getCreai());
            
            $gestionnaire->setNewFonctionnaliteGestionnaire(true);
            
            
            $gestionnaire->setCategory($em->getRepository('Pericles3Bundle:GestionnaireCategory')->findOneById(1));
                    
            $gestionnaire->setCreatedDate(new \DateTime());
            $gestionnaire->setStockageGestionnaire($em->getRepository('Pericles3Bundle:StockageGestionnaire')->findOneById(0));
            $em->persist($gestionnaire);
            $em->flush();
            $this->AddFlash("success","Le gestionnaire à bien été créer");
            $encours=$em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById(2);
            $demandeGestionnaire->setEtat($encours);
            foreach ($demandeGestionnaire->getDemandesEtablissement() as $DemandeEtablissement)
            {
                $DemandeEtablissement->setEtat($encours);
                $em->persist($DemandeEtablissement);
                $em->flush();
            }
                    
            return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $gestionnaire->getId()));
    }
     
    
    
    
    
    
     /**
     * Lists all DemandeInfos entities.
     *
     * @Route("/infos/", name="bo_demande_info_index")
     * @Method("GET")
     */
    public function indexDemandeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $creai=$this->getUser()->GetCreai();
        if ($creai)
        {
            $demandeInfos = $em->getRepository('Pericles3Bundle:DemandeInfos')->findByCreai($creai);
        }
        else
        {
            $demandeInfos = $em->getRepository('Pericles3Bundle:DemandeInfos')->findAll();
        }

        return $this->render('BackOffice/Demande/Info/index.html.twig', array(
            'demandeInfos' => $demandeInfos,
        ));
    }
    
    
     /**
     * Lists all DemandeInfos entities.
     *
     * @Route("/infos/all", name="bo_demande_info_index_all")
     * @Method("GET")
     */
    public function indexDemandeAllAction()
    {
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_TRAITEMENT_DEMANDE') or $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_SUPERVISOR'))
        {
            $em = $this->getDoctrine()->getManager();
            $demandeInfos = $em->getRepository('Pericles3Bundle:DemandeInfos')->findAll();
            return $this->render('BackOffice/Demande/Info/index.html.twig', array(
            'demandeInfos' => $demandeInfos,
        ));
        }
    }
    
    
    
    
    
    
     /**
     * Lists all DemandeInfos entities.
     *
     * @Route("/infos/creai_{id}", name="bo_demande_info_creai")
     * @Method("GET")
     */
    public function indexDemandeCreaiAction(Creai $creai)
    {
        $em = $this->getDoctrine()->getManager();
        $demandeInfos = $em->getRepository('Pericles3Bundle:DemandeInfos')->findByCreai($creai);
        return $this->render('BackOffice/Demande/Info/index.html.twig', array(
            'demandeInfos' => $demandeInfos,
            'targetCreai' => $creai,
        ));
    }
    
    
    
    
         /**
     * Lists all DemandeInfos entities.
     *
     * @Route("/infos/ancreai", name="bo_demande_info_ancreai")
     * @Method("GET")
     */
    public function indexDemandeAnCreaiAction()
    {
        $em = $this->getDoctrine()->getManager();
        $demandeInfos = $em->getRepository('Pericles3Bundle:DemandeInfos')->findSansCreai();
        return $this->render('BackOffice/Demande/Info/index.html.twig', array(
            'demandeInfos' => $demandeInfos
        ));
    }
    
    
    
 

    
      /**
     * Displays a form to edit an existing DemandeInfos entity.
     *
     * @Route("/infos/{id}/edit", name="bo_demande_info_edit")
     * @Method({"GET", "POST"})
     */
    public function editDemandeAction(Request $request, DemandeInfos $demandeInfo)
    {
        $editForm = $this->createForm('Pericles3Bundle\Form\DemandeInfosType', $demandeInfo, ['ancreai'=>true]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandeInfo);
            $em->flush();
            $this->AddFlash("success","La demande a bien été modifiée.");
            return $this->redirectToRoute('backoffice_demande_index');
        }

        return $this->render('BackOffice/Demande/Info/edit.html.twig', array(
            'demandeInfo' => $demandeInfo,
            'edit_form' => $editForm->createView()
        ));
    }
    
    
    
    
    /**
     * Finds and displays a DemandeInfos entity.
     *
     * @Route("/infos/{id}", name="bo_demande_info_show")
     * @Method("GET")
     */
    public function showDemandeAction(DemandeInfos $demandeInfo)
    {
        return $this->render('BackOffice/Demande/Info/show.html.twig', array(
            'demandeInfo' => $demandeInfo
        ));
    }
 
    
        /**
     * Deletes a DemandeEtablissement entity.
     *
     * @Route("/infos/delete/{id}", name="bo_demande_info_delete")
     * @Method("GET")
     */
    public function deleteGetInfoAction(Request $request, DemandeInfos $DemandeInfos)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($DemandeInfos);
            $em->flush();
            $this->AddFlash("success","Votre demande à bien été suprimée");
            return $this->redirectToRoute('backoffice_demande_index');
    }
 

    
    
 
    /**
     * Lists all DemandeEtablissement entities.
     *
     * @Route("/etablissement/{id}", name="backoffice_demande_etablissement_show")
     * @Method("GET")
     */
    public function showEtablissementBaAction(DemandeEtablissement $demandeEtablissement)
    {
        return $this->render('BackOffice/Demande/show.html.twig', array(
            'demandeEtablissement' => $demandeEtablissement));
    }
    
    
      
    public function EnvoiMailDemandeEtablissement($demandeEtablissement,$email)
    {
        $message = \Swift_Message::newInstance()
          ->setSubject("[ARSENE] - Demande de création d'établissement ")
          ->setFrom($this->getParameter('mail_from'))
          ->setTo($email)
          ->setBody($this->renderView(
                          'Email/demandeCreationEtablissement.html.twig',
                          array('demandeEtablissement' => $demandeEtablissement)
          ),
                          'text/html'
          );
          $this->get('mailer')->send($message);
    }
    
 
    
    
    
}
