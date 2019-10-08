<?php


namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Pericles3Bundle\Entity\User;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\EditorialCLU;

use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;

use Pericles3Bundle\Entity\DemandeInfos;
use Pericles3Bundle\Form\DemandeInfosType;



/**
 * Evaluation controller.
 *
 * @Route("/")
 */
class IndexController extends Controller
{
    
    /**
     * Index Evaluation
     *
     * @Route("/", name="pericles3_homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $result=$this->logUser();
        if ($result)  return($result);

        else return $this->render('Index/accueil_'.strtolower($this->getParameter('application_name')).'.html.twig');
    }
    
    
    
    /**
     * Finds ehpad
     *
     * @Route("/ehpad", name="ehpad_conditions")
     * @Method("GET")
     */
    public function ehpadAction()
    {
        $result=$this->logUser();
        if ($result)  return($result);
        else return $this->render('Index/accueil_ehpad.html.twig', array());
    }
     
    
    function logUser()
    {
        if ($this->getUser()) {   $editorials = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Editorial')->findByUser($this->getUser(),3); }
        else {return("");}
        
        $lastCGU= $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:EditorialCLU')->findLast();        
        
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') )
        {
                return $this->redirectToRoute('pericles3_backoffice');
        }
        else
        {
            if ($lastCGU != $this->getUser()->getLastCluChecked())
            {
                return $this->render('Index/conditions_generales.html.twig', ['lastCGU'=>$lastCGU]);
            }
            else
            {
                    if ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE'))
                    {

                        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_POLE'))
                        {
                            $Etablissements=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement')->findEtablissementParPole($this->getUser());
                        }
                        else 
                        {
                            $Etablissements=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:Etablissement')->findEtablissementParGestionnaire($this->getUser()->GetGestionnaire());
                        }
                        
                        return $this->render('Index/indexGestionnaire.html.twig',['etablissements' =>$Etablissements,"editorials"=>$editorials, 'etabsByRef'=> $this->etabsByRef($Etablissements)]);
                    }
                    elseif($this->getUser())
                    {
                            return $this->render('Index/index.html.twig',["editorials"=>$editorials]);
                    }        
            }
        }
    }
         
    
       
    function etabsByRef($etablissements)
    {
        foreach ($etablissements as $etablissement)
        {
            $etabsByRef[$etablissement->GetReferentielPublic()->GetID()][]=$etablissement;
        }
        return($etabsByRef);
    }
     

    /**
     * Finds and displays a User entity.
     *
     * @Route("/accepted/{id}", name="arsene_accepted_conditions")
     * @Method("GET")
     */
    public function acceptedAction(EditorialCLU $clu)
    {
        $em = $this->getDoctrine()->getManager();
        $this->getUser()->setLastCluChecked($clu);
        $em->persist($this->getUser());
        $this->addFlash('success', "Vous avez bien accepté les conditions générales d'utilisation ! ");
        $em->flush();
        return $this->redirectToRoute('pericles3_homepage');
    }
    
    
    
    
    
    /**
     * Index Evaluation
     *
     * @Route("/etablissement_{id}", name="pericles3_homepage_etablissement")
     * @Method("GET")
     */
    public function indexEtablissementAction(Etablissement $Etablissement )
    {
        return $this->render('Index/index.html.twig' ,['etablissement' =>$Etablissement]);
    }
    
    /**
     * Index Evaluation
     *
     * @Route("/mentions", name="pericles3_mentions")
     * @Method("GET")
     */
    public function MentionsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lastMention= $em->getRepository('Pericles3Bundle:EditorialMentionsLegales')->findLast();
        return $this->render('Index/mentions.html.twig', ['lastMention'=>$lastMention]);
    }
    
    

    
     
    
    /**
     * Index Evaluation
     *
     * @Route("/cgu", name="arsene_getlast_cgu")
     * @Method("GET")
     */
    public function CguAction()
    {
        $lastCGU= $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:EditorialCLU')->findLast();
         return $this->file(WEB_DIR.'/clu/'.$lastCGU->GetFichier());
    }

    
    
    

    
  
 
    /**
     * login
     *
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     */
    public function loginAction(Request $request)
	{
        $session = $request->getSession();

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; 
        }

        $lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);
        
        return $this->render('Login/login.html.twig', array('last_username' => $lastUsername, 'error' => $error));
	}
	      
 
    /**
     * login
     *
     * @Route("/login_check", name="login_check")
     * @Method({"GET", "POST"})
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }
	
    /**
     * logout
     *
     * @Route("/logout", name="login_logout")
     * @Method({"GET", "POST"})
     */	
    public function logoutAction()
    {
            throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
	
    
    
    /**
     * logout
     *
     * @Route("/maillogin/user_{id}", name="mail_login_user")
     * @Method({"GET", "POST"})
     */	
	public function mailloginAction(User $user)
	{
	
            if ( ! $user->getEmail())
            {
                $this->addFlash('error', "L'utilisateur n'a pas d'adresse Email");
            }
            else
            {
                $message = \Swift_Message::newInstance()
                ->setSubject('Votre compte ARSENE à été crée')
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                                'Email/logincreer.html.twig',
                                array('user' => $user)
                ),
                                'text/html'
                );
                if ($this->get('mailer')->send($message))
                {
                    $this->addFlash('success', 'Un Email vous a été envoyé. Veuillez vérifierz votre boite mail ainsi que la boite SPAM.');
                }
                else
                {
                    $this->addFlash('error', "Une erreur est survenue lors de l'envois du mail");
                }
            }	
            return $this->redirectToRoute('backoffice_user_show', array('id' => $user->getId()));
	}
        
        
    
    
    
    /**
     * logout
     *
     * @Route("/motdepasseoublie", name="login_motdepasseoublie")
     * @Method({"GET", "POST"})
     */	
	public function motDePasseOublieAction(Request $request)
	{
		if ($request->getMethod() == 'POST') {
			$email = $request->get('inputEmail');
			
			$emailConstraint = new EmailConstraint();
    		$emailConstraint->message = 'Veuillez saisir un Email valide';
    		
    		$notBlankConstraint = new NotBlankConstraint();
    		$notBlankConstraint->message = 'Veuillez saisir un Email';
    		
    		$errors = $this->get('validator')->validate($email, array($notBlankConstraint, $emailConstraint));
			if (count($errors) > 0) {
				foreach ($errors as $error)
					$this->addFlash('error', $error->getMessage());
			} else {
				$repositoryUser = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User');
				$userMail = $repositoryUser->findOneByEmail($email);
				if (count($userMail) == 0) {
					$this->addFlash('error', 'Cet Email est inconnu');
				} else {
					$userMail->setMotDePasseOublie(time());
					$em = $this->getDoctrine()->getManager();
					$em->persist($userMail);
					$em->flush();
					
					$message = \Swift_Message::newInstance()
					->setSubject('Réinitialisation mot de passe')
					->setFrom($this->getParameter('mail_from'))
					->setTo($email)
					->setBody($this->renderView(
							'Email/motdepasseoublie.html.twig',
							array('user' => $userMail->getId(), 'code' => $userMail->getMotDePasseOublie())
					),
							'text/html'
					);
					if ($this->get('mailer')->send($message))
                                        {
                                            $this->addFlash('success', 'Un Email vous a été envoyé. Veuillez vérifierz votre boite mail ainsi que la boite SPAM.');
                                            
                                        }
                                        else
                                        {
                                            $this->addFlash('error', "Une erreur est survenue lors de l'envois du mail");
                                        }
                                        return $this->redirectToRoute('pericles3_homepage');
                                        
				}	
			}
		}

		return $this->render('Login/motdepasseoublie.html.twig');
	}
        
        
    /**
     * logout
     *
     * @Route("/resetpassword/{user}/{code}", name="login_resetpassword")
     * @Method({"GET", "POST"})
     */	
	public function resetPasswordAction($user, $code)
	{
		$repositoryUser = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User');
		$userReset = $repositoryUser->findOneBy(array('id' => $user, 'motDePasseOublie' => $code));
		if (count($userReset) == 0) {
			$this->addFlash('error', 'Cette demande de réinitialisation de mot de passe n\'est plus d\'actualité, veuillez en refaire une');
			return $this->render('Login/resetpassword.html.twig', array('user' => null, 'code' => null));
		}
		return $this->render('Login/resetpassword.html.twig', array('user' => $user, 'code' => $code));
	}
        
        
    
    /**
     * logout
     *
     * @Route("/resetpassword", name="login_updatepassword")
     * @Method({"GET", "POST"})
     */	
        public function updatePasswordAction(Request $request)
	{
		if ($request->getMethod() == 'POST') {
			$passwordFirst = $request->get('inputPasswordFirst');
			$passwordSecond = $request->get('inputPasswordSecond');
			$code = $request->get('inputCode');
			$idUser = $request->get('inputUser');
			if ($passwordFirst === $passwordSecond) {	
				if ($passwordFirst == '') {
					$this->addFlash('error', 'Veuillez saisir un nouveau mot de passe');
					return $this->render('Login/resetpassword.html.twig', array('user' => $idUser, 'code' => $code));
				} else {
					$repositoryUser = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User');
					$user = $repositoryUser->findOneBy(array('id' => $idUser, 'motDePasseOublie' => $code));
					if (count($user) > 0) {
						$encoder = $this->container->get("security.encoder_factory")->getEncoder($user);
						$user->setPassword($encoder->encodePassword($passwordFirst, $user->getSalt()));
						$user->setMotDePasseOublie(null);
						$user->eraseCredentials();
						$em = $this->getDoctrine()->getManager();
						$em->persist($user);
						$em->flush();
						$this->addFlash('success', 'Votre mot de passe a été mis à jour');
					} else {
						$this->addFlash('error', 'Une erreur est survenue lors de la réinitialisation de votre mot de passe, veuillez refaire une demande');
                                        }
                                        return $this->redirectToRoute('pericles3_homepage');
				}
			} else {
				$this->addFlash('error', 'Vos mots de passe ne correspondent pas');
				return $this->render('Login/resetpassword.html.twig', array('user' => $idUser, 'code' => $code));
			}
			
		}
		return $this->render('Login/resetpassword.html.twig', array('user' => null, 'code' => null));
	}
    
    
    
    
   
    
       

    /**
     * Finds and displays a User entity.
     *
     * @Route("/user_profile", name="arsene_user_profile")
     * @Method("GET")
     */
    public function showProfileAction()
    {
        return $this->render('User/show.html.twig', array(
            'user' => $this->getUser()
        ));
    }
    

           

    /**
     * Finds and displays a User entity.
     *
     * @Route("/genre_note", name="arsene_genere_note")
     * @Method("GET")
     */
    public function genereNotesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $etab=$this->GetUser()->GetEtablissement();
        if ($etab)
        {
            if ($etab->GetCategory()->GetId()==5)
            {
                foreach ($etab->GetDomaines() as $domaine)
                {
                    foreach ($domaine->GetDimensions() as $dimension)
                    {
                        foreach ($dimension->GetCriteres() as $critere)
                        {
                            $new_note=rand(1,10);
                            $critere->SetNote($new_note);
                            $em->persist($critere);
                            $em->flush();
                            foreach ($critere->GetQuestions() as $Question)
                            {
                                $Question->SetReponse(rand(0,1));
                                $em->persist($Question);
                                $em->flush();
                            }
                        }
                    }
                }
                $this->addFlash('success', "Des notes aléatoires ont été crées");
            }
            else
            {
                $this->addFlash('error', "Impossible");
            }
        }
        else
        {
            $this->addFlash('error', "Impossible");
        }
        return $this->redirectToRoute('pericles3_backoffice');
    }
    
     
    
    
    
    

    
    
    
    
    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/user_edit_password", name="front_office_user_edit_password")
     * @Method({"GET", "POST"})
     */
    public function editPasswordAction(Request $request)
    {
        $user=$this->GetUser();
        if ($request)
        {
            if ($this->editPassord($request, $user))
            {
                return $this->redirectToRoute('arsene_user_profile', array('id' => $user->getId()));
            }
            else 
            {
                return $this->render('User/edit_password.html.twig', array('user' => $user));
            }
        }
    }

    
    function editPassord(Request $request, User $user)
    {
         //doublon avec BackOffice
        if (! ($request->getMethod() == 'POST'))    
        {
            return(false);
        }
        elseif (! $request->get('new_password1'))
        {
            $this->addFlash('error', "Le mot de passe est vide");
            return(false);
        }
        elseif ($request->get('new_password1')!= $request->get('new_password2'))
        {
            $this->addFlash('error', "Les mots de passes ne sont pas identiques");
            return(false);
        }
        else
        {
            $encoder = $this->container->get("security.encoder_factory")->getEncoder($user);
            $this->addFlash('success', "le mot de passe à été changé avec succès ! ");
            $user->setPassword($encoder->encodePassword($request->get('new_password1'), $user->getSalt()));
            $user->setChangedPassword(true);
            $user->setMotDePasseOublie(null);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return(true);
        }
    }
    
    
  
    
    
    
} 