<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;


use \Datetime;


/**
 * Facture
 *
 * @ORM\Table
 * @Gedmo\Loggable
 * @ORM\Entity
 */
class FactureRappel
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateRappel;


    
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

       
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Facture", inversedBy="rappels")
     * @ORM\JoinColumn(referencedColumnName="num_facture") 
     */
    private $facture;

    
   public function __toString()
    {
        if ($this->libelle) return $this->libelle;
        else return("");
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return FactureRappel
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set facture
     *
     * @param \Pericles3Bundle\Entity\Facture $facture
     *
     * @return FactureRappel
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
     * Set dateRappel
     *
     * @param \DateTime $dateRappel
     *
     * @return FactureRappel
     */
    public function setDateRappel($dateRappel)
    {
        $this->dateRappel = $dateRappel;

        return $this;
    }

    /**
     * Get dateRappel
     *
     * @return \DateTime
     */
    public function getDateRappel()
    {
        return $this->dateRappel;
    }
}
