<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use \Datetime;


/**
 * Facture
 *
 * @ORM\Table
 * @ORM\Entity
 */
class FactureMoyenPaiement
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

       
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Facture", mappedBy="MoyenPaiement")
     */
    private $factures;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->factures = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return FactureMoyenPaiement
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

    
    public function __toString()
    {
        return $this->libelle;
    }
    
    /**
     * Add facture
     *
     * @param \Pericles3Bundle\Entity\Facture $facture
     *
     * @return FactureMoyenPaiement
     */
    public function addFacture(\Pericles3Bundle\Entity\Facture $facture)
    {
        $this->factures[] = $facture;

        return $this;
    }

    /**
     * Remove facture
     *
     * @param \Pericles3Bundle\Entity\Facture $facture
     */
    public function removeFacture(\Pericles3Bundle\Entity\Facture $facture)
    {
        $this->factures->removeElement($facture);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFactures()
    {
        return $this->factures;
    }
}
