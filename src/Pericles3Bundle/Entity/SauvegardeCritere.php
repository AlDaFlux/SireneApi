<?php

namespace Pericles3Bundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="sauvegarde_critere")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\SauvegardeCritereRepository")
 */
class SauvegardeCritere
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="sauvegardeCritere")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;


    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\SauvegardeDimension", inversedBy="criteres")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $dimension;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Critere", inversedBy="sauvegardes")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $critere_original;

    

    
   /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;
    
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeQuestion", mappedBy="critere", cascade={"remove"})
     */
    private $questions;
    
        

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
     * @return SauvegardeCritere
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
     * @return SauvegardeCritere
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
     * Set dimension
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDimension $dimension
     *
     * @return SauvegardeCritere
     */
    public function setDimension(\Pericles3Bundle\Entity\SauvegardeDimension $dimension)
    {
        $this->dimension = $dimension;
        $dimension->addCritere($this);
        return $this;
    }

    /**
     * Get dimension
     *
     * @return \Pericles3Bundle\Entity\SauvegardeDimension
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Set critereOriginal
     *
     * @param \Pericles3Bundle\Entity\Critere $critereOriginal
     *
     * @return SauvegardeCritere
     */
    public function setCritereOriginal(\Pericles3Bundle\Entity\Critere $critereOriginal)
    {
        $this->critere_original = $critereOriginal;

        return $this;
    }

    /**
     * Get critereOriginal
     *
     * @return \Pericles3Bundle\Entity\Critere
     */
    public function getCritereOriginal()
    {
        return $this->critere_original;
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
        return $this->getDimension()->GetNumero().".".$this->GetOrdre();
        
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
           return $this->critere_original->GetNote();
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
 
 
      public function getGraphLegend()
    {
        return("'".$this->GetNumero()."'");
    }
    
    
    public function getSauvegarde()
    {
           return $this->GetDimension()->GetSauvegarde();
    }
    
    
    public function getNextCritere()
    {
        return $this->dimension->getCritereByOrdre($this->getOrdre()+1);
    }
    public function getPreviousCritere()
    {
        return $this->dimension->getCritereByOrdre($this->getOrdre()-1);
    }
    
     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add question
     *
     * @param \Pericles3Bundle\Entity\SauvegardeQuestion $question
     *
     * @return SauvegardeCritere
     */
    public function addQuestion(\Pericles3Bundle\Entity\SauvegardeQuestion $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \Pericles3Bundle\Entity\SauvegardeQuestion $question
     */
    public function removeQuestion(\Pericles3Bundle\Entity\SauvegardeQuestion $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }
    
       
    
    
    public function getNbQuestionsRepondues()
    {
        $total=0;
        foreach ($this->questions as $question)
        {
            if ($question->getRepondu()) $total++;
        }
        return $total;
    }
    
    
    
}
