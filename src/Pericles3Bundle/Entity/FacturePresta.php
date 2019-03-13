<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use \DateTime;


/**
 * Facture
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\FacturePrestaRepository")
 */
class FacturePresta
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Facture", inversedBy="facturePrestas")
     * @ORM\JoinColumn(referencedColumnName="num_facture") 
     */
    private $facture;

    
         
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="facturePrestas")
     */
    private $etablissement;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Gestionnaire", inversedBy="facturePrestas")
     */
    private $gestionnaire;
     
      
    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montant;


    
    
     /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;


    
    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     */
    private $renouvellement;
    
    
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateFin;

    
    
    

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
     * Set montant
     *
     * @param integer $montant
     *
     * @return FacturePresta
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer
     */
    public function getMontant()
    {
        return $this->montant;
    }
    
    public function getMontantLib()
    {
        if ($this->montant) return $this->montant." €";
        else return ("OFFERT");
    }
    
    
    

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return FacturePresta
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
     * Set facture
     *
     * @param \Pericles3Bundle\Entity\Facture $facture
     *
     * @return FacturePresta
     */
    public function setFacture(\Pericles3Bundle\Entity\Facture $facture = null)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return \Pericles3Bundle\Entity\Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return FacturePresta
     */
    public function setEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement = null)
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
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return FacturePresta
     */
    public function setGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire = null)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \Pericles3Bundle\Entity\Gestionnaire
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }
    
    
    function __toString()
    {
      if ($this->getGestionnaire())
      {
          return("MODULE GESTIONNAIRE : ".$this->getGestionnaire());
      }
      else
      {
          $ret=$this->getEtablissement();
          if ($this->getEtablissement()->GetCodeFiness()) $ret.=" (".$this->getEtablissement()->GetCodeFiness().") ";
          $ret.=$this->getRenouvellementLib();
          return($ret);
      }
        
    }
    
    function getConcerne()
    {
      if ($this->getGestionnaire())
      {
          return($this->getGestionnaire());
      }
      else
      {
          return($this->getEtablissement());
      }
        
    }
    
    
    public function getConcerneTypeLib()
    {
        if ($this->getGestionnaire()) return("GESTIONNAIRE");
        else return("ETABLISSEMENT");
    }

    
    

    /**
     * Set renouvellement
     *
     * @param integer $renouvellement
     *
     * @return FacturePresta
     */
    public function setRenouvellement($renouvellement)
    {
        $this->renouvellement = $renouvellement;

        return $this;
    }

    /**
     * Get renouvellement
     *
     * @return integer
     */
    public function getRenouvellement()
    {
        return $this->renouvellement;
    }
    
    public function getRenouvellementLib()
    {
        if ($this->commentaire) return("  - ".$this->commentaire);
        elseif ($this->renouvellement) return("  - Renouvellement (".$this->renouvellement.")");
        else return(" - Premier abonnement : ");
    }
    
    public function getDateFiff2()
    {
        $diff=$this->GetDateFin()->diff($this->getDateFinCalcule());
        return($diff->format('%a'));
    }
    
    
    
    public function getDateFiff()
    {
        
        $date_creation_plus= clone $this->GetConcerne()->GetCreatedDate();
        $date_creation_plus->modify("+".$this->renouvellement." year");
        
        $diff=$date_creation_plus->diff($this->getFacture()->GetDateEmission());
        return($diff->format('%a'));
    }
    
    public function getDateSigne()
    {
        
        $date_creation_plus= clone $this->GetConcerne()->GetCreatedDate();
        $date_creation_plus->modify("+".$this->renouvellement." year");
        
        $diff=$date_creation_plus->diff($this->getFacture()->GetDateEmission());
        return($diff->format('%R'));
    }
    
    
    public function getDateEmission()
    {
    return($this->GetFacture()->getDateEmission());
    }
    
    public function GetFirstFacturePresta()
    {
        return($this->GetConcerne()->GetFirstFacturePresta());
    }
    
    
    public function getDateCreaPlus()
    {
        $date_creation_plus = clone  $this->GetConcerne()->GetFirstFacturePresta()->getDateEmission();
        $date_creation_plus->modify("+".(1+$this->renouvellement)." year");
        $date_creation_plus->modify("-1 day");
        return($date_creation_plus);
    }

    
    public function getDateFinCalcule()
    {
        if ($this->GetConcerne()->GetFirstFacturePresta())
        {
            $date_creation_plus = clone  $this->GetFirstFacturePresta()->getDateDebut();
        }
        else
        {
            $date_creation_plus = new DateTime();
        }
        $date_creation_plus->modify("+".(1+$this->renouvellement)." year");
        $date_creation_plus->modify("-1 day");
        return($date_creation_plus);
    }

    
    
    
    

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return FacturePresta
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    
    
    
    public function getDateDebut()
    {
        $date_debut = clone  $this->getDateFin();
        $date_debut ->modify("-1 year");
        $date_debut ->modify("+1 day");
        return($date_debut );
    }
    
      
    
    public function getDateFinLointaine()
    {
        $datet = new DateTime();
        $datet ->modify("+1 year");
        return($this->getDateFin()>$datet );
    }
    

    
    
    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
    
    public function getValiditeDateLib()
    {
        return (" - Valable du ".$this->getDateDebut()->format('d/m/Y')." au ".$this->getDateFin()->format('d/m/Y'));
    }
    
        
    
    function isLastFacturePresta()
    {
        return($this==$this->GetLastFacturePresta());
    }
    
    
    function GetLastFacturePresta()
    {
        return($this->getConcerne()->GetLastFacturePresta());
    }
    

    
}
