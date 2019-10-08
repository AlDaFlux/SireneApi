<?php

namespace Pericles3Bundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;


use Gedmo\Mapping\Annotation as Gedmo;


use Doctrine\ORM\Mapping as ORM;

/**
 * Bibliotheque
 *
 * @ORM\Table(name="bibliotheque_ancreai")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\BibliothequeAncreaiRepository")
 */
class BibliothequeAncreai
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
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $titre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="href", type="string", length=255)
     */
    private $href;

    
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $cache;

  
    
    
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\BibliothequeAncreaiTypeSource", inversedBy="bibliothequesAncreai")
    * @ORM\JoinColumn(nullable=false)
    */
    private $typeSourceBA;




    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime")
     */
    private $dateUpdate;

   
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_publication", type="datetime")
     */
    private $datePublication;

   
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastTest;

    
    
    /** 
     * @var boolean
     *
     * @ORM\Column(type="integer")
     */
    private $codeRetour;
             

    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lastModifiedBy;


    
    

    
    
    
   

    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="bibliothequesAncreai")
     */
    private $referentielPublics;
    
    
    
        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Referentiel", mappedBy="RBPP")
     */
    private $Criterereferentiel;

    

    
    
    
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->referentielPublics = new \Doctrine\Common\Collections\ArrayCollection();
        $this->codeRetour = 0;
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
     * @return BibliothequeAncreai
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
     * Set href
     *
     * @param string $href
     *
     * @return BibliothequeAncreai
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Get href
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    
    /**
     * Get typeSource
     *
     * @return string
     */
    public function getTypeSource()
    {
        
        return $this->getTypeSourceBA();
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return BibliothequeAncreai
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
     * Add referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     *
     * @return BibliothequeAncreai
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
     
    
    
    
    public function hasReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        if ($this->referentielPublics->contains($referentielPublic)) return(true);
        else return(false);
    }
    

    /**
     * Set typeSourceBA
     *
     * @param \Pericles3Bundle\Entity\BibliothequeAncreaiTypeSource $typeSourceBA
     *
     * @return BibliothequeAncreai
     */
    public function setTypeSourceBA(\Pericles3Bundle\Entity\BibliothequeAncreaiTypeSource $typeSourceBA)
    {
        $this->typeSourceBA = $typeSourceBA;

        return $this;
    }

    /**
     * Get typeSourceBA
     *
     * @return \Pericles3Bundle\Entity\BibliothequeAncreaiTypeSource
     */
    public function getTypeSourceBA()
    {
        return $this->typeSourceBA;
    }
    
    
    
     /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getTitre();
    }
    

    /**
     * Add criterereferentiel
     *
     * @param \Pericles3Bundle\Entity\Referentiel $criterereferentiel
     *
     * @return BibliothequeAncreai
     */
    public function addCriterereferentiel(\Pericles3Bundle\Entity\Referentiel $criterereferentiel)
    {
        $this->Criterereferentiel[] = $criterereferentiel;

        return $this;
    }

    /**
     * Remove criterereferentiel
     *
     * @param \Pericles3Bundle\Entity\Referentiel $criterereferentiel
     */
    public function removeCriterereferentiel(\Pericles3Bundle\Entity\Referentiel $criterereferentiel)
    {
        $this->Criterereferentiel->removeElement($criterereferentiel);
    }

    /**
     * Get criterereferentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriterereferentiel()
    {
        return $this->Criterereferentiel;
    }
    
    
    
    public function NbCriteres()
    {
        return(count($this->getCriterereferentiel()));
    }
    
            
    public function NbCriteresByRefPublic(ReferentielPublic $referentielPublic)
    {
        return(count($this->CriteresByRefPublic($referentielPublic)));
    }
    
    
    public function CriteresByRefPublic(ReferentielPublic $referentielPublic)
    {
        $criteres = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->Criterereferentiel as $CritereReferentiel)
        {
            if ($CritereReferentiel->GetReferentielPublic()->GetId() ==$referentielPublic->GetId()) $criteres->Add($CritereReferentiel);
        }
        return($criteres);
    }
    
    
            

    
    public function NbReferentiels()
    {
        return(count($this->referentielPublics));
    }
            

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return BibliothequeAncreai
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
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return BibliothequeAncreai
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
     * Set createdBy
     *
     * @param \Pericles3Bundle\Entity\User $createdBy
     *
     * @return BibliothequeAncreai
     */
    public function setCreatedBy(\Pericles3Bundle\Entity\User $createdBy)
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
     * Set lastModifiedBy
     *
     * @param \Pericles3Bundle\Entity\User $lastModifiedBy
     *
     * @return BibliothequeAncreai
     */
    public function setLastModifiedBy(\Pericles3Bundle\Entity\User $lastModifiedBy)
    {
        $this->lastModifiedBy = $lastModifiedBy;

        return $this;
    }

    /**
     * Get lastModifiedBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }

    /**
     * Set lastTest
     *
     * @param \DateTime $lastTest
     *
     * @return BibliothequeAncreai
     */
    public function setLastTest($lastTest)
    {
        $this->lastTest = $lastTest;

        return $this;
    }

    /**
     * Get lastTest
     *
     * @return \DateTime
     */
    public function getLastTest()
    {
        return $this->lastTest;
    }

    /**
     * Set codeRetour
     *
     * @param integer $codeRetour
     *
     * @return BibliothequeAncreai
     */
    public function setCodeRetour($codeRetour)
    {
        $this->codeRetour = $codeRetour;

        return $this;
    }

    /**
     * Get codeRetour
     *
     * @return integer
     */
    public function getCodeRetour()
    {
        return $this->codeRetour;
    }

    public function getLienOK()
    {
        return ($this->codeRetour==200);
    }
    
    
    public function getCodeRetourLib()
    {
        
        switch ($this->codeRetour)
        {
            case 200:
                return("Lien OK ! (200)");
                break;
            case 301:
            case 302:
                return("Lien déplacé ! (".$this->codeRetour.")");
                break;
            case 404:
                return("Lien mort ! (404)");
                break;
            default:
                return("Code retour (".$this->codeRetour.") inconnu");
                break;
        }
            
              
    }

    

    /**
     * Set cache
     *
     * @param string $cache
     *
     * @return BibliothequeAncreai
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get cache
     *
     * @return string
     */
    public function getCache()
    {
        return $this->cache;
    }
}
