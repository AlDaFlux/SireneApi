<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="stockage_etablissement")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\StockageEtablissementRepository")
 */
class StockageEtablissement
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
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", mappedBy="StockageEtablissement")
     */
    private $etablissements;

    
          
    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montant;


    
    
    
    
     
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="capacite", type="bigint")
     */
    private $capacite;

    
    public function __toString()
    {
        return $this->getNom();
//        if ($this->montant) return $this->getNom()." (".$this->montant."€)";
            
    }

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etablissements = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return StockageEtablissement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set capacite
     *
     * @param integer $capacite
     *
     * @return StockageEtablissement
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * Get capacite
     *
     * @return integer
     */
    public function getCapacite()
    {
        return $this->capacite;
    }

    /**
     * Add etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return StockageEtablissement
     */
    public function addEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->etablissements[] = $etablissement;

        return $this;
    }

    /**
     * Remove etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     */
    public function removeEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->etablissements->removeElement($etablissement);
    }

    /**
     * Get etablissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtablissements()
    {
        return $this->etablissements;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return StockageEtablissement
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
}