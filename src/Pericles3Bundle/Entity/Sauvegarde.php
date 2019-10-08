<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
 
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;


/**
 * Domaine
 *
 * @ORM\Table(name="sauvegarde")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\SauvegardeRepository")
 */
class Sauvegarde
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    
   // use SoftDeleteableEntity;  * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

    
    
   /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="sauvegardes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etablissement;
    

    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User", inversedBy="sauvegardes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;

    

    /**
     * @var \DateTime   
     * @ORM\Column(type="datetime",nullable=true)
     */
     private $deletedAt;
    
    
     
    
  

    /**
     * @ORM\Column(type="boolean")
     */
    protected $toDelete;
           

    

    
    
    

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeDomaine", mappedBy="sauvegarde", cascade={"remove"})
     */
    private $domaines;

    

        
    /**
     * @var int
     *
     * @ORM\Column(name="note", type="float", nullable=true)
     */
    private $note;
    

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->domaines = new \Doctrine\Common\Collections\ArrayCollection();
        $this->toDelete=false;
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
     * @return Sauvegarde
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Sauvegarde
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
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Sauvegarde
     */
    public function setEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \Pericles3Bundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return Sauvegarde
     */
    public function setUser(\Pericles3Bundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    
    /**
     * Set note
     *
     * @param integer $note
     *
     * @return SauvegardeDomaine
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    
    
    /**
     * Add domaine
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDomaine $domaine
     *
     * @return Sauvegarde
     */
    public function addDomaine(\Pericles3Bundle\Entity\SauvegardeDomaine $domaine)
    {
        $this->domaines[] = $domaine;
        return $this;
    }

    /**
     * Remove domaine
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDomaine $domaine
     */
    public function removeDomaine(\Pericles3Bundle\Entity\SauvegardeDomaine $domaine)
    {
        $this->domaines->removeElement($domaine);
    }

    /**
     * Get domaines
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDomaines()
    {
        return $this->domaines;
    }
    
    
        
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return "Sauvegarde du ".date_format($this->dateCreate,"d/m/Y");
    }
    
    

     
    public function getDomaineByOrdre($ordre)
    {
        foreach ($this->domaines as $domaine)
        {
            if ($domaine->getOrdre()==$ordre) {return($domaine);}
        }
    }

    
    
    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getPlusDunAn()
    {
        $unAn = new \DateTime(" - 365 day");
        return  ($this->dateCreate< $unAn );
    }
    
    
    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getMoinsdeTroisMois()
    {
        $troisMois = new \DateTime(" - 3 months ");
        return  ($this->dateCreate >  $troisMois );
    }
     
    
    
    /**
     * Get Referentiel
     *
     * @return \boolean
     */
    public function getReferentiel()
    {
        return($this->domaines[0]->GetReferentiel()->GetReferentielPublic());
    }
    
    /**
     * Get ReferentielDesuet
     *
     * @return \boolean
     */
    public function getReferentielDesuet()
    {
        return($this->getReferentiel() != $this->GetEtablissement()->GetReferentielPublic());
    }
    
    
    
     
    
    
    

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Sauvegarde
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return Sauvegarde
     */
    public function setToDelete($toDelete)
    {
        $this->toDelete = $toDelete;

        return $this;
    }

    /**
     * Get toDelete
     *
     * @return boolean
     */
    public function getToDelete()
    {
        return $this->toDelete;
    }
}
