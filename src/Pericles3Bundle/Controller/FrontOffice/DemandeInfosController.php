<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pericles3Bundle\Entity\DemandeInfos;
use Pericles3Bundle\Entity\ReferentielPublic;
use Pericles3Bundle\Entity\DemandeEtat;
use Pericles3Bundle\Form\DemandeInfosType;

/**
 * DemandeInfos controller.
 *
 * @Route("/demandeinfo")
 */
class DemandeInfosController extends Controller
{
  
    /**
     * Creates a new DemandeInfos entity.
     *
     * @Route("/", name="demande_info_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        return ($this->newFunction($request));
    } 

  /**
     * Finds and displays a DemandeInfos entity.
     *
     * @Route("/public_{id}", name="demande_info_new_public")
     * @Method({"GET", "POST"})
     */
    public function newPublicAction(Request $request,ReferentielPublic $ReferentielPublic)
    {
        return ($this->newFunction($request,$ReferentielPublic));
    }    
    
    
    
    
    private  function newFunction(Request $request, ReferentielPublic $public=null)
    {
        $demandeInfo = new DemandeInfos();
        
        $creai=$this->getParameter('activate.creai');
        
        
        $form = $this->createForm('Pericles3Bundle\Form\DemandeInfosType', $demandeInfo, ['creai'=>$creai]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($public) $demandeInfo->setPublic($public);

            $demandeInfo->setDateDemande(new \DateTime(date("Y-m-d H:i:s")));
            $demandeInfo->setEtat($em->getRepository('Pericles3Bundle:DemandeEtat')->findOneById(1));
            $em->persist($demandeInfo);
            $em->flush();
            $creai=$demandeInfo->getCreai();
            if ($creai)
            {
                $contact=$creai->getEmail();
                if ($contact)
                {
                    $this->EnvoiMailDemandeInformation($demandeInfo,$contact);
                    $this->addFlash('success', "mail to : ".$contact);
                }
            }
            $this->addFlash('success', "La demande d'information à bien été prise en compte");
            return $this->redirectToRoute('demande_info_show', array('id' => $demandeInfo->getId()));
        }

        return $this->render('Demande/Info/new.html.twig', array(
            'demandeInfo' => $demandeInfo,
            'form' => $form->createView(),
            'public' => $public,
        ));
    } 
    
     
    
    
        
    public function EnvoiMailDemandeInformation($demandeInfo,$email)
    {
              $message = \Swift_Message::newInstance()
                ->setSubject("[ARSENE] - Demande d'information ")
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($email)
                ->setBody($this->renderView('Email/demandeInfo.html.twig',array('demandeInfo' => $demandeInfo)
                ),
                                'text/html'
                );
               
                $this->get('mailer')->send($message);
    }
    
    /**
     * Finds and displays a DemandeInfos entity.
     *
     * @Route("/{id}", name="demande_info_show")
     * @Method("GET")
     */
    public function showAction(DemandeInfos $demandeInfo)
    {
        return $this->render('Demande/Info/show.html.twig', array(
            'demandeInfo' => $demandeInfo
        ));
    }
  
}
