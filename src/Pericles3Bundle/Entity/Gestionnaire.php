<?php

namespace Pericles3Bundle\Entity;


use Gedmo\Mapping\Annotation as Gedmo;


use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Gestionnaire
 *
 * @ORM\Table(name="gestionnaire")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\GestionnaireRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Gestionnaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    
    use SoftDeleteableEntity;

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
     * @ORM\Column(name="codePostal", type="string", length=255, nullable=true)
     */
    private $codePostal;

      /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;
    

     /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tel;



    

     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", mappedBy="gestionnaire")
     */
    private $etablissements;
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\User", mappedBy="gestionnaire")
     */
    private $users;
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Bibliotheque", mappedBy="gestionnaire")
     */
    private $bibliotheques;
    
    
        
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User",  inversedBy="gestionnaires_cree")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CreatedBy;
    
    

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $CreatedDate;

    
      
    /**
     * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\FinessGestionnaire", inversedBy="gestionnaire")
     * @ORM\JoinColumn(referencedColumnName="code_finess", nullable=true) 
     */
    private $finess;  
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Creai", inversedBy="gestionnaires")
     * @ORM\OrderBy({"nom" = "asc"})
     */
    private $creai;
    

     
    /**
    * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\DemandeGestionnaire", mappedBy="Gestionnaire")
    */
    private $demandeGestionnaire;

  
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\StockageGestionnaire", inversedBy="gestionnaires")
     */
    private $StockageGestionnaire;

    
             
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\FacturePresta", mappedBy="gestionnaire")
     */
    private $facturePrestas;


    
     
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\GestionnaireCategory", inversedBy="gestionnaires")
     */
    private $category;


    
    /** 
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $newFonctionnaliteGestionnaire;
             
    
    

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Pericles", mappedBy="gestionnaire")
     */
    private $pericles;
    
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_last_connect", type="datetime", nullable=true)
     */
    private $dateLastConnect;


    
    
    
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Gestionnaire
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
     * @return Gestionnaire
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
     * @return Gestionnaire
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
     * @return Gestionnaire
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
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getNom();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etablissements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Gestionnaire
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
    
    
    public function getEtablissementsByUsers(User $user)
    {
        $etablissements=new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->etablissements as $etablissement)
        {
            if ($user->ADroitEtablissement($etablissement)) { $etablissements->Add($etablissement);}
        }
        return($etablissements);
    }
    
    
    public function getEtablissementsDelegationCreaiAllByUsers(User $user)
    {
        $allDelegation=true;
        $etablissements=$this->getEtablissementsByUsers($user);
        
        foreach ($etablissements as $etablissement)
        {
            $allDelegation=$allDelegation && $etablissement->GetDelegationCreai();
        }
        return($allDelegation);
    }
    
    
    
    
    
    
    
    public function getNbEtablissements()
    {
        return count($this->etablissements);
    }
    
    
    public function getEtablissementsModeCotisationRenseigne()
    {
        foreach ($this->getEtablissements() as $Etablissement) 
        {
            if ($Etablissement->GetModeCotisation()->GetId()==0) return(false);
        }
        return(true);
    }
    
    public function hasEtablissementFinContrat()
    {
        foreach ($this->getEtablissements() as $Etablissement) 
        {
            if ($Etablissement->GetFinContrat()) return(true);
        }
        return(false);
    }
    
    public function hasEtablissementNoFinContrat()
    {
        foreach ($this->getEtablissements() as $Etablissement) 
        {
            if (! $Etablissement->GetFinContrat()) return(true);
        }
        return(false);
    }
    
    
    
    
    


    public function getReferentielAJour()
    {
        foreach ($this->getEtablissements() as $Etablissement) 
        {
            if ($Etablissement->GetReferentielPublic()->GetObsolete()) return(false);
        }
        return(true);
    }
    
    


    
    
    
    

    /**
     * Add user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return Gestionnaire
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
    
    public function getUsersEtablissements()
    {
        $users=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getEtablissements() as $etablissement) 
        {
            foreach ($etablissement->getUsers() as $user) 
            {
                $users->Add($user);
            }
        }
        return $users;
    }
    
    public function getNbUsersEtablissements()
    {
        return count($this->getUsersEtablissements());
    }
    
    
    
    



    /**
     * Add bibliotheque
     *
     * @param \Pericles3Bundle\Entity\Bibliotheque $bibliotheque
     *
     * @return Gestionnaire
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
    
    
    
        
    public function getNbBibliotheques()
    {
        return(count($this->bibliotheques));
    }
    
    
    
        
    public function getNbBibliothequesFichier()
    {
        $total=0;
        foreach ($this->getBibliotheques() as $Bibliotheque) 
        {
            if ($Bibliotheque->GetFichier()<>'') $total+=1;
        }
        return $total;
    }
    
    
    
    
    
    public function GetUploadFolderPath() 
    {
        return ("st02/gestionnaire_".$this->getId());
    }
    
    
    
    

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Gestionnaire
     */
    public function setCreatedDate($createdDate)
    {
        $this->CreatedDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->CreatedDate;
    }

    /**
     * Set createdBy
     *
     * @param \Pericles3Bundle\Entity\User $createdBy
     *
     * @return Gestionnaire
     */
    public function setCreatedBy(\Pericles3Bundle\Entity\User $createdBy)
    {
        $this->CreatedBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->CreatedBy;
    }

    /**
     * Set creai
     *
     * @param \Pericles3Bundle\Entity\Creai $creai
     *
     * @return Gestionnaire
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

    /**
     * Set demandeGestionnaire
     *
     * @param \Pericles3Bundle\Entity\DemandeGestionnaire $demandeGestionnaire
     *
     * @return Gestionnaire
     */
    public function setDemandeGestionnaire(\Pericles3Bundle\Entity\DemandeGestionnaire $demandeGestionnaire = null)
    {
        $this->demandeGestionnaire = $demandeGestionnaire;
        $demandeGestionnaire->setGestionnaire($this);
        return $this;
    }

    /**
     * Get demandeGestionnaire
     *
     * @return \Pericles3Bundle\Entity\DemandeGestionnaire
     */
    public function getDemandeGestionnaire()
    {
        return $this->demandeGestionnaire;
    }
    
    public function getDemande()
    {
        return $this->getDemandeGestionnaire();
    }


    public function sizeMaxUpload()
    {
        return($this->getStockageGestionnaire()->getCapacite());
    }


    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Facture", mappedBy="gestionnaire")
     */
    private $factures;

    
    
    

    /**
     * Set stockageGestionnaire
     *
     * @param \Pericles3Bundle\Entity\StockageGestionnaire $stockageGestionnaire
     *
     * @return Gestionnaire
     */
    public function setStockageGestionnaire(\Pericles3Bundle\Entity\StockageGestionnaire $stockageGestionnaire = null)
    {
        $this->StockageGestionnaire = $stockageGestionnaire;

        return $this;
    }

    /**
     * Get stockageGestionnaire
     *
     * @return \Pericles3Bundle\Entity\StockageGestionnaire
     */
    public function getStockageGestionnaire()
    {
        return $this->StockageGestionnaire;
    }
    
    function IsReel()
    {
        $reel=1;
        foreach ($this->getEtablissements() as $Etablissement) 
        {
            $reel=$reel* $Etablissement->getCategory()->GetReel();
        }
        return $reel;
    }
    
    
    function reelModuleActive()
    {
        return ($this->getCategory()->getId()==1);
    }
    
    
    

    /**
     * Set finess
     *
     * @param \Pericles3Bundle\Entity\FinessGestionnaire $finess
     *
     * @return Gestionnaire
     */
    public function setFiness(\Pericles3Bundle\Entity\FinessGestionnaire $finess = null)
    {
        $this->finess = $finess;

        return $this;
    }

    /**
     * Get finess
     *
     * @return \Pericles3Bundle\Entity\FinessGestionnaire
     */
    public function getFiness()
    {
        return $this->finess;
    }
    
    
    
    

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Gestionnaire
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Add facture
     *
     * @param \Pericles3Bundle\Entity\Facture $facture
     *
     * @return Gestionnaire
     */
    public function addFacture(\Pericles3Bundle\Entity\Facture $facture)
    {
        $this->factures[] = $facture;

        return $this;
    }

    /**
     * Remove facture
     *
     * @param \Pericles3Bundle\Entity\Facture $facture
     */
    public function removeFacture(\Pericles3Bundle\Entity\Facture $facture)
    {
        $this->factures->removeElement($facture);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFactures()
    {
        return $this->factures;
    }

    /**
     * Add facturePresta
     *
     * @param \Pericles3Bundle\Entity\FacturePresta $facturePresta
     *
     * @return Gestionnaire
     */
    public function addFacturePresta(\Pericles3Bundle\Entity\FacturePresta $facturePresta)
    {
        $this->facturePrestas[] = $facturePresta;

        return $this;
    }

    /**
     * Remove facturePresta
     *
     * @param \Pericles3Bundle\Entity\FacturePresta $facturePresta
     */
    public function removeFacturePresta(\Pericles3Bundle\Entity\FacturePresta $facturePresta)
    {
        $this->facturePrestas->removeElement($facturePresta);
    }

    /**
     * Get facturePrestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturePrestas()
    {
        return $this->facturePrestas;
    }
     
     public function getHasFacturePrestas()
    {
        return count($this->facturePrestas);
    }
     
 
    
    
    public function getLastFacturePresta()
    {
        $lastdate=0;
        $last_facture=null;
        foreach ($this->getPrestasNotAVoir() as $presta)
        {
            if ($presta->getFacture()->getDateEmission()->getTimestamp()>$lastdate)
            {
                $lastdate=$presta->getDateEmission()->getTimestamp();
                $last_facture=$presta;
            }
        }
        return $last_facture;
    }
    
     public function getFirstFacturePresta()
    {
        $lastdate=0;
        $last_facture=null;
        foreach ($this->getPrestasNotAVoir() as $presta)
        {
            if ($lastdate==0)
            {
                $lastdate = $presta->getDateEmission()->getTimestamp();
                $last_facture=$presta;
            }
            if ($presta->getDateEmission()->getTimestamp()<$lastdate)
            {
                $lastdate=$presta->getDateEmission()->getTimestamp();
                $last_facture=$presta;
            }
        }
        return $last_facture;
    }
    
     public function getPrestasNotAVoir()
    {
        $prestas=  new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->getFacturePrestas() as $presta) 
        {
            if (! $presta->getFacture()->getAvoir()) $prestas->Add($presta);
        }
        return $prestas;
    }
    
    public function getFacturesPrestaFactures()
    {
        $factures=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getFacturePrestas() as $presta) 
        {
                $factures->Add($presta->getFacture());
        }
        return $factures;
    }
    
     public function getFacturesAll()
     {
        $factures= $this->getFacturesPrestaFactures();
        foreach ($this->getFactures() as $facture) 
        {
            if (!  $factures->contains($facture)) $factures->Add($facture);
        }
         return($factures);
     }
    
    
    
    public function getLastFacture()
    {
        $lastdate=0;
        $last_facture=null;
        foreach ($this->getFacturesAll() as $facture)
        {
            if ($facture->getDateEmission()->getTimestamp()>$lastdate)
            {
                $last_facture=$facture;
            }
        }
        return $last_facture;
    }
    
     
    public function getEtablissementsSansPrestas()
    {
        $etablissements=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getEtablissements() as $etablissement)
        {
            if ($etablissement->getFacturable() &&  $etablissement->getNbFacturesNotAVoir()==0)
            {
                $etablissements->Add($etablissement);
            }
        }
        return $etablissements;
    }
    
    public function getNbEtablissementsSansPrestas()
    { 
        return count($this->getEtablissementsSansPrestas());
    }
    
   

    /**
     * Set category
     *
     * @param \Pericles3Bundle\Entity\GestionnaireCategory $category
     *
     * @return Gestionnaire
     */
    public function setCategory(\Pericles3Bundle\Entity\GestionnaireCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Pericles3Bundle\Entity\GestionnaireCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    
    public function GetFinContrat()
    {
        return $this->category->getId()==4;
    }


    /**
     * Set newFonctionnaliteGestionnaire
     *
     * @param boolean $newFonctionnaliteGestionnaire
     *
     * @return Gestionnaire
     */
    public function setNewFonctionnaliteGestionnaire($newFonctionnaliteGestionnaire)
    {
        $this->newFonctionnaliteGestionnaire = $newFonctionnaliteGestionnaire;

        return $this;
    }

    /**
     * Get newFonctionnaliteGestionnaire
     *
     * @return boolean
     */
    public function getNewFonctionnaliteGestionnaire()
    {
        return $this->newFonctionnaliteGestionnaire;
    }
    
    
    
    

    /**
     * Add pericle
     *
     * @param \Pericles3Bundle\Entity\Pericles $pericle
     *
     * @return Gestionnaire
     */
    public function addPericle(\Pericles3Bundle\Entity\Pericles $pericle)
    {
        $this->pericles[] = $pericle;

        return $this;
    }

    /**
     * Remove pericle
     *
     * @param \Pericles3Bundle\Entity\Pericles $pericle
     */
    public function removePericle(\Pericles3Bundle\Entity\Pericles $pericle)
    {
        $this->pericles->removeElement($pericle);
    }

    /**
     * Get pericles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPericles()
    {
        return $this->pericles;
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

    
    
}
