<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;


use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;  
use Gedmo\Blameable\Traits\BlameableEntity;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\UserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User implements UserInterface
{
    
    
    use SoftDeleteableEntity;
    use BlameableEntity;
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="first_password", type="string", length=255)
     */
    private $first_password;

    
    /**
     * @var boolean
     * @ORM\Column(name="changed_password",type="boolean", options={"default":false})
     */
    protected $ChangedPassword;
    
    
    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;
    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement",inversedBy="users")
     */
    private $etablissement;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Gestionnaire",inversedBy="users")
     */
    private $gestionnaire;
        
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_last_connect", type="datetime", nullable=true)
     */
    private $dateLastConnect;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="temps_connexion_global", type="time")
     */
    private $tempsConnexionGlobal;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mot_de_passe_oublie", type="string", length=255, nullable=true)
     */
    private $motDePasseOublie;

    
        
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Creai", inversedBy="users")
     */
    private $creai;

    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", mappedBy="CreatedBy")
     */
    private $etablissements_cree;

    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Gestionnaire", mappedBy="CreatedBy")
     */
    private $gestionnaires_cree;

    
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="users", cascade={"remove"})
     */
    private $ReferentielsPublic;

    
        
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="desactive", type="integer")
     */
    private $desactive;

                 
                 
    
        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Bibliotheque", mappedBy="user")
     */
    private $bibliotheques;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\CommentaireDomaine",mappedBy="user")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Constat",mappedBy="user")
     */
    private $constats;


    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DomaineObjectifStrategique",mappedBy="user")
     */
    private $objectifs_srategiques;



    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\ObjectifOperationnel",mappedBy="user")
     */
    private $objectifsOperationnel;

    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Preuve",mappedBy="user")
     */
    private $preuves;

        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Sauvegarde",mappedBy="user")
     */
    private $sauvegardes;
    
    
    /**
     * @var boolean
     * @ORM\Column(name="conditions_acceppted",type="boolean", options={"default":false})
     */
    protected $conditionsAcceppted;
    

    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="userPole")
     * @ORM\JoinTable(name="pole_user_etablissement")
     */
    private $etablissementsPole;

    

    
    

       
    public function GetNbEtablissementsCrees()
    {
        return(count($this->etablissements_cree));
    }
    
    public function GetNbGestionnairesCrees()
    {
        return(count($this->gestionnaires_cree));
    }
    
    
    
    public function GetNbSauvegardes()
    {
        return(count($this->sauvegardes));
    }
    public function GetNbPreuves()
    {
        return(count($this->preuves));
    }

    
    public function GetNbOOA()
    {
        return(count($this->objectifsOperationnel));
    }
    
    public function GetNbOSA()
    {
        return(count($this->objectifs_srategiques));
    }
    
    public function GetNbConstats()
    {
        return(count($this->constats));
    }
    
    public function GetNbBibliotheque()
    {
        return(count($this->bibliotheques));
    }
    
    public function GetNbCommentaireDomaine()
    {
        return(count($this->commentaires));
    }
    
    public function GetMessageCantDelete()
    {
        $message="";
        if ($this->GetNbBibliotheque()) $message.="<li>L'utilisateur a saisi dans la bibliotheque</li>";
        if ($this->GetNbCommentaireDomaine()) $message.="<li>L'utilisateur a commenté des domaines</li>";
        if ($this->GetNbConstats()) $message.="<li>L'utilisateur a saisi des constats</li>";
        if ($this->GetNbOSA()) $message.="<li>L'utilisateur a saisi des objectifs stratégiques</li>";
        if ($this->GetNbOOA()) $message.="<li>L'utilisateur a saisis des objectifs opérationnels</li>";
        if ($this->GetNbPreuves()) $message.="<li>L'utilisateur a saisi des preuves</li>";
        if ($this->GetNbSauvegardes()) $message.="<li>L'utilisateur a effectué des sauvegardes</li>";
        if ($this->GetNbEtablissementsCrees()) $message.="<li>L'utilisateur a crée des établissement</li>";
        if ($this->GetNbGestionnairesCrees()) $message.="<li>L'utilisateur a crée des gestionnaires</li>";
        return($message);
    }

    public function getCantDelete()
    {
        return($this->GetNbBibliotheque()+
                $this->GetNbCommentaireDomaine()+
                $this->GetNbConstats() +
                $this->GetNbOSA() +
                $this->GetNbOOA() +
                $this->GetNbPreuves()+
                $this->GetNbSauvegardes() +
                $this->GetNbEtablissementsCrees() +
                $this->GetNbGestionnairesCrees()); 
    }
    
    
    public function __construct() {
    	$this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    	$this->dateCreate = new \DateTime();
    	$this->conditionsAcceppted = false;
    	$this->tempsConnexionGlobal = new \DateTime('00:00:00');
    }

    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set mdp
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
    	return $this->roles;
    }
    
    public function setRoles($roles) 
    {
    	$this->roles = $roles;
    	
    	return $this;
    }

    
    public function getRolesResume()
    {
//    	$roles_resume=explode("---, ", array_values($this->roles));
     	$roles_resume=implode(" - ",$this->roles);
        $roles_resume=str_replace("ROLE_","", $roles_resume);
    	return $roles_resume;
    }
    
    public function getIsSuperAdmin()
    {
        return(in_array("ROLE_SUPER_ADMIN", $this->roles));
    }
    
    public function getIsAdminPole()
    {
        return(in_array("ROLE_ADMIN_POLE", $this->roles));
    }
    
    public function getAllEtablissement()
    {
        return(in_array("ROLE_SUPER_ADMIN_TRAITEMENT_DEMANDE", $this->roles) or in_array("ROLE_MEGA_ADMIN", $this->roles));
    }
    
    
    
    
    public function getIsSuperAdminUser()
    {
        return(in_array("ROLE_SUPER_ADMIN_UTILISATEUR", $this->roles));
    }

    public function getIsMegaAdmin()
    {
        return(in_array("ROLE_MEGA_ADMIN", $this->roles));
    }
    
    public function getIsSupevisor()
    {
        return(in_array("ROLE_ADMIN_SUPERVISOR", $this->roles));
    }
    
    
    
    public function getIsAdmin()
    {
        return(in_array("ROLE_ADMIN", $this->roles));
    }
    
    
    
    
    
    public function getRolePrincipal()
    {
        if (in_array("ROLE_SUPER_ADMIN", $this->roles))
        {
            return("ROLE_SUPER_ADMIN");
        }
        elseif(in_array("ROLE_GESTIONNAIRE", $this->roles))
        {
            return("ROLE_GESTIONNAIRE");
        }
        elseif(in_array("ROLE_USER", $this->roles))
        {
            return("ROLE_USER");
        }
    }
     
    
        
    
    
    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
    	return $this->salt;
    }
    
    public function eraseCredentials() {
  
    }
    
    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setEtablissement(Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;
        return $this;
    }

    public function getEtablissement()
    {
        return $this->etablissement;
    }

    
    public function IsAnEtablissement()
    {
        if ($this->getEtablissement()) return(true);
    }

    public function hasOneEtablissement()
    {
        if ($this->getEtablissement()) return(true);
    }

    
    public function GetAfficheGantt()
    {
        if ($this->IsAnEtablissement()) 
        {
            return(true);
        }
        elseif ($this->isGestionnaire()) 
        {
            return($this->GetGestionnaire()->getNewFonctionnaliteGestionnaire());
        }
    }
    

    
    
    
    
    
    
    public function getReferentielPublic()
    {
        if ($this->getEtablissement()) return $this->getEtablissement()->getReferentielPublic();
    }
    
    public function getReferentielExterne()
    {
        if ($this->getEtablissement()) return $this->getReferentielPublic()->getReferentielExterne();
    }
    
    
    
    
    /**
     * Set dateLastConnect
     *
     * @param \DateTime $dateLastConnect
     *
     * @return User
     */
    public function setDateLastConnect($dateLastConnect)
    {
        $this->dateLastConnect = $dateLastConnect;

        return $this;
    }

    /**
     * Get dateLastConnect
     *
     * @return \DateTime
     */
    public function getDateLastConnect()
    {
        return $this->dateLastConnect;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return User
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set tempsConnexionGlobal
     *
     * @param \DateTime $tempsConnexionGlobal
     *
     * @return User
     */
    public function setTempsConnexionGlobal($tempsConnexionGlobal)
    {
        $this->tempsConnexionGlobal = $tempsConnexionGlobal;

        return $this;
    }

    /**
     * Get tempsConnexionGlobal
     *
     * @return \DateTime
     */
    public function getTempsConnexionGlobal()
    {
        return $this->tempsConnexionGlobal;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set motDePasseOublie
     *
     * @param string $motDePasseOublie
     *
     * @return User
     */
    public function setMotDePasseOublie($motDePasseOublie)
    {
        $this->motDePasseOublie = $motDePasseOublie;

        return $this;
    }

    /**
     * Get motDePasseOublie
     *
     * @return string
     */
    public function getMotDePasseOublie()
    {
        return $this->motDePasseOublie;
    }
     
     
    /**
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return User
     */
    public function setGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire = null)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \Pericles3Bundle\Entity\Gestionnaire
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }
    
    public function isGestionnaire()
    {
        if ($this->gestionnaire) return(true);
    }
    
   
    
    
    
    
    public function getDomaines()
    {
        if ($this->getEtablissement()) return($this->getEtablissement()->GetDomaines());
    }
    
    
    
    public function getAncreaiSources()
    {
        
        
        return(array(1,3));
    }
    
    
    public function GetParent()
    {
        if ($this->etablissement) { return($this->etablissement);}
        elseif ($this->gestionnaire) { return($this->gestionnaire);}
        elseif ($this->creai) { return($this->creai);}
        else return(0);
    }

    public function GetParentType()
    {
        if ($this->etablissement) { return("E");}
        elseif ($this->gestionnaire) { return("G");}
        elseif ($this->creai) { return("C");}
        else return("-");
    }

    
    public function GetUploadFolderPath() 
    {
        
        //desuet
        if ($this->etablissement) { return ($this->etablissement->GetUploadFolderPath());}
        elseif ($this->gestionnaire) { return ($this->gestionnaire->GetUploadFolderPath());}
        else return("-");
    }
    
    


    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getUsername();
    }

    
    

    /**
     * Set creai
     *
     * @param \Pericles3Bundle\Entity\Creai $creai
     *
     * @return User
     */
    public function setCreai(\Pericles3Bundle\Entity\Creai $creai = null)
    {
        $this->creai = $creai;

        return $this;
    }

    /**
     * Get creai
     *
     * @return \Pericles3Bundle\Entity\Creai
     */
    public function getCreai()
    {
        return $this->creai;
    }
    
    
    public function isCreai()
    {
        return $this->creai;
    }
    
    public function getCreaiOrAncreai()
    {
        return ($this->creai or $this->getIsSupevisor());
    }
    
    
    
    

 
    
    
    
    public function getCreaiCascade()
    {
        if ($this->creai) return $this->creai;
        elseif ($this->getEtablissement())
        { 
            if ($this->getEtablissement()->GetCreai()) return $this->getEtablissement()->GetCreai();
        }
        elseif ($this->getGestionnaire())
        {
            if ($this->getGestionnaire()->GetCreai()) return $this->getGestionnaire()->GetCreai();
        }
    }
    
    
    
    

    /**
     * Set firstPassword
     *
     * @param string $firstPassword
     *
     * @return User
     */
    public function setFirstPassword($firstPassword)
    {
        $this->first_password = $firstPassword;

        return $this;
    }

    /**
     * Get firstPassword
     *
     * @return string
     */
    public function getFirstPassword()
    {
        return $this->first_password;
    }

    /**
     * Set changedPassword
     *
     * @param boolean $changedPassword
     *
     * @return User
     */
    public function setChangedPassword($changedPassword)
    {
        $this->ChangedPassword = $changedPassword;

        return $this;
    }

    /**
     * Get changedPassword
     *
     * @return boolean
     */
    public function getChangedPassword()
    {
        return $this->ChangedPassword;
    }

    /**
     * Add etablissementsCree
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissementsCree
     *
     * @return User
     */
    public function addEtablissementsCree(\Pericles3Bundle\Entity\Etablissement $etablissementsCree)
    {
        $this->etablissements_cree[] = $etablissementsCree;

        return $this;
    }

    /**
     * Remove etablissementsCree
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissementsCree
     */
    public function removeEtablissementsCree(\Pericles3Bundle\Entity\Etablissement $etablissementsCree)
    {
        $this->etablissements_cree->removeElement($etablissementsCree);
    }

    /**
     * Get etablissementsCree
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtablissementsCree()
    {
        return $this->etablissements_cree;
    }

    /**
     * Add gestionnairesCree
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnairesCree
     *
     * @return User
     */
    public function addGestionnairesCree(\Pericles3Bundle\Entity\Gestionnaire $gestionnairesCree)
    {
        $this->gestionnaires_cree[] = $gestionnairesCree;

        return $this;
    }

    /**
     * Remove gestionnairesCree
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnairesCree
     */
    public function removeGestionnairesCree(\Pericles3Bundle\Entity\Gestionnaire $gestionnairesCree)
    {
        $this->gestionnaires_cree->removeElement($gestionnairesCree);
    }

    /**
     * Get gestionnairesCree
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGestionnairesCree()
    {
        return $this->gestionnaires_cree;
    }

    /**
     * Add referentielsPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielsPublic
     *
     * @return User
     */
    public function addReferentielsPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielsPublic)
    {
        $this->ReferentielsPublic[] = $referentielsPublic;

        return $this;
    }

    /**
     * Remove referentielsPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielsPublic
     */
    public function removeReferentielsPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielsPublic)
    {
        $this->ReferentielsPublic->removeElement($referentielsPublic);
    }

    /**
     * Get referentielsPublic
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentielsPublic()
    {
        return $this->ReferentielsPublic;
    }
    
    
    /**
     * Get referentielsPublic
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNbReferentielsPublic()
    {
        return count($this->ReferentielsPublic);
    }
    
    
    /**
     * Get referentielsPublic
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentielsPublicNonFini()
    {
        $refs =  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->ReferentielsPublic as $ref ) 
        {
            if ($ref->GetFini()==0)
            {
                $refs->Add($ref);
            }
        }
        return $refs;
    }
     
    
    public function hasReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        if ($this->ReferentielsPublic->contains($referentielPublic)) { return(true) ;}
        else { return(false); }
    }
    
     

    /**
     * Set desactive
     *
     * @param integer $desactive
     *
     * @return User
     */
    public function setDesactive($desactive)
    {
        $this->desactive = $desactive;

        return $this;
    }

    /**
     * Get desactive
     *
     * @return integer
     */
    public function getDesactive()
    {
        return $this->desactive;
    }
    
    
    public function GetCanSee(\Pericles3Bundle\Entity\User $user)
    {
        if ($this->getIsSuperAdmin())
        {
            if ($this->getIsSuperAdminUser())
            {
                return(true);
            }
            else
            {
                if ($this->isCreai())
                {
                    if ($user->getCreaiCascade()==$this->getCreai())
                    {
                        return(true);
                    }
                    else 
                    {
                        return(false);
                    }
                }
                else
                {
                        return(false);
                }
            }
        }
        elseif ($this->getRolePrincipal()=="ROLE_GESTIONNAIRE")
        {
            if ($user->getGestionnaire()==$this->getGestionnaire())
            {
                return(true);
            }
            elseif ($user->getEtablissement()->GetGestionnaire()==$this->getGestionnaire())
            {
                return(true);
            }
            else
            {
                return(false);
            }
        }
        elseif ($this->getRolePrincipal()=="ROLE_USER")
        {
            if ($user->getEtablissement()==$this->getEtablissement())
            {
                return(true);
            }
            else
            {
                return(false);
            }
        }
    }
    
    public function GetCanModify(\Pericles3Bundle\Entity\User $user)
    {
        $canModify=false;
        if ($this->getIsMegaAdmin() or $this->getIsSupevisor() or $this->getIsAdmin() or ($this->getIsSuperAdmin() && $this->getIsSuperAdminUser())) 
        {
            if ($user->getIsMegaAdmin() or $user->getIsSuperAdmin() or $this->getIsSupevisor() )
            {
                if ($this->getIsMegaAdmin() or $this->getIsSupevisor()) $canModify=true;
                else $canModify=false;
            }
            else
            {
                   $canModify=true;      
            }
        }
        return($this->GetCanSee($user) && $canModify );
    }
    
    public function GetCanDelete(\Pericles3Bundle\Entity\User $user)
    {
        $luimeme=$user->getId()==$this->getId();
        
        return($this->GetCanModify($user) && (! $luimeme));
    }
    
    
    
    
    
    
     
    
    
    
    public function getEtat()
    {
        if ($this->desactive==0)
        {
            return "Actif";
        }
        elseif ($this->desactive==1)
        {
            return "Désactivé";
        }
        elseif ($this->desactive==-1)
        {
            return "A supprimmer";
        }
        
    }
          
    
    

    /**
     * Set conditiosnAcceppted
     *
     * @param boolean $conditiosnAcceppted
     *
     * @return User
     */
    public function setConditiosnAcceppted($conditiosnAcceppted)
    {
        $this->conditiosnAcceppted = $conditiosnAcceppted;

        return $this;
    }

    /**
     * Get conditiosnAcceppted
     *
     * @return boolean
     */
    public function getConditiosnAcceppted()
    {
        return $this->conditiosnAcceppted;
    }

    /**
     * Add bibliotheque
     *
     * @param \Pericles3Bundle\Entity\Bibliotheque $bibliotheque
     *
     * @return User
     */
    public function addBibliotheque(\Pericles3Bundle\Entity\Bibliotheque $bibliotheque)
    {
        $this->bibliotheques[] = $bibliotheque;

        return $this;
    }

    /**
     * Remove bibliotheque
     *
     * @param \Pericles3Bundle\Entity\Bibliotheque $bibliotheque
     */
    public function removeBibliotheque(\Pericles3Bundle\Entity\Bibliotheque $bibliotheque)
    {
        $this->bibliotheques->removeElement($bibliotheque);
    }

    /**
     * Get bibliotheques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBibliotheques()
    {
        return $this->bibliotheques;
    }

    /**
     * Add commentaire
     *
     * @param \Pericles3Bundle\Entity\CommentaireDomaine $commentaire
     *
     * @return User
     */
    public function addCommentaire(\Pericles3Bundle\Entity\CommentaireDomaine $commentaire)
    {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \Pericles3Bundle\Entity\CommentaireDomaine $commentaire
     */
    public function removeCommentaire(\Pericles3Bundle\Entity\CommentaireDomaine $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Add constat
     *
     * @param \Pericles3Bundle\Entity\Constat $constat
     *
     * @return User
     */
    public function addConstat(\Pericles3Bundle\Entity\Constat $constat)
    {
        $this->constats[] = $constat;

        return $this;
    }

    /**
     * Remove constat
     *
     * @param \Pericles3Bundle\Entity\Constat $constat
     */
    public function removeConstat(\Pericles3Bundle\Entity\Constat $constat)
    {
        $this->constats->removeElement($constat);
    }

    /**
     * Get constats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConstats()
    {
        return $this->constats;
    }

    /**
     * Add objectifsSrategique
     *
     * @param \Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsSrategique
     *
     * @return User
     */
    public function addObjectifsSrategique(\Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsSrategique)
    {
        $this->objectifs_srategiques[] = $objectifsSrategique;

        return $this;
    }

    /**
     * Remove objectifsSrategique
     *
     * @param \Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsSrategique
     */
    public function removeObjectifsSrategique(\Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsSrategique)
    {
        $this->objectifs_srategiques->removeElement($objectifsSrategique);
    }

    /**
     * Get objectifsSrategiques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectifsSrategiques()
    {
        return $this->objectifs_srategiques;
    }

    /**
     * Add objectifsOperationnel
     *
     * @param \Pericles3Bundle\Entity\ObjectifOperationnel $objectifsOperationnel
     *
     * @return User
     */
    public function addObjectifsOperationnel(\Pericles3Bundle\Entity\ObjectifOperationnel $objectifsOperationnel)
    {
        $this->objectifsOperationnel[] = $objectifsOperationnel;

        return $this;
    }

    /**
     * Remove objectifsOperationnel
     *
     * @param \Pericles3Bundle\Entity\ObjectifOperationnel $objectifsOperationnel
     */
    public function removeObjectifsOperationnel(\Pericles3Bundle\Entity\ObjectifOperationnel $objectifsOperationnel)
    {
        $this->objectifsOperationnel->removeElement($objectifsOperationnel);
    }

    /**
     * Get objectifsOperationnel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectifsOperationnel()
    {
        return $this->objectifsOperationnel;
    }

    /**
     * Add preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     *
     * @return User
     */
    public function addPreufe(\Pericles3Bundle\Entity\Preuve $preufe)
    {
        $this->preuves[] = $preufe;

        return $this;
    }

    /**
     * Remove preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     */
    public function removePreufe(\Pericles3Bundle\Entity\Preuve $preufe)
    {
        $this->preuves->removeElement($preufe);
    }

    /**
     * Get preuves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreuves()
    {
        return $this->preuves;
    }

    /**
     * Add sauvegarde
     *
     * @param \Pericles3Bundle\Entity\Sauvegarde $sauvegarde
     *
     * @return User
     */
    public function addSauvegarde(\Pericles3Bundle\Entity\Sauvegarde $sauvegarde)
    {
        $this->sauvegardes[] = $sauvegarde;

        return $this;
    }

    /**
     * Remove sauvegarde
     *
     * @param \Pericles3Bundle\Entity\Sauvegarde $sauvegarde
     */
    public function removeSauvegarde(\Pericles3Bundle\Entity\Sauvegarde $sauvegarde)
    {
        $this->sauvegardes->removeElement($sauvegarde);
    }

    /**
     * Get sauvegardes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSauvegardes()
    {
        return $this->sauvegardes;
    }

    /**
     * Set conditionsAcceppted
     *
     * @param boolean $conditionsAcceppted
     *
     * @return User
     */
    public function setConditionsAcceppted($conditionsAcceppted)
    {
        $this->conditionsAcceppted = $conditionsAcceppted;

        return $this;
    }

    /**
     * Get conditionsAcceppted
     *
     * @return boolean
     */
    public function getConditionsAcceppted()
    {
        return $this->conditionsAcceppted;
    }

 
    /**
     * Get etablissementsPole
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtablissementsPole()
    {
        return $this->etablissementsPole;
    }
    
    
    public function getEtablissements()
    {
        if ($this->GetGestionnaire())
        {
            if ($this->getIsAdminPole())
            {
                return $this->etablissementsPole;
            }
            else
            {
                return $this->GetGestionnaire()->GetEtablissements();
            }
        }
    }
    
     
    public function getEtablissementsByRefPublic(ReferentielPublic $referentielPublic)
    {
        $etablissements=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getEtablissements() as $etab)
        {
            if ($etab->GetReferentielPublic()==$referentielPublic)
            {
                $etablissements->Add($etab);
            }
        }
        return($etablissements);
    }
    
    
     
    public function getNbEtablissementsByRefPublic(ReferentielPublic $referentielPublic)
    {
        return(count($this->getEtablissementsByRefPublic($referentielPublic)));
    }

    
    
    
    
    
     
    public function getGestionnaireReferentielsPublic()
    {
        $referentiels=  new \Doctrine\Common\Collections\ArrayCollection();
   
        foreach ($this->getEtablissements() as $etab)
        {
            if (! $referentiels->Contains($etab->GetReferentielPublic()))
            {
                $referentiels->Add($etab->GetReferentielPublic());
            }
        }
        return($referentiels);
    }
         
    
/*    public function EtablissementsPoleReinit()
    {
        $this->etablissementsPole==null;
    }
  */  
    

    public function getNbEtablissementsPole()
    {
        return count($this->etablissementsPole);
    }

    /**
    {
        $this->etablissementsPole[] = $etablissementsPole;
        $etablissementsPole->addUserPole($this);
        return $this;
    }
     */
    

    /**
     * Add etablissementsPole
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissementsPole
     *
     * @return User
     */
    public function addEtablissementsPole(\Pericles3Bundle\Entity\Etablissement $etablissementsPole)
    {
        $this->etablissementsPole[] = $etablissementsPole;

        return $this;
    }

    /**
     * Remove etablissementsPole
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissementsPole
     */
    public function removeEtablissementsPole(\Pericles3Bundle\Entity\Etablissement $etablissementsPole)
    {
        $this->etablissementsPole->removeElement($etablissementsPole);
    }
    
    
    
    public function  ADroitEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        if ($this->getEtablissement()==$etablissement) 
        { 
            return (true);
        }
        elseif (($this->getRolePrincipal()=="ROLE_SUPER_ADMIN")) 
        {
            
            if (($this->getIsSupevisor())) 
            {
                return(true);
            }
            else
            {
                if ($this->IsCreai())
                {
                    if ($etablissement->GetDelegationCreai())
                    {
                        if ($etablissement->getCreai()==$this->getCreai())
                        {
                            return (true);
                        }
                        else {return (false); }
                    }
                    else {return (false); }
                }
                else { return (true); }
            }
        }
        elseif ($this->getRolePrincipal()=="ROLE_GESTIONNAIRE")
        {
            if ($this->getEtablissements()->Contains($etablissement))
            {
                    return (true);
            }
            else
            {
                return (false);
            }
        }
        else
        {
            return (false);
        }
    }
            
    
    
    
}