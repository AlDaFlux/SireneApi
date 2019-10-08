<?php

namespace Pericles3Bundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReferentielPublic
 *
 * @ORM\Table(name="referentiel_externe_niv1")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\ReferentielExterneNiv1Repository")
 */
class ReferentielExterneNiv1
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
     * @ORM\Column(name="nom", type="string", length=510)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer")
     */
    private $ordre;
     
    
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielExterne", inversedBy="ReferentielExterneNiv1")
    * @ORM\JoinColumn(nullable=false)
    */
    private $referentielExterne;
    


        
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielExterneNiv1", inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\ReferentielExterneNiv1", mappedBy="parent")
     */
    private $children;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=10)
     */
    private $numero;
	
	






  
         
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Referentiel", mappedBy="ReferentielExterneNiv1")
     */
    private $referentiels;


    
     

          












    
    
    
    
    
 
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return trim($this->numero." ".$this->getNom());
    }

	
	
    public function numeroOrdre() 
    {
        if ($this->numero)
        {
                return($this->numero);
        }
        else
        {
                return($this->ordre);
        }
    }
    
    public function getNiveau() 
    {
	return(substr_count($this->numero,".")+1);
    }
    
    
	
	
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->referentielExterne = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ReferentielExterneNiv1
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
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return ReferentielExterneNiv1
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
     * Add referentielExterne
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterne $referentielExterne
     *
     * @return ReferentielExterneNiv1
     */
    public function addReferentielExterne(\Pericles3Bundle\Entity\ReferentielExterne $referentielExterne)
    {
        $this->referentielExterne[] = $referentielExterne;

        return $this;
    }

    /**
     * Remove referentielExterne
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterne $referentielExterne
     */
    public function removeReferentielExterne(\Pericles3Bundle\Entity\ReferentielExterne $referentielExterne)
    {
        $this->referentielExterne->removeElement($referentielExterne);
    }

    /**
     * Get referentielExterne
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentielExterne()
    {
        return $this->referentielExterne;
    }

    /**
     * Set referentielExterne
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterne $referentielExterne
     *
     * @return ReferentielExterneNiv1
     */
    public function setReferentielExterne(\Pericles3Bundle\Entity\ReferentielExterne $referentielExterne)
    {
        $this->referentielExterne = $referentielExterne;

        return $this;
    }

    /**
     * Add referentiel
     *
     * @param \Pericles3Bundle\Entity\Referentiel $referentiel
     *
     * @return ReferentielExterneNiv1
     */
    public function addReferentiel(\Pericles3Bundle\Entity\Referentiel $referentiel)
    {
        $this->referentiels[] = $referentiel;

        return $this;
    }

    /**
     * Remove referentiel
     *
     * @param \Pericles3Bundle\Entity\Referentiel $referentiel
     */
    public function removeReferentiel(\Pericles3Bundle\Entity\Referentiel $referentiel)
    {
        $this->referentiels->removeElement($referentiel);
    }

    /**
     * Get referentiels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentiels()
    {
        return $this->referentiels;
    }
 
    public function getCriteresByPublic(\Pericles3Bundle\Entity\ReferentielPublic $public)
    {
       $criteres= new \Doctrine\Common\Collections\ArrayCollection();
        
        //renvoi le critere correspondant au public
        foreach ($this->referentiels as $ref)
        {
            if ($ref->getReferentielPublic()->GetId()==$public->GetId()) {  $criteres->Add($ref); }
        }
        return ($criteres);
    }
    
 
    
 
    public function getNbCriteresByPublic(\Pericles3Bundle\Entity\ReferentielPublic $public)
    {
         return(count($this->getCriteresByPublic($public))); 
    }
    
    
    


    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return ReferentielExterneNiv1
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set parent
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterneNiv1 $parent
     *
     * @return ReferentielExterneNiv1
     */
    public function setParent(\Pericles3Bundle\Entity\ReferentielExterneNiv1 $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Pericles3Bundle\Entity\ReferentielExterneNiv1
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterneNiv1 $child
     *
     * @return ReferentielExterneNiv1
     */
    public function addChild(\Pericles3Bundle\Entity\ReferentielExterneNiv1 $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterneNiv1 $child
     */
    public function removeChild(\Pericles3Bundle\Entity\ReferentielExterneNiv1 $child)
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
}
