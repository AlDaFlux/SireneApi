<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="departement")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DepartementRepository")
 */
class Departement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="string", length=2)
     * @ORM\Id
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="lib_departement", type="string")
     */
    private $libelleDepartement;    
   
    
    
        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Finess", mappedBy="departement")
     */
    private $finess;
    
    
        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\FinessGestionnaire", mappedBy="departement")
     */
    private $finess_gestionnaire;
    
    
    
    
    

    
            
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", mappedBy="departement")
     */
    private $etablissements;

    
    
            
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Creai", inversedBy="departements")
     */
    private $creai;



    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Pericles", mappedBy="departement")
     */
    private $pericles;

    
    
        
    
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->finess = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Departement
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelleDepartement
     *
     * @param string $libelleDepartement
     *
     * @return Departement
     */
    public function setLibelleDepartement($libelleDepartement)
    {
        $this->libelleDepartement = $libelleDepartement;

        return $this;
    }

    /**
     * Get libelleDepartement
     *
     * @return string
     */
    public function getLibelleDepartement()
    {
        return $this->libelleDepartement;
    }

    /**
     * Add finess
     *
     * @param \Pericles3Bundle\Entity\Finess $finess
     *
     * @return Departement
     */
    public function addFiness(\Pericles3Bundle\Entity\Finess $finess)
    {
        $this->finess[] = $finess;

        return $this;
    }

    /**
     * Remove finess
     *
     * @param \Pericles3Bundle\Entity\Finess $finess
     */
    public function removeFiness(\Pericles3Bundle\Entity\Finess $finess)
    {
        $this->finess->removeElement($finess);
    }

    /**
     * Get finess
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiness()
    {
        return $this->finess;
    }
    
    public function getNbFiness()
    {
        return count($this->finess);
    }
    
    public function __toString() 
    {
        return $this->getLibelleDepartement();
    }

    /**
     * Add etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Departement
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
     * Get etablissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNbEtablissements()
    {
        return count($this->etablissements);
    }
    
     

    /**
     * Set creai
     *
     * @param \Pericles3Bundle\Entity\Creai $creai
     *
     * @return Departement
     */
    public function setCreai(\Pericles3Bundle\Entity\Creai $creai = null)
    {
        $this->creai = $creai;

        return $this;
    }

    /**
     * Get creai
     *
     * @return \Pericles3Bundle\Entity\Creai
     */
    public function getCreai()
    {
        return $this->creai;
    }

    /**
     * Add finessGestionnaire
     *
     * @param \Pericles3Bundle\Entity\FinessGestionnaire $finessGestionnaire
     *
     * @return Departement
     */
    public function addFinessGestionnaire(\Pericles3Bundle\Entity\FinessGestionnaire $finessGestionnaire)
    {
        $this->finess_gestionnaire[] = $finessGestionnaire;

        return $this;
    }

    /**
     * Remove finessGestionnaire
     *
     * @param \Pericles3Bundle\Entity\FinessGestionnaire $finessGestionnaire
     */
    public function removeFinessGestionnaire(\Pericles3Bundle\Entity\FinessGestionnaire $finessGestionnaire)
    {
        $this->finess_gestionnaire->removeElement($finessGestionnaire);
    }

    /**
     * Get finessGestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFinessGestionnaire()
    {
        return $this->finess_gestionnaire;
    }
}
