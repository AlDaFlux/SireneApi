<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * Gestionnaire controller.
 *
 * @Route("/backoffice")
 */ 
class IndexController extends Controller
{
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/", name="pericles3_backoffice")
     * @Method("GET")
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) 
        {
            $em = $this->getDoctrine()->getManager();
            $creai=$this->GetUser()->GetCreai();
            
            $BiblioLiensMorts=null;
            if ($this->get('security.authorization_checker')->isGranted('ROLE_RW_BIBLIO_ARSENE')) 
            {
                $BiblioLiensMorts = $em->getRepository('Pericles3Bundle:BibliothequeAncreai')->findLienARefaire(10);
            }
            
            if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_COMPTA_EDIT'))
            {
                $gestionnaires_sans_facture = $em->getRepository('Pericles3Bundle:Gestionnaire')->findSansFacture();
                $etablissements_sans_facture = $em->getRepository('Pericles3Bundle:Etablissement')->findReelsSansFacture();
                $factures_a_echeances = $em->getRepository('Pericles3Bundle:Facture')->ProchainesFacturesAEcheances();
                $factures_non_finalisees = $em->getRepository('Pericles3Bundle:Facture')->findNonFinalisee();
                    
                $factures_a_payer_old = $em->getRepository('Pericles3Bundle:Facture')->findNonPayeeOld();
                $sommeAPayer = $em->getRepository('Pericles3Bundle:Facture')->findSommeNonPayee();
                $total_a_payer= $sommeAPayer['total'];
            }
            else
            {
                $gestionnaires_sans_facture = null;
                $etablissements_sans_facture = null;
                $factures_a_echeances=null;
                $factures_non_finalisees = null;
                $factures_a_payer_old=null;
                $total_a_payer=null;
            }
            
            
                
           
            $demandeInfosSansCreai = $em->getRepository('Pericles3Bundle:DemandeInfos')->findNonFiniSansCreai();
            if ($creai)
            {
                $LastCreatedEtablissements = $em->getRepository('Pericles3Bundle:Etablissement')->findLastCreatedCreai($creai,6);
                $LastUsers= $em->getRepository('Pericles3Bundle:User')->findLastCreatedCreai($creai,6);
                $LastConnectedUsers= $em->getRepository('Pericles3Bundle:User')->findLastConnectedCreai($creai,6);
            }
            else
            {
                $LastCreatedEtablissements = $em->getRepository('Pericles3Bundle:Etablissement')->findLastCreated(6);
                $LastUsers= $em->getRepository('Pericles3Bundle:User')->findLastCreated(6);
                $LastConnectedUsers= $em->getRepository('Pericles3Bundle:User')->findLastConnected(6);
            }

            $editos = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Editorial')->findByUser($this->getUser(),3);

            
            
            return $this->render('BackOffice/index.html.twig',
                    ['demandeInfosSansCreai'=>$demandeInfosSansCreai, 
                        'BiblioLiensMorts'=>$BiblioLiensMorts,
                        'LastCreatedEtablissements'=>$LastCreatedEtablissements,
                        'LastUsers'=>$LastUsers,
                        'LastConnectedUsers'=>$LastConnectedUsers, 
                        'gestionnaires_sans_facture' => $gestionnaires_sans_facture,
                        'etablissements_sans_facture' => $etablissements_sans_facture,
                        'factures_a_echeances' => $factures_a_echeances,
                        'factures_non_finalisees' => $factures_non_finalisees,
                        'factures_a_payer_old' => $factures_a_payer_old,
                        'sommeAPayer' => $total_a_payer,
                        'editos' => $editos,
                    ]);
        }
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE'))
        {
            $gestionnaire= $this->getUser()->GetGestionnaire();
             return $this->render('BackOffice/Gestionnaire/show.html.twig', array('gestionnaire' => $gestionnaire ));
        }
        else
        {
                return $this->redirectToRoute('backoffice_user_index');
        }
    } 
    
    
     /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/test_mail", name="pericles3_test_mail")
     * @Method("GET")
     */
    public function TestMailAction()
    {
        $message = \Swift_Message::newInstance()
          ->setSubject("[ARSENE] - 6549872131 TEST ")
          ->setFrom($this->getParameter('mail_from'))
          ->setTo("antoine.lotz@creai-aquitaine.org")
          ->setBody("CA MARCHE");
        
        $this->get('mailer')->send($message);
        $this->addFlash('success', "Un mail a été envoyé de ".$this->getParameter('mail_from') );
  
        return $this->redirectToRoute('pericles3_backoffice');

          
    }
    
   
}   


