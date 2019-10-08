<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;




/**
 * Creai
 *
 * @ORM\Table(name="creai")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\CreaiRepository")
 */
class Creai
{
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=255, nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;
    
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", mappedBy="creai")
     * @ORM\OrderBy({"nom" = "asc"})
     */
    private $etablissements;

    
        
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Departement", mappedBy="creai")
     */
    private $departements;

    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\User", mappedBy="creai")
     */
    private $users;

    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Gestionnaire", mappedBy="creai")
     * @ORM\OrderBy({"nom" = "asc"})
     */
    private $gestionnaires;
    
    
               
    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeInfos", mappedBy="creai")
    */
    private $demandesinfos;

    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeEtablissement", mappedBy="creai")
    */
    private $demandesEtablissement;

    
    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeGestionnaire", mappedBy="creai")
    */
    private $demandesGestionnaire;
    
    

    
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_last_connect", type="datetime", nullable=true)
     */
    private $dateLastConnect;

        

    
    
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", mappedBy="creai")
     */
    private $publicsFacturation;
    
    

    
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etablissements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Creai
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Creai
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Creai
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Creai
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Add etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Creai
     */
    public function addEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->etablissements[] = $etablissement;

        return $this;
    }

    /**
     * Remove etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     */
    public function removeEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->etablissements->removeElement($etablissement);
    }

    /**
     * Get etablissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtablissements()
    {
        return $this->etablissements;
    }

    public function getNbEtablissements()
    {
        return count($this->etablissements);
    }

    public function getEtablissementsReels()
    {
        $etablissements =  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->etablissements as $etablissement ) 
        {
            if ($etablissement->IsReel())
            {
                $etablissements->Add($etablissement);
            }
        }
        return $etablissements;
    }
    
    
    public function getNbEtablissementsReels()
    {
        return count($this->getEtablissementsReels());
    }
    
    
    
    
    

    /**
     * Add user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return Creai
     */
    public function addUser(\Pericles3Bundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Pericles3Bundle\Entity\User $user
     */
    public function removeUser(\Pericles3Bundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    
    public function getNbUsers()
    {
        return count($this->users);
    }

    
    
    /**
     * Add departement
     *
     * @param \Pericles3Bundle\Entity\Departement $departement
     *
     * @return Creai
     */
    public function addDepartement(\Pericles3Bundle\Entity\Departement $departement)
    {
        $this->departements[] = $departement;

        return $this;
    }

    /**
     * Remove departement
     *
     * @param \Pericles3Bundle\Entity\Departement $departement
     */
    public function removeDepartement(\Pericles3Bundle\Entity\Departement $departement)
    {
        $this->departements->removeElement($departement);
    }

    /**
     * Get departements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartements()
    {
        return $this->departements;
    }
    
    function __toString() 
    {
        return("".$this->nom);
    }

    /**
     * Add gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return Creai
     */
    public function addGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire)
    {
        $this->gestionnaires[] = $gestionnaire;

        return $this;
    }

    /**
     * Remove gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     */
    public function removeGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire)
    {
        $this->gestionnaires->removeElement($gestionnaire);
    }

    /**
     * Get gestionnaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGestionnaires()
    {
        return $this->gestionnaires;
    }
    
    
    public function getGestionnairesReels()
    {
        $gestionnaires =  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->gestionnaires as $gestionnaire ) 
        {
            if ($gestionnaire->reelModuleActive())
            {
                $gestionnaires->Add($gestionnaire);
            }
        }
        return $gestionnaires;
    }

    
    public function getNbGestionnaires()
    {
        return count($this->gestionnaires);
    }

    public function getNbGestionnairesReels()
    {
          return count($this->getGestionnairesReels());
    }

    
    
    
    
    
    
    
    
    /**
     * Add demandesinfo
     *
     * @param \Pericles3Bundle\Entity\DemandeInfos $demandesinfo
     *
     * @return Creai
     */
    public function addDemandesinfo(\Pericles3Bundle\Entity\DemandeInfos $demandesinfo)
    {
        $this->demandesinfos[] = $demandesinfo;

        return $this;
    }

    /**
     * Remove demandesinfo
     *
     * @param \Pericles3Bundle\Entity\DemandeInfos $demandesinfo
     */
    public function removeDemandesinfo(\Pericles3Bundle\Entity\DemandeInfos $demandesinfo)
    {
        $this->demandesinfos->removeElement($demandesinfo);
    }

    /**
     * Get demandesinfos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandesinfos()
    {
        return $this->demandesinfos;
    }
    
    
    public function getDemandesinfosNonFinies()
    {
        $demandes=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getDemandesinfos() as $demande ) 
        {
            if (! $demande->IsFini())
            {
                $demandes->Add($demande);
            }
        }
        return $demandes;
    }
    
    public function getNbDemandesInfosNonFinies()
    {
        return(count($this->getDemandesinfosNonFinies()));
    }
    
    
    
    public function getDemandesinfosATraiter()
    {
        $demandes=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getDemandesinfos() as $demande ) 
        {
            if ($demande->ATraiter())
            {
                $demandes->Add($demande);
            }
        }
        return $demandes;
    }
    
    public function getNbDemandesInfosATraiter()
    {
        return(count($this->getDemandesinfosATraiter()));
    }
    
    
    
    
    

    /**
     * Add demandesEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement
     *
     * @return Creai
     */
    public function addDemandesEtablissement(\Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement)
    {
        $this->demandesEtablissement[] = $demandesEtablissement;

        return $this;
    }

    /**
     * Remove demandesEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement
     */
    public function removeDemandesEtablissement(\Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement)
    {
        $this->demandesEtablissement->removeElement($demandesEtablissement);
    }

    /**
     * Get demandesEtablissement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandesEtablissement()
    {
        return $this->demandesEtablissement;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Creai
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

    /**
     * Add demandesGestionnaire
     *
     * @param \Pericles3Bundle\Entity\DemandeGestionnaire $demandesGestionnaire
     *
     * @return Creai
     */
    public function addDemandesGestionnaire(\Pericles3Bundle\Entity\DemandeGestionnaire $demandesGestionnaire)
    {
        $this->demandesGestionnaire[] = $demandesGestionnaire;

        return $this;
    }

    /**
     * Remove demandesGestionnaire
     *
     * @param \Pericles3Bundle\Entity\DemandeGestionnaire $demandesGestionnaire
     */
    public function removeDemandesGestionnaire(\Pericles3Bundle\Entity\DemandeGestionnaire $demandesGestionnaire)
    {
        $this->demandesGestionnaire->removeElement($demandesGestionnaire);
    }

    /**
     * Get demandesGestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandesGestionnaire()
    {
        return $this->demandesGestionnaire;
    }

    
     
    
    public function getFactures()
    {
        $factures=new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getGestionnaires() as $gestionnaire)
        {
            foreach ($gestionnaire->getFactures() as $facture)
            {
                if ($facture->IsFinalised() && $facture->getConcerneGestionnaire()) $factures->Add($facture);
            }
        }
        foreach ($this->getEtablissementsReels() as $etablissement)
        {
            foreach ($etablissement->getFactures() as $facture)
            {
                if ($facture->IsFinalised() && ! $facture->getConcerneGestionnaire()) $factures->Add($facture);
            }
        }
        return ($factures);
    }
    
    
    
    public function getFacturesMontant()
    {
         
        $total=0;
        foreach ($this->getFactures() as $facture)
        {
             $total+=$facture->getMontantTotal();
        }
        return ($total);
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
     * Add publicsFacturation
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $publicsFacturation
     *
     * @return Creai
     */
    public function addPublicsFacturation(\Pericles3Bundle\Entity\ReferentielPublic $publicsFacturation)
    {
        $this->publicsFacturation[] = $publicsFacturation;
        $publicsFacturation->setCreai($this);
        return $this;
    }

    /**
     * Remove publicsFacturation
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $publicsFacturation
     */
    public function removePublicsFacturation(\Pericles3Bundle\Entity\ReferentielPublic $publicsFacturation)
    {
        $this->publicsFacturation->removeElement($publicsFacturation);
    }

    /**
     * Get publicsFacturation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublicsFacturation()
    {
        return $this->publicsFacturation;
    }

    public function getNbPublicsFacturation()
    {
        return $this->publicsFacturation;
    }
}
