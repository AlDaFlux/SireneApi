<?php

namespace Pericles3Bundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;


/**
 * Constat
 *
 * @ORM\Table(name="editorial")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\EditorialRepository")
 */
class Editorial
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string")
     */
    private $titre;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;

    /**
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="\Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var User $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="\Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $updatedBy;

    /**
     * @var User $contentChangedBy
     *
     * @Gedmo\Blameable(on="change", field={"titre", "commentaire"})
     * @ORM\ManyToOne(targetEntity="\Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $contentChangedBy;


    /**
     * @var date $created
     *
     * @ORM\Column(name="date_create", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $dateCreate;

    /**
     * @var date $updated
     *
     * @ORM\Column(name="date_update", type="datetime")
     * @Gedmo\Timestampable
     */
    private $dateUpdate;

    /**
     * @var date $updated
     *
     * @ORM\Column(name="date_publication", type="datetime")
     */
    private $datePublication;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\EditorialPublication", inversedBy="articles")
     */
    private $etatPublication;

    
    
  
    
    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\ReferentielPublic",inversedBy="news", cascade={"remove"})
     */
    private $referentielPublics;
    
    
 
    /** 
     * @var boolean
     *
     * @ORM\Column(type="integer")
     */
    private $etablissementGestionnaire;
    
                
    
    
     
    
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getCommentaire();
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Editorial
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Editorial
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
    
    
    
    
    

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Editorial
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
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Editorial
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set createdBy
     *
     * @param \Pericles3Bundle\Entity\User $createdBy
     *
     * @return Editorial
     */
    public function setCreatedBy(\Pericles3Bundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy
     *
     * @param \Pericles3Bundle\Entity\User $updatedBy
     *
     * @return Editorial
     */
    public function setUpdatedBy(\Pericles3Bundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set contentChangedBy
     *
     * @param \Pericles3Bundle\Entity\User $contentChangedBy
     *
     * @return Editorial
     */
    public function setContentChangedBy(\Pericles3Bundle\Entity\User $contentChangedBy = null)
    {
        $this->contentChangedBy = $contentChangedBy;

        return $this;
    }

    /**
     * Get contentChangedBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getContentChangedBy()
    {
        return $this->contentChangedBy;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->datePublication = new \DateTime();
        $this->etablissementGestionnaire = 0;
        $this->referentielPublics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     *
     * @return Editorial
     */
    public function addReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        $this->referentielPublics[] = $referentielPublic;

        return $this;
    }

    /**
     * Remove referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     */
    public function removeReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        $this->referentielPublics->removeElement($referentielPublic);
    }

    /**
     * Get referentielPublics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentielPublics()
    {
        return $this->referentielPublics;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Editorial
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set etatPublication
     *
     * @param \Pericles3Bundle\Entity\EditorialPublication $etatPublication
     *
     * @return Editorial
     */
    public function setEtatPublication(\Pericles3Bundle\Entity\EditorialPublication $etatPublication = null)
    {
        $this->etatPublication = $etatPublication;

        return $this;
    }

    /**
     * Get etatPublication
     *
     * @return \Pericles3Bundle\Entity\EditorialPublication
     */
    public function getEtatPublication()
    {
        return $this->etatPublication;
    }

    /**
     * Set etablissementGestionnaire
     *
     * @param integer $etablissementGestionnaire
     *
     * @return Editorial
     */
    public function setEtablissementGestionnaire($etablissementGestionnaire)
    {
        $this->etablissementGestionnaire = $etablissementGestionnaire;

        return $this;
    }

    /**
     * Get etablissementGestionnaire
     *
     * @return integer
     */
    public function getEtablissementGestionnaire()
    {
        return $this->etablissementGestionnaire;
    }
     
    
    
}