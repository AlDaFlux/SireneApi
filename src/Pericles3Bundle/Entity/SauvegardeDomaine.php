<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="sauvegarde_domaine")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\SauvegardeDomaineRepository")
 */
class SauvegardeDomaine
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="sauvegardeDomaine")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Domaine")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domaine_original;

    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Sauvegarde", inversedBy="domaines")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $sauvegarde;

    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeDimension", mappedBy="domaine", cascade={"remove"})
     */
    private $dimensions;
    
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
        $this->dimensions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set referentiel
     *
     * @param \Pericles3Bundle\Entity\Referentiel $referentiel
     *
     * @return SauvegardeDomaine
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
     * Set sauvegarde
     *
     * @param \Pericles3Bundle\Entity\Sauvegarde $sauvegarde
     *
     * @return SauvegardeDomaine
     */
    public function setSauvegarde(\Pericles3Bundle\Entity\Sauvegarde $sauvegarde)
    {
        $this->sauvegarde = $sauvegarde;
        $sauvegarde->addDomaine($this);
        return $this;
    }

    /**
     * Get sauvegarde
     *
     * @return \Pericles3Bundle\Entity\Sauvegarde
     */
    public function getSauvegarde()
    {
        return $this->sauvegarde;
    }

    /**
     * Add dimension
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDimension $dimension
     *
     * @return SauvegardeDomaine
     */
    public function addDimension(\Pericles3Bundle\Entity\SauvegardeDimension $dimension)
    {
        $this->dimensions[] = $dimension;

        return $this;
    }

    /**
     * Remove dimension
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDimension $dimension
     */
    public function removeDimension(\Pericles3Bundle\Entity\SauvegardeDimension $dimension)
    {
        $this->dimensions->removeElement($dimension);
    }

    /**
     * Get dimensions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDimensions()
    {
        return $this->dimensions;
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
        return $this->getOrdre();
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
     * Set domaineOriginal
     *
     * @param \Pericles3Bundle\Entity\Domaine $domaineOriginal
     *
     * @return SauvegardeDomaine
     */
    public function setDomaineOriginal(\Pericles3Bundle\Entity\Domaine $domaineOriginal)
    {
        $this->domaine_original = $domaineOriginal;

        return $this;
    }

    /**
     * Get domaineOriginal
     *
     * @return \Pericles3Bundle\Entity\Domaine
     */
    public function getDomaineOriginal()
    {
        return $this->domaine_original;
    }
    

    /**
     * Get note
     *
     * @return integer
     */
    public function getNoteActuelle()
    {
           return $this->domaine_original->GetMoyenneNotes();
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
 
    public function getNextDomaine()
    {
        return $this->sauvegarde->getDomaineByOrdre($this->getOrdre()+1);
    }
    public function getPreviousDomaine()
    {
        return $this->sauvegarde->getDomaineByOrdre($this->getOrdre()-1);
    }
    
    public function getDimensionByOrdre($ordre)
    {
        foreach ($this->dimensions as $dimension)
        {
            if ($dimension->GetOrdre()==$ordre) 
            {
                return($dimension);
                break;
            }
        }
    }


    

}