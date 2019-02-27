<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="sauvegarde_question")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\SauvegardeQuestionRepository")
 */
class SauvegardeQuestion
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="sauvegardeQuestion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\SauvegardeCritere", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $critere;
     
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Question",inversedBy="sauvegardes")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $question_original;

    

    
   /**
     * @var int
     *
     * @ORM\Column(name="reponse", type="integer", nullable=true)
     */
    private $reponse;
    

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
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getReponse()
    {
        return $this->reponse;
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
     * @param \Pericles3Bundle\Entity\SauvegardeCritere $dimension
     *
     * @return SauvegardeQuestion
     */
    public function setCritere(\Pericles3Bundle\Entity\SauvegardeCritere $critere)
    {
        $this->critere = $critere;
        $critere->addQuestion($this);
        return $this;
    }

    /**
     * Get dimension
     *
     * @return \Pericles3Bundle\Entity\SauvegardeDimension
     */
    public function getCritere()
    {
        return $this->critere;
    }

    /**
     * Set critereOriginal
     *
     * @param \Pericles3Bundle\Entity\Critere $critereOriginal
     *
     * @return SauvegardeCritere
     */
    public function setQuestionOriginal(\Pericles3Bundle\Entity\Question $questionOriginal)
    {
        $this->question_original = $questionOriginal;
        return $this;
    }
    

    /**
     * Get critereOriginal
     *
     * @return \Pericles3Bundle\Entity\Critere
     */
    public function getQuestionOriginal()
    {
        return $this->question_original;
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
     * @return string
     */
    public function getEvolution()
    {
//        return($this->getQuestionOriginal()->getReponse()."===".$this->reponse." : ".(! ($this->getQuestionOriginal()->getReponse()===$this->reponse)));
        return(! ($this->getQuestionOriginal()->getReponse()===$this->reponse));
    }
    
    
 
      public function getGraphLegend()
    {
        return("'".$this->GetNumero()."'");
    }
    
    
    public function getSauvegarde()
    {
           return $this->GetCritere()->GetSauvegarde();
    }
    
     
    public function getRepondu()
    {
        if (is_null($this->reponse))
        {
            return(false);
        }
        else 
        {
              return(!($this->getNonConcerne()));
        }
    }
    
    
    public function getNonConcerne()
    {
        if ($this->reponse==-1) return(true);
        else return(false);
    }
        
    public function getReponseLib()
    {
        if ($this->reponse==1)
        {
            return($this->GetReferentiel()->getReponseOuiLib());
        }
        elseif ($this->reponse==0)
        {
            return($this->GetReferentiel()->getReponseNonLib());
        }
        else
        {
            return("Non concerné");
        }
    }
    
}
