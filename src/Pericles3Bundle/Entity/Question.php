<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

use Gedmo\Mapping\Annotation as Gedmo;



/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\QuestionRepository")
 * @Gedmo\Loggable
 */
class Question
{
    use SoftDeleteableEntity;
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="reponse", type="integer", nullable=true)
     */
    private $reponse;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Critere",inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $critere;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel",inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;

    
        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeQuestion",mappedBy="question_original")
     */
    private $sauvegardes;


    
    

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
     * Set reponse
     *
     * @param int $reponse
     *
     * @return Question
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return int
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    public function setCritere(Critere $critere)
    {
        $this->critere = $critere;

        return $this;
    }

    public function getCritere()
    {
        return $this->critere;
    }
    
    public function setReferentiel(Referentiel $referentiel)
    {
    	$this->referentiel = $referentiel;
    
    	return $this;
    }
    
    public function getReferentiel()
    {
    	return $this->referentiel;
    }
    
    public function getEtablissement()
    {
        return $this->GetCritere()->getEtablissement();
    }
    
    
    public function getReferentielPublic()
    {
    	return $this->getReferentiel()->getReferentielPublic();
    }

    
    
    /**
     * Renvoi vrai si la question à une réponse est vrai ou fausse
     *
     * @return bool
     */
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

     
    /**
     * Renvoi vrai si la question à une réponse est vrai ou fausse
     *
     * @return bool
     */
    public function getARepondre()
    {
        if (is_null($this->reponse))
        {
              return(true);
        }
        else 
        {
            return(false);
        }
    }
    

    /**
     * Renvoi vrai si la question à une réponse
     *
     * @return bool
     */
    public function getReponseLib()
    {
        if ($this->reponse==1)
        {
            return($this->GetReferentiel()->getReponseOuiLib());
        }
        elseif ($this->reponse===0)
        {
            return($this->GetReferentiel()->getReponseNonLib());
        }
        elseif ($this->reponse===null)
        {
            return("Non répondu");
        }
        else
        {
            return("Non concerné");
        }
    }
    
     public function getReponseLibCourt()
    {
        if ($this->reponse==1)
        {
            return("OUI");
        }
        elseif ($this->reponse===0)
        {
            return("NON");
        }
        elseif ($this->reponse===null)
        {
            return("-");
        }
        else
        {
            return("NC");
        }
    }
    
    public function getCss()
    {
        return("reponse_".strtolower($this->getReponseLibCourt()));
    }
    
    
    
    
    
    
    public function getOrdre()
    {
        return($this->referentiel->getOrdre());
    }



        
    /**
     * Renvoi vrai si la question est non concerné
     *
     * @return int
     */
    public function getNonConcerne()
    {
        
        if ($this->reponse==-1) return(true);
        else return(false);
    }
        
    /**
     * Renvoi vrai si la question est non concernable
     *
     * @return bool
     */
    public function getNonConcernable()
    {
        return($this->GetReferentiel()->getNonConcerne());
    }


    
       
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->GetReferentiel()->getNom();
    }
    
    
    
    
    public function GetEvalSource()
    {
        $Source=$this->getReferentiel()->getSourceParent();
        if ($Source) return($this->getReferentiel()->GetSourceParent()->GetQuestionEtablissement($this->getEtablissement()));
    }


    public function GetNumero() 
    {
        return $this->getCritere()->GetNumero().".".$this->GetOrdre();
    }       
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sauvegardes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sauvegarde
     *
     * @param \Pericles3Bundle\Entity\SauvegardeQuestion $sauvegarde
     *
     * @return Question
     */
    public function addSauvegarde(\Pericles3Bundle\Entity\SauvegardeQuestion $sauvegarde)
    {
        $this->sauvegardes[] = $sauvegarde;

        return $this;
    }

    /**
     * Remove sauvegarde
     *
     * @param \Pericles3Bundle\Entity\SauvegardeQuestion $sauvegarde
     */
    public function removeSauvegarde(\Pericles3Bundle\Entity\SauvegardeQuestion $sauvegarde)
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
    
    function IsObsolete()
    {
        return ($this->getCritere()->IsOboslete());
    }
    
    

    
}
