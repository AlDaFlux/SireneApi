<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="stockage_gestionnaire")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\StockageGestionnaireRepository")
 */
class StockageGestionnaire
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
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Gestionnaire", mappedBy="StockageGestionnaire")
     */
    private $gestionnaires;

    
     
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
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gestionnaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return StockageGestionnaire
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
     * @return StockageGestionnaire
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
     * Add gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return StockageGestionnaire
     */
    public function addGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire)
    {
        $this->gestionnaires[] = $gestionnaire;

        return $this;
    }

    /**
     * Remove gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     */
    public function removeGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire)
    {
        $this->gestionnaires->removeElement($gestionnaire);
    }

    /**
     * Get gestionnaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGestionnaires()
    {
        return $this->gestionnaires;
    }
}
