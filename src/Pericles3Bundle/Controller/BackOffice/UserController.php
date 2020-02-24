<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pericles3Bundle\Entity\User;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\Gestionnaire;
use Pericles3Bundle\Entity\ProfilesRoles;
use Pericles3Bundle\Entity\DemandeGestionnaire;

use Pericles3Bundle\Form\UserType;

use Symfony\Component\Form\FormError;
 

/**
 * User controller.
 *
 * @Route("/backoffice/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="backoffice_user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $creai=$this->GetUser()->GetCreai();

            if ($creai)
            {
                $LastUsers= $em->getRepository('Pericles3Bundle:User')->findLastCreatedCreai($creai,16);
                $LastConnectedUsers= $em->getRepository('Pericles3Bundle:User')->findLastConnectedCreai($creai,16);
                $CTs= $em->getRepository('Pericles3Bundle:User')->ListCTByCreai($creai);
            }
            else
            {
                $LastUsers= $em->getRepository('Pericles3Bundle:User')->findLastCreated(16);
                $LastConnectedUsers= $em->getRepository('Pericles3Bundle:User')->findLastConnected(16);
                $CTs=null;
            }
            return $this->render('BackOffice/user/accueil.html.twig',['CTs'=>$CTs,'LastUsers'=>$LastUsers,'LastConnectedUsers'=>$LastConnectedUsers,"creai"=>$creai]);
        }
        else
        {
            return $this->render('BackOffice/user/index.html.twig', array('users' => self::GetUsers()));
        }
        

    }
    
    
      

    /**
     * Finds and displays a User entity.
     *
     * @Route("/user_profile", name="backoffice_arsene_user_profile")
     * @Method("GET")
     */
    public function showProfileAction()
    {
        return $this->render('BackOffice/user/show.html.twig', array(
            'user' => $this->getUser()
        ));
    }
    
    
    
    

    
    
    
        /**
     * Lists all User entities.
     *
     * @Route("/all", name="backoffice_user_all_index")
     * @Method("GET")
     */
    public function indexAllAction()
    {
        return $this->render('BackOffice/user/index.html.twig', array(
            'users' => self::GetUsers()
        ));
    }
    
    
    
    
    

    /**
     * Recherche
     *
     * @Route("/search", name="pericles3_user_search")
     * @Method({"GET", "POST"})
    */
    public function SearchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $occurence=$request->get('occurence');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            if ($this->getUser()->getAllEtablissement())
            {
                $creai=null;
            }
            else
            {
                $creai=$this->getUser()->GetCreai();
            }
        }
                
        $users=$em->getRepository('Pericles3Bundle:User')->FindByOccurence($occurence,$creai);
        return $this->render('BackOffice/user/search.html.twig', ['occurence'=>$occurence, 'users'=>$users ]);
    }
    


    
    
    /**
     * Lists all User entities.
     *
     * @Route("/listby/{type}", name="backoffice_user_list_bytype")
     * @Method("GET")
     */
    public function listByTypeAction($type)
    {
        
        return $this->render('BackOffice/user/index.html.twig', array(
            'users' => self::GetUsers($type)
        ));
    }
    
    
    
    

    /*
        private function GetUsers()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUsers = $em->getRepository('Pericles3Bundle:User');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            $users = $repositoryUsers->findAll();
        }
        
        */
    
    private function GetUsers($type=null)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUsers = $em->getRepository('Pericles3Bundle:User');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))        
        {
            if ($this->getUser()->getAllEtablissement())
            {
                if ($type) $users = $repositoryUsers->FindByType($type);
                else $users = $repositoryUsers->findAll();
            }
            else
            {
                if ($type) $users = $repositoryUsers->FindByCreaiType($this->getUser()->getCreai(),$type);
                else $users = $repositoryUsers->FindByCreai($this->getUser()->getCreai());
            }
        }
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE'))        
        {
            $gestionnaire=$this->getUser()->getGestionnaire();
            if ($type)  $users =  $repositoryUsers->FindByGestionnaireType($gestionnaire,$type);
            else $users = $repositoryUsers->FindByGestionnaireAll($this->getUser());
        }
        else
        {
            $users = $repositoryUsers->findBy(array("etablissement" => $this->GetUser()->getEtablissement()), array('username' => 'ASC'));
        }
        return($users);
    }


    
       public function AddUser(Request $request,$role_principal,$parent=null,$profil=null)
       {
                if ($role_principal =="ROLE_GESTIONNAIRE")
                {
          //          $options= array('edit_password' => true,'niveau' => 'gestionnaire');
                    $titre="Gestionnaire : ".$parent;
                }
                elseif ($role_principal =="ROLE_USER")
                {
        //            $options= array('edit_password' => true,'niveau' => 'etablissement');
                    $titre="Etablissement : ".$parent;
                }
                elseif ($role_principal =="ROLE_SUPER_ADMIN")
                {
                    $titre="Administrateur APPLICATION";
                }
                if ($profil) $options= array('new'=>true, 'edit_password' => true,'niveau' => $role_principal, 'show_roles'=>false);
                else  $options= array('new'=>true, 'edit_password' => true,'niveau' => $role_principal);
                
                
                $user = new User();
                $user->setPassword($this->get('saksimple')->RandomString());
                $user->setDesactive(0);
                $form = $this->createForm('Pericles3Bundle\Form\UserType', $user,$options);

                $em=$this->getDoctrine()->getManager();

                $form->handleRequest($request);
                $email=$form->getData()->GetEmail();

                if ($em->getRepository('Pericles3Bundle:User')->findOneByUsername($form->getData()->GetUsername()))
                {
                    $this->addFlash('error', "L'utilisateur <i>".$form->getData()->GetUsername()."</i> existe déja");
                }
                elseif ($em->getRepository('Pericles3Bundle:User')->nbParMail($email))
                {
                    $this->addFlash('error', "L'utilisateur <i>".$email."</i> à déja un compte ARSENE !  ");
                }
                else
                {
                    if ($form->isSubmitted() && $form->isValid()) {

                        $password=$form->getData()->GetPassword();
                           if ($password != '') {
                                    $encoder = $this->container->get("security.encoder_factory")->getEncoder($user);
                                            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
                            }
                        $user->setFirstPassword($password);
                        $user->setChangedPassword(false);

                        if ($role_principal =="ROLE_GESTIONNAIRE")
                        {
                            $user->setGestionnaire($parent);
                        }
                        elseif ($role_principal =="ROLE_USER")
                        {
                            $user->setEtablissement($parent);
                        }

                        if ($profil)
                        {
                            foreach ($profil->GetRoles() as $role) 
                            {
                                $roles[]=$role;
                            }
                        }
                        else
                        {
                            $roles=$form->getData()->GetRoles();
                        }
                        $roles[]=$role_principal;
                        $user->setRoles($roles);
                        

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($user);
                        $em->flush();
                        $this->addFlash('success', "L'utilisateur a été ajouté avec succès");

        //                return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $gestionnaire->getId()));

                        return $this->redirectToRoute('backoffice_user_show', array('id' => $user->getId()));
                    }
                } 
                return $this->render('BackOffice/user/new.html.twig', array(
                    'titre' => $titre,
                    'user' => $user,
                    'profil' => $profil,
                    'form' => $form->createView(),
                ));


       } 
    
    
    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="backoffice_user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if ($this->getUser()->getEtablissement() && $this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            return($this->addUserEtablissementAction($request,$this->getUser()->getEtablissement()));
        }
        return($this->AddUser($request,'ROLE_SUPER_ADMIN'));
    }
    
    
    
    
    /**
     * Ajoute un utilisateur à un établissement.
     *
     * @Route("/etablissement_{id}/new", name="backoffice_etablissement_add_user")
     * @Method({"GET", "POST"})
     */
    public function addUserEtablissementAction(Request $request, Etablissement $etablissement)
    { 
        return ($this->AddUser($request,'ROLE_USER',$etablissement));
    }
    
    
    /**
     * Ajoute un utilisateur à un établissement.
     *
     * @Route("/etablissement_{id}/new_fromdemande", name="backoffice_etablissement_add_user_fromdemande")
     * @Method({"GET", "POST"})
     */
    public function addUserFromdemandeEtablissementAction(Request $request, Etablissement $etablissement)
    { 
        
        $user = new User();
        $user->setDesactive(0);
        $password=$this->get('saksimple')->RandomString();
        $encoder = $this->container->get("security.encoder_factory")->getEncoder($user);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
        $username= strtolower($etablissement->GetDemande()->GetDemandeurPrenom().".".$etablissement->GetDemande()->GetDemandeurNom());
        $usernamebase=$username;
        $suff=1;
        
        while ($this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User')->findOneByUsername($username))
        {
            $username=$usernamebase.$suff;
            $suff++;
        }

        $user->setUsername($username);
        $user->setEmail($etablissement->GetDemande()->GetEmail());
        
         $user->setEtablissement($etablissement);
        $user->setFirstPassword($password);
        $user->setChangedPassword(false);
        $this->addFlash('success', "L'utilisateur a été ajouté avec succès");
 
        $em = $this->getDoctrine()->getManager();

        $ProfilesRoles=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ProfilesRoles')->findOneById(1);
        foreach ($ProfilesRoles->GetRoles() as $role) 
        {   
            $roles[]=$role;
        }
        $roles[]="ROLE_USER";
        $user->setRoles($roles);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('backoffice_user_show', array('id' => $user->getId()));
                        
//        return ($this->AddUser($request,'ROLE_USER',$etablissement));
    }
    
    
    
    /**
     * Ajoute un utilisateur à un établissement.
     *
     * @Route("/gestionnaire_{id}/new_fromdemande", name="backoffice_gestionnaire_add_user_fromdemande")
     * @Method({"GET", "POST"})
     */
    public function addUserFromdemandeGestionnaireAction(DemandeGestionnaire $DemandeGestionnaire)
    { 
        $user = new User();
        $user->setDesactive(0);
        $password=$this->get('saksimple')->RandomString();
        $encoder = $this->container->get("security.encoder_factory")->getEncoder($user);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
        $username= strtolower($DemandeGestionnaire);
        $usernamebase=$username;
        $suff=1;
        
        while ($this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User')->findOneByUsername($username))
        {
            $username=$usernamebase.$suff;
            $suff++;
        }

        $user->setUsername($username);
        $user->setEmail($DemandeGestionnaire->GetEmail());
        
        $user->setGestionnaire($DemandeGestionnaire->getGestionnaire());
        $user->setFirstPassword($password);
        $user->setChangedPassword(false);
        $this->addFlash('success', "L'utilisateur a été ajouté avec succès ");
        $this->addFlash('success', "Mot de passe : ".$password);
        $em = $this->getDoctrine()->getManager();
        $ProfilesRoles=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ProfilesRoles')->findOneById(4);
        foreach ($ProfilesRoles->GetRoles() as $role) 
        {   
            $roles[]=$role;
        }
        $roles[]="ROLE_GESTIONNAIRE";
        $user->setRoles($roles);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $DemandeGestionnaire->getGestionnaire()->getId()));
                        
    }
    
    

        
    /**
     * Ajoute un utilisateur à un établissement.
     *
     * @Route("/etablissement_{id}/new/profil_{id_ProfilesRoles}", name="backoffice_etablissement_add_user_profil")
     * @Method({"GET", "POST"})
     */
    public function addUserEtablissementProfilAction(Request $request, Etablissement $etablissement,$id_ProfilesRoles)
    { 
        $ProfilesRoles=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ProfilesRoles')->findOneById($id_ProfilesRoles);
        return ($this->AddUser($request,'ROLE_USER',$etablissement,$ProfilesRoles));
        
    }
    
    
    
    /**
     * choix des profils pour un nouvel utilisateur.
     *
     * @Route("/etablissement_{id}/new_choiceprofil", name="backoffice_etablissement_add_user_choiceprofile")
     * @Method({"GET", "POST"})
     */
    public function addUserEtablissementChoixProfilAction(Etablissement $etablissement)
    { 
        $roles=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ProfilesRoles')->findRolesEtablissement();
          return $this->render('BackOffice/user/new_user_profiles.html.twig', array(
            'etablissement' => $etablissement, 
            'roles' => $roles, 
        ));
    }
    
        
    /**
     * choix des profils pour un nouvel utilisateur.
     *
     * @Route("/new_choiceprofil", name="backoffice_etablissement_add_user_etablissement_choiceprofile")
     * @Method({"GET", "POST"})
     */
    public function EtablissementaddUserEtablissementChoixProfilAction()
    { 
        $roles=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ProfilesRoles')->findRolesEtablissement();
          return $this->render('BackOffice/user/new_user_profiles.html.twig', array(
            'etablissement' =>  $this->GetUser()->getEtablissement(), 
            'roles' => $roles, 
        ));
    }
    
    
  
    
  
    
    
    
    
    
    
    
    /**
     * Ajoute un ulisateur à l'établissement
     *
     * @Route("/getionnaire_{id}/users/new", name="backoffice_gestionnaire_add_user")
     * @Method({"GET", "POST"})
     */
    public function addUserGestionnaireAction(Request $request, Gestionnaire $gestionnaire)
    { 
        return($this->AddUser($request,'ROLE_GESTIONNAIRE',$gestionnaire));
        if ($result) return($result);
    }
    
         
    /**
     * choix des profils pour un nouvel utilisateur.
     *
     * @Route("/getionnaire_{id}/new_choiceprofil", name="backoffice_gestionnaire_add_user_choiceprofile")
     * @Method({"GET", "POST"})
     */
    public function addUserGestionnaireChoixProfilAction(Gestionnaire $Gestionnaire)
    { 
        $roles=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ProfilesRoles')->findRolesGestionnaire();
          return $this->render('BackOffice/user/new_user_profiles.html.twig', array(
            'gestionnaire' => $Gestionnaire, 
            'roles' => $roles, 
        ));
    }
    
            
    /**
     * Ajoute un utilisateur à un établissement.
     *
     * @Route("/gestionnaire_{id}/new/profil_{id_ProfilesRoles}", name="backoffice_gestionnaire_add_user_profil")
     * @Method({"GET", "POST"})
     */
    public function addUserGestionnaireProfilAction(Request $request, Gestionnaire $Gestionnaire,$id_ProfilesRoles)
    { 
        $ProfilesRoles=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:ProfilesRoles')->findOneById($id_ProfilesRoles);
        return ($this->AddUser($request,'ROLE_GESTIONNAIRE',$Gestionnaire,$ProfilesRoles));
    }
    

     /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/view_roles", name="pericles3_backoffice_roles")
     * @Method("GET")
     */
    public function indexRolesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('Pericles3Bundle:User')->findAll();
        $tous_roles = array();
        foreach ($users as $user)
        {
            foreach ($user->getRoles() as $role)
            {
                if (! (in_array($role, $tous_roles)))
                {
                    $tous_roles[]=$role;
                }
            }
        }
        
        return $this->render('BackOffice/user/roles.html.twig', array('roles' => $tous_roles ));
    } 
        
    
     /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/view_roles/{role_rechercher}", name="pericles3_backoffice_role_user")
     * @Method("GET")
     */
    public function indexRoleUsersAction($role_rechercher)
    {
        $users_result=  new \Doctrine\Common\Collections\ArrayCollection();

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('Pericles3Bundle:User')->findAll();
        foreach ($users as $user)
        {
            if (in_array($role_rechercher, $user->GetRoles()))  $users_result->Add($user);
        }
        
        return $this->render('BackOffice/user/role_users.html.twig',array("users" => $users_result,"show_affected"=>true));
    } 
        
    
    

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="backoffice_user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        return $this->render('BackOffice/user/show.html.twig', array(
            'user' => $user
             
        ));
    }

    

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/saisies", name="backoffice_user_show_saisies")
     * @Method("GET")
     */
    public function showSaisiesAction(User $user)
    {
        return $this->render('BackOffice/user/show_saisies.html.twig', array(
            'user' => $user
        ));
    }

    
    
    
    
    
    
    
    
    /*
    private function UserEmailExiste()
    {
            if (count($this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User')->findOneByUsername($form->getData()->GetUsername())) > 0)
                {
                    
                }
    }  
    
    private function UserLoginUsername($UserName,$CurrentUser=NULL)
    {
        $user_base=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User')->findOneByUsername($UserName);
        if ($user_base)
        {
            if ($user_base->GetUsername()==$CurrentUser->GetUsername())
            {
                
            }
        }
            && !$CurrentUser) return(true)
        elseif ($user_base) {
        }
        else return (true);
    }
    */
     
    private function UserByLoginUsername($UserName)
    {
       $user_base=$this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User')->findOneByUsername($UserName);
       return($user_base);
    }
    
    
    public function editUser(Request $request, User $user)
    {
        if (! $this->GetUser()->GetCanModify($user)) throw $this->createAccessDeniedException("Vous n'avez pas les droits de modification pour cet utilisateur");

;
        
        $em = $this->getDoctrine()->getManager();
        $role_principal=$user->getRolePrincipal();

        if ($role_principal =="ROLE_GESTIONNAIRE")
        {
            $options= array('niveau' => 'gestionnaire');
            $titre="Gestionnaire : ".$user->getGestionnaire();
        }
        elseif ($role_principal =="ROLE_USER")
        {
            $titre="Etablissement  : ".$user->getEtablissement();
        }
        elseif ($role_principal =="ROLE_SUPER_ADMIN")
        {
            $titre="Administrateur APPLICATION";
        }
        
        $options= array('edit_password' => false,'niveau' => $role_principal,"iammegaadmin" => $this->GetUser()->getIsMegaAdmin());
        
        $ancien_username=$user->GetUserName();
        $ancien_email=$user->getEmail();
        $editForm = $this->createForm('Pericles3Bundle\Form\UserType', $user,$options);
         
        
        
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
                    
            $roles=$editForm->getData()->GetRoles();
            $roles[]=$role_principal;
            $user->setRoles($roles);

            
            
            
//            $poles=$editForm->getData()->GetEtablissementsPole();
            
            
            
            
        
            if ($ancien_username!=$editForm->getData()->GetUserName())
            {
               if ($this->UserByLoginUsername($editForm->getData()->GetUserName()))
               {
                    $editForm->get('username')->addError(new FormError("ce nom d'utilisateur est déja pris  !"));
                       return $this->render('BackOffice/user/edit.html.twig', array('user' => $user,'edit_form' => $editForm->createView(),'titre' => $titre));
                        
               }
            } 
            if ($ancien_email!=$editForm->getData()->GetEmail())
            {
                if($em->getRepository('Pericles3Bundle:User')->nbParMail($editForm->getData()->GetEmail()))
               {
                    $editForm->get('email')->addError(new FormError("cet email est déja utilisé dans ARSENE !"));
                    return $this->render('BackOffice/user/edit.html.twig', array('user' => $user,'edit_form' => $editForm->createView(),'titre' => $titre));
               }
            }
            if (false)
            {
                $editForm->get('password')->addError(new FormError('Pas bon'));
                   return $this->render('BackOffice/user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'titre' => $titre
        ));
            }
            else
            {
                $em->persist($user);
                $em->flush();   
                return $this->redirectToRoute('backoffice_user_show', array('id' => $user->getId()));
            }
        }

      
        return $this->render('BackOffice/user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView()  ,
            'titre' => $titre
        ));
    }
    
    
 
    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="backoffice_user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
         return($this->editUser($request,$user));
    }

    
    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/uptogestionnaire", name="backoffice_user_uptogestionnaire")
     * @Method({"GET", "POST"})
     */
    public function UpToGerstionnaireAction(User $user)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $roles[]="ROLE_GESTIONNAIRE";
        $roles[]="ROLE_ADMIN_POLE";
        foreach ($user->GetRoles() as $role)
        {
            if ($role<>"ROLE_USER") $roles[]=$role;
        }
        $user->setRoles($roles);
        
        $em->persist($user);
        $em->flush();
        
        

        $etab=$user->getEtablissement();
        $user->setGestionnaire($etab->GetGestionnaire());
        $etab->GetGestionnaire()->AddUser($user);

        $etab->removeUser($user);
        $user->setEtablissement(null);
        
        $user->addEtablissementsPole($etab);
        $etab->addUserPole($user);
        
        $em->persist($user);
        $em->persist($etab);
        $em->flush();
        
        
       
        

        return $this->redirectToRoute('backoffice_user_edit_pole_etablissement', array('id' => $user->getId()));
         
    }

    
    
    
    
    
    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit_pole_etablissement", name="backoffice_user_edit_pole_etablissement")
     * @Method({"GET", "POST"})
     */
    public function editPoleEtablissementAction(Request $request, User $user)
    {
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_GESTIONNAIRE') && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
           $id_gestionnaire=$this->GetUser()->GetGestionnaire()->GetId();
        }
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') && $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN_UTILISATEUR'))
        {
            $id_gestionnaire=$user->getGestionnaire()->GetId();
        }
        else
        {
            throw $this->createAccessDeniedException("Vous n'avez pas les droits de modifier les établissment de cet utilsateur");
        }
                
        $editForm = $this->createForm('Pericles3Bundle\Form\UserType', $user,['id_gestionnaire'=> $id_gestionnaire]);
        

        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {

         /* 
//            $user->Re
            $poles=$editForm->getData()->GetEtablissementsPole();
            foreach ($poles as $pole)
            {
                $user->addEtablissementsPole($pole);
                $this->addFlash('error', "Pole : ".$pole);
            }
            
                       
  //                      $roles[]=$role_principal;
    //                    $user->setRoles($roles);
            
          * */
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur a bien été mis à jour");
            return $this->redirectToRoute('backoffice_user_show', array('id' => $user->getId()));

        }

        return $this->render('BackOffice/user/edit.html.twig', array(
            'titre' => "Pole Gestionnaire : Utilisateur ".$user." - Modification des établissements",
            'user' => $user,
            'edit_form' => $editForm->createView()
        ));
    }

    
    
    
    
    
    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit_password", name="backoffice_user_edit_password")
     * @Method({"GET", "POST"})
     */
    public function editPasswordAction(Request $request, User $user)
    {
        if ($request)
        {
            if ($this->editPassord($request, $user))
            {
                return $this->redirectToRoute('backoffice_user_show', array('id' => $user->getId()));
            }
            else 
            {
                return $this->render('BackOffice/user/edit_password.html.twig', array('user' => $user));
            }
        }
    }

    
    
    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/edit/password", name="backoffice_user_edit_his_password")
     * @Method({"GET", "POST"})
     */
    public function editMyPasswordAction(Request $request)
    {
        $user=$this->GetUser();
        if ($request)
        {
            if ($this->editPassord($request, $user))
            {
                return $this->redirectToRoute('backoffice_arsene_user_profile');
            }
            else 
            {
                return $this->render('BackOffice/user/edit_password.html.twig', array('user' => $user));
            }
        }
    }

    
    
    
    function editPassord(Request $request, User $user)
    {
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
    
    
    
    function deleteUser(User $user)
    {
         $em = $this->getDoctrine()->getManager();
            $user->SetEmail(null);
            $user->SetUsername($user->getUsername(). (" - suprimmé le : ". date("d/m/Y h:i:s")));
            $em->persist($user);
            $em->flush();
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur a bien été supprimé");
    }
     
      /**
     * Deletes a User entity.
     *
     * @Route("/delete_url/{id}", name="backoffice_user_delete_url")
     * @Method({"GET", "POST"})
     */
    public function deleteURLAction(User $user)
    {
        
        if ($user->IsAnEtablissement())
        {
            $id_etab=$user->getEtablissement()->GetId();
            $this->deleteUser($user);
            return $this->redirectToRoute('backoffice_etablissement_view', array('id' => $id_etab));
        }
        elseif ($user->isGestionnaire())
        {
            $id_gest=$user->getGestionnaire()->GetId();
            $this->deleteUser($user);
            return $this->redirectToRoute('backoffice_gestionnaire_show', array('id' => $id_gest));
        }
        else
        {
            $this->deleteUser($user);
            return $this->redirectToRoute('backoffice_user_index');
        }
    }
    
    
    
    
      /**
     * Deletes a User entity.
     *
     * @Route("/delete_url/{id}/json", name="backoffice_user_delete_url_json")
     * @Method({"GET", "POST"})
     */
    public function deleteURLJsonAction(User $user)
    {
        $this->deleteUser($user);
        return new JsonResponse(true);
    }
    
    
    
    
    
    
    
    
    
    /**
     * Deletes a User entity.
     *
     * @Route("/list/etablissement_{id}", name="backoffice_users_from_etablissent")
     * @Method({"GET", "POST"})
     */
    public function GetFromEtablissementAction(Etablissement $Etablissement)
    {
        return $this->render('BackOffice/user/list.html.twig',array("users" => $Etablissement->GetUsers()));
    }
    
    
    /**
     * Deletes a User entity.
     *
     * @Route("/list/etablissement_{id}/pole", name="backoffice_users_from_etablissent_pole")
     * @Method({"GET", "POST"})
     */
    public function GetFromEtablissementPoleAction(Etablissement $Etablissement)
    {
        return $this->render('BackOffice/user/list.html.twig',array("users" => $Etablissement->getUserPole()));
    }
    
    
    
    
    /**
     * 
     *
     * @Route("/list/gestionnaire_{id}/etablissements", name="backoffice_users_etablissements_from_gestionnaire")
     * @Method({"GET", "POST"})
     */
    public function GetFromGestionnaireEtablissementAction(Gestionnaire $Gestionnaire)
    {
        return $this->render('BackOffice/user/list.html.twig',array("users" => $Gestionnaire->getUsersEtablissements()));
    }
    
    /**
     * 
     *
     * @Route("/list/creai_{id}", name="backoffice_users_creai")
     * @Method({"GET", "POST"})
     */
    public function GetFromCreaiAction(\Pericles3Bundle\Entity\Creai $creai)
    {
        return $this->render('BackOffice/user/list.html.twig',array("users" => $creai->getUsers()));
    }
    
    
    
    
    
    
    /**
     * Deletes a User entity.
     *
     * @Route("/list/gestionnaire_{id}", name="backoffice_users_from_gestionnaire")
     * @Method({"GET", "POST"})
     */
    public function GetFromGestionnaireAction(Gestionnaire $Gestionnaire)
    {
        return $this->render('BackOffice/user/list.html.twig',array("users" => $Gestionnaire->GetUsers()));
    }
    
    
 
    
     
    public function listAction($gestionnaire="",$etablissement="")
    { 
        $repositoryUsers = $this->getDoctrine()->getManager()->getRepository('Pericles3Bundle:User');
        if ($gestionnaire) 
        {
            $users = $repositoryUsers->findBy(array("gestionnaire" => $gestionnaire));
        }
        elseif ($etablissement and false) 
        {
            $users = $repositoryUsers->findBy(array("etablissement" => $etablissement));
        }
        else
        {
            $users=self::GetUsers();
        }
        return $this->render('BackOffice/user/list.html.twig',array("users" => $users));
    }
    

    
     
        
    
}
