<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Preuve
 *
 * @ORM\Table(name="finess_categorie")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\FinessCategorieRepository")
 */
class FinessCategorie
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
     * @ORM\Column(name="categorie_lib", type="string", length=255)
     */
    private $categorie_lib;
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Finess",  mappedBy="codeCategorie")
     */
    private $finess;

    

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="finessCategories")
     * @ORM\JoinColumn(nullable=true)
     */
    private $referentielPublicDefault;
    
 
    

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
     * Set categorieLib
     *
     * @param string $categorieLib
     *
     * @return FinessCategorie
     */
    public function setCategorieLib($categorieLib)
    {
        $this->categorie_lib = $categorieLib;

        return $this;
    }

    /**
     * Get categorieLib
     *
     * @return string
     */
    public function getCategorieLib()
    {
        return $this->categorie_lib;
    }
    
        /**
     * toString
     * @return string
     */
    public function __toString() 
    {
         return $this->categorie_lib;
    }
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->finess = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add finess
     *
     * @param \Pericles3Bundle\Entity\Finess $finess
     *
     * @return FinessCategorie
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
    
    public function getEtablissements()
    {

        $etablissements =  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getFiness() as $finess)
        {
            if ($finess->GetEtablissement())
            {
                $etablissements->Add($finess->GetEtablissement());
            }
        }
        return($etablissements);
        
    }
    
    
    public function getNbEtablissements()
    {
        return count($this->getEtablissements());
    }
    
    
    
    
    
    
    
    
    

    /**
     * Set referentielPublicDefault
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublicDefault
     *
     * @return FinessCategorie
     */
    public function setReferentielPublicDefault(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublicDefault = null)
    {
        $this->referentielPublicDefault = $referentielPublicDefault;

        return $this;
    }

    /**
     * Get referentielPublicDefault
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getReferentielPublicDefault()
    {
        return $this->referentielPublicDefault;
    }
}
