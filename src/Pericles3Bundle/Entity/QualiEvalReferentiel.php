<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Gedmo\Mapping\Annotation as Gedmo;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;





/**
 * Referentiel
 *
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\QualiEvalReferentielRepository")
 * @Gedmo\Loggable
 */
class QualiEvalReferentiel
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
     * @Gedmo\Versioned
     * 
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "La question ne peut dépasser {{ limit }} caracteres de long",
     * )
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     */
    private $ordre;
            

            
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\QualiEvalReferentiel", inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\QualiEvalReferentiel", mappedBy="parent")
     */
    private $children;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\QualiEvalNiveau",inversedBy="QEReferentiel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="QEReferentielAdulte")
     */
    private $referentielAdulte;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="QEReferentielEnfant")
     */
    private $referentielEnfant;
    
    
    
    
    
    

    
    
            
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return QualiEvalReferentiel
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
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return QualiEvalReferentiel
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set parent
     *
     * @param \Pericles3Bundle\Entity\Referentiel $parent
     *
     * @return QualiEvalReferentiel
     */
    public function setParent(\Pericles3Bundle\Entity\QualiEvalReferentiel $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Pericles3Bundle\Entity\Referentiel $child
     *
     * @return QualiEvalReferentiel
     */
    public function addChild(\Pericles3Bundle\Entity\QualiEvalReferentiel $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Pericles3Bundle\Entity\Referentiel $child
     */
    public function removeChild(\Pericles3Bundle\Entity\QualiEvalReferentiel $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
    
     public function getNbChildren()
    {
        return count($this->children);
    }
    
    
    

    /**
     * Set niveau
     *
     * @param \Pericles3Bundle\Entity\QualiEvalNiveau $niveau
     *
     * @return QualiEvalReferentiel
     */
    public function setNiveau(\Pericles3Bundle\Entity\QualiEvalNiveau $niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return \Pericles3Bundle\Entity\QualiEvalNiveau
     */
    public function getNiveau()
    {
        return $this->niveau;
    }
    
    
    
    /**
     * Get ordre
     *
     * @return integer
     */
    public function getNumero()
    {
        if ($this->GetParent())
        {
            return($this->GetParent()->getNumero().".".$this->ordre);
        }
        else
        {
            return $this->ordre;
        }
    }
    
    
    
    
    
    

    /**
     * Set referentielAdulte
     *
     * @param \Pericles3Bundle\Entity\Referentiel $referentielAdulte
     *
     * @return QualiEvalReferentiel
     */
    public function setReferentielAdulte(\Pericles3Bundle\Entity\Referentiel $referentielAdulte = null)
    {
        $this->referentielAdulte = $referentielAdulte;

        return $this;
    }

    /**
     * Get referentielAdulte
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getReferentielAdulte()
    {
        return $this->referentielAdulte;
    }

    /**
     * Set referentielEnfant
     *
     * @param \Pericles3Bundle\Entity\Referentiel $referentielEnfant
     *
     * @return QualiEvalReferentiel
     */
    public function setReferentielEnfant(\Pericles3Bundle\Entity\Referentiel $referentielEnfant = null)
    {
        $this->referentielEnfant = $referentielEnfant;

        return $this;
    }

    /**
     * Get referentielEnfant
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getReferentielEnfant()
    {
        return $this->referentielEnfant;
    }
    
    
    
    
    
    
}
