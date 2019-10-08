<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Creai
 *
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\ModeCotisationRepository")
 */
class ModeCotisation
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
 
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $montantFirst;
    
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $montantRenew;
    
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", mappedBy="modeCotisation")
     */
    private $etablissements;

    
    
    
    
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
     * @return ModeCotisation
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
    
    public function __toString()
    {
        return $this->nom;
    }
    
    

    /**
     * Set montantFirst
     *
     * @param integer $montantFirst
     *
     * @return ModeCotisation
     */
    public function setMontantFirst($montantFirst)
    {
        $this->montantFirst = $montantFirst;

        return $this;
    }

    /**
     * Get montantFirst
     *
     * @return integer
     */
    public function getMontantFirst()
    {
        return $this->montantFirst;
    }

    /**
     * Set montantRenew
     *
     * @param integer $montantRenew
     *
     * @return ModeCotisation
     */
    public function setMontantRenew($montantRenew)
    {
        $this->montantRenew = $montantRenew;

        return $this;
    }

    /**
     * Get montantRenew
     *
     * @return integer
     */
    public function getMontantRenew()
    {
        return $this->montantRenew;
    }

    /**
     * Add etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return ModeCotisation
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
}
