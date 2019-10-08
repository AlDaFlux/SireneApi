<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="sauvegarde_dimension")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\SauvegardeDimensionRepository")
 */
class SauvegardeDimension
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="sauvegardeDimension")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;



    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\SauvegardeDomaine", inversedBy="dimensions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $domaine;
    
      /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Dimension")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dimension_original;


    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeCritere", mappedBy="dimension", cascade={"remove"})
     */
    private $criteres;
    
    
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
        $this->criteres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set note
     *
     * @param integer $note
     *
     * @return SauvegardeDimension
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
     * Set referentiel
     *
     * @param \Pericles3Bundle\Entity\Referentiel $referentiel
     *
     * @return SauvegardeDimension
     */
    public function setReferentiel(\Pericles3Bundle\Entity\Referentiel $referentiel)
    {
        $this->referentiel = $referentiel;

        return $this;
    }

    /**
     * Get referentiel
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getReferentiel()
    {
        return $this->referentiel;
    }

    /**
     * Set domaine
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDomaine $domaine
     *
     * @return SauvegardeDimension
     */
    public function setDomaine(\Pericles3Bundle\Entity\SauvegardeDomaine $domaine)
    {
        $this->domaine = $domaine;
        $domaine->addDimension($this);  
        return $this;
    }

    /**
     * Get domaine
     *
     * @return \Pericles3Bundle\Entity\SauvegardeDomaine
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * Add critere
     *
     * @param \Pericles3Bundle\Entity\SauvegardeCritere $critere
     *
     * @return SauvegardeDimension
     */
    public function addCritere(\Pericles3Bundle\Entity\SauvegardeCritere $critere)
    {
        $this->criteres[] = $critere;

        return $this;
    }

    /**
     * Remove critere
     *
     * @param \Pericles3Bundle\Entity\SauvegardeCritere $critere
     */
    public function removeCritere(\Pericles3Bundle\Entity\SauvegardeCritere $critere)
    {
        $this->criteres->removeElement($critere);
    }

    /**
     * Get criteres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriteres()
    {
        return $this->criteres;
    }

    /**
     * Set dimensionOriginal
     *
     * @param \Pericles3Bundle\Entity\Dimension $dimensionOriginal
     *
     * @return SauvegardeDimension
     */
    public function setDimensionOriginal(\Pericles3Bundle\Entity\Dimension $dimensionOriginal)
    {
        $this->dimension_original = $dimensionOriginal;

        return $this;
    }

    /**
     * Get dimensionOriginal
     *
     * @return \Pericles3Bundle\Entity\Dimension
     */
    public function getDimensionOriginal()
    {
        return $this->dimension_original;
    }
    
    
    
    public function getGraphData()
    {
        if ($this->getNote()) return($this->getNote());
        else  return(0);
    }

    

    public function getOrdre()
    {
        return($this->referentiel->getOrdre());
    }
               
              
    /**
     * toString
     * @return string
     */
    public function GetNumero() 
    {
        return $this->getDomaine()->GetNumero().".".$this->GetOrdre();
        
    }
      
    public function getNom()
    {
        return($this->referentiel->getNom());
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
     * Get note
     *
     * @return integer
     */
    public function getNoteActuelle()
    {
           return $this->dimension_original->GetMoyenneNotes();
    }
 
    
     
    /**
     * Get note
     *
     * @return string
     */
    public function getEvolution()
    {
           if ($this->getNote()==null) return("nouveau"); 
           elseif ($this->getNoteActuelle()==$this->getNote()) return("stable");
           elseif ($this->getNoteActuelle()<$this->getNote()) return("baisse");
           else return("hausse");
    }
 
       
    public function getSauvegarde()
    {
           return $this->GetDomaine()->GetSauvegarde();
    }
    
    
    
    public function getNextDimension()
    {
        return $this->domaine->getDimensionByOrdre($this->getOrdre()+1);
    }
    public function getPreviousDimension()
    {
        return $this->domaine->getDimensionByOrdre($this->getOrdre()-1);
    }
    
    public function getCritereByOrdre($ordre)
    {
        foreach ($this->criteres as $critere)
        {
            if ($critere->GetOrdre()==$ordre) 
            {
                return($critere);
                break;
            }
        }
    }


}
