<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="domaine_externe")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DomaineExterneRepository")
 */
class DomaineExterne
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="domainesExterne")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etablissement;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielExterneNiv1")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentielExterneN1;
                
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Critere", mappedBy="domaineExterne")
     */
    private $criteres;

    
    
    
    
     
    /**
     * toString
     * @return string
   
    public function __toString() 
    {
        return $this->getNom();
    }
    */
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->criteres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return DomaineExterne
     */
    public function setEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \Pericles3Bundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set referentielExterneN1
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterneNiv1 $referentielExterneN1
     *
     * @return DomaineExterne
     */
    public function setReferentielExterneN1(\Pericles3Bundle\Entity\ReferentielExterneNiv1 $referentielExterneN1)
    {
        $this->referentielExterneN1 = $referentielExterneN1;

        return $this;
    }

    /**
     * Get referentielExterneN1
     *
     * @return \Pericles3Bundle\Entity\ReferentielExterneNiv1
     */
    public function getReferentielExterneN1()
    {
        return $this->referentielExterneN1;
    }

    /**
     * Add critere
     *
     * @param \Pericles3Bundle\Entity\Critere $critere
     *
     * @return DomaineExterne
     */
    public function addCritere(\Pericles3Bundle\Entity\Critere $critere)
    {
        $this->criteres[] = $critere;

        return $this;
    }

    /**
     * Remove critere
     *
     * @param \Pericles3Bundle\Entity\Critere $critere
     */
    public function removeCritere(\Pericles3Bundle\Entity\Critere $critere)
    {
        $this->criteres->removeElement($critere);
    }

    /**
     * Get criteres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriteres()
    {
        return $this->criteres;
    }

    public function getNbCriteres()
    {
        return count($this->criteres);
    }
    
    
    function hasOnlyObsolete()
    {
        if ($this->getNbCriteres()==0) { return(true); }
        $hasObsolete=false;
        foreach ($this->criteres as $critere)
        {
            if ($critere->getReferentielPublic() <> $critere->getEtablissement()->getReferentielPublic()) { $hasObsolete=true; }
        }
        return($hasObsolete);
    }
     
    
    
        /**
     * Get Moyennes criteres 
     *
     * @return integer
     */
    public function getMoyenneNotes(){
        $moyenne = 0;
        $nb_criteres=0;
        $criteres = $this->getCriteres();
        foreach ($criteres as $critere ) {
            if ( ! ($critere->getNote()==-1)) // non concerné
            {
                $nb_criteres++;
                $moyenne+=$critere->getNote();
            }
        }
        
        if ($nb_criteres)
        {
            $moyenne=$moyenne/count($criteres);
            return round($moyenne, 1);
        }
        else
        {
            return(0);
        }
    }
    
    
    public function getOrdre()
    {
        return($this->referentielExterneN1->getOrdre());
    }
      

    
    public function getNom()
    {
        return($this->referentielExterneN1->getNom());
    }

    public function getGraphLegend()
    {
        return($this->getOrdre());
    }
    
    public function getGraphData()
    {
        return($this->getMoyenneNotes());
    }

        

    
    
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getNom();
    }
    
        
    
    
}
