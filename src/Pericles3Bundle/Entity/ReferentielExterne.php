<?php

namespace Pericles3Bundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReferentielPublic
 *
 * @ORM\Table(name="referentiel_externe")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\ReferentielExterneRepository")
 */
class ReferentielExterne
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
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", mappedBy="referentielExterne")
     */
    private $referentielPublic;
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\ReferentielExterneNiv1", mappedBy="referentielExterne")
     */
    private $ReferentielExterneNiv1;
    


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=510)
     */
    private $description;

    
    
    
    
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->referentielPublic = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ReferentielExterne
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
     * Add referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     *
     * @return ReferentielExterne
     */
    public function addReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        $this->referentielPublic[] = $referentielPublic;

        return $this;
    }

    /**
     * Remove referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     */
    public function removeReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        $this->referentielPublic->removeElement($referentielPublic);
    }

    /**
     * Get referentielPublic
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentielPublic()
    {
        return $this->referentielPublic;
    }
    
    
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getNom();
    }
    

    /**
     * Add referentielExterneNiv1
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterneNiv1 $referentielExterneNiv1
     *
     * @return ReferentielExterne
     */
    public function addReferentielExterneNiv1(\Pericles3Bundle\Entity\ReferentielExterneNiv1 $referentielExterneNiv1)
    {
        $this->ReferentielExterneNiv1[] = $referentielExterneNiv1;

        return $this;
    }

    /**
     * Remove referentielExterneNiv1
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterneNiv1 $referentielExterneNiv1
     */
    public function removeReferentielExterneNiv1(\Pericles3Bundle\Entity\ReferentielExterneNiv1 $referentielExterneNiv1)
    {
        $this->ReferentielExterneNiv1->removeElement($referentielExterneNiv1);
    }

    /**
     * Get referentielExterneNiv1
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentielExterneNiv1()
    {
        return $this->ReferentielExterneNiv1;
    }
    
    
    /*
    public function getReferentielExterneNiv1ByEtablissement(Etablissement $etablissement)
    {
        $referentielsExterneNiv1 =  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->ReferentielExterneNiv1 as $refExterne)
        {
            if ($refExterne->GetEtablissement()==$etablissement)
            {
                $referentielsExterneNiv1->Add();
            }
        }
        return($referentielsExterneNiv1);
    }
     * 
     */
    
    
    public function getNbDomaines()
    {
        if ($this->ReferentielExterneNiv1)
        {
            return count($this->ReferentielExterneNiv1);
        }
        else
        {
            return(0);
        }
    }
    
    
 
    public function getNbCritereRefExterneByPublic(\Pericles3Bundle\Entity\ReferentielPublic $public)
    {
        $nb=0;
        foreach ($this->ReferentielExterneNiv1 as $n1)
        {
             $nb+=$n1->getNbCriteresByPublic($public);
        }
        return($nb);
    }
    
     
    public function getNbN1ExterneByPublic(\Pericles3Bundle\Entity\ReferentielPublic $public)
    {
        $nb=0;
        foreach ($this->ReferentielExterneNiv1 as $n1)
        {
             if ($n1->getNbCriteresByPublic($public)) {$nb++;}
        }
        return($nb);
    }
     
 
    public function getReferentielExterneNiv1ByCritereRef(\Pericles3Bundle\Entity\Referentiel $critereRef)
    {
        foreach ($this->ReferentielExterneNiv1 as $n1)
        {
            foreach ($n1->getReferentiels() as $ref)
            {
                if ($ref->GetId()==$critereRef->GetId()) return $n1;

            }
        }
    }
    
     
    
    
    
    

    


    /**
     * Set description
     *
     * @param string $description
     *
     * @return ReferentielExterne
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
