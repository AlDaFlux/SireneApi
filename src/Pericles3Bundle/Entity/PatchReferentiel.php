<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;


use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;

 
/**
 * Bibliotheque
 *
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\PatchReferentielRepository")
 */
class PatchReferentiel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
  

    use BlameableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Patch", inversedBy="patchReferentiels")
     */
    private $patch;
    

    
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="patchSources")
     */
    private $patcheRefSource;
    
    
    
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="patchCibles")
     */
    private $patcheRefCible;
    
    
    
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="patchAieuls")
     */
    private $patcheRefAieul;

     
    /** 
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $valide;
                 
    

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
     * Set patch
     *
     * @param \Pericles3Bundle\Entity\Patch $patch
     *
     * @return PatchReferentiel
     */
    public function setPatch(\Pericles3Bundle\Entity\Patch $patch = null)
    {
        $this->patch = $patch;

        return $this;
    }

    /**
     * Get patch
     *
     * @return \Pericles3Bundle\Entity\Patch
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * Set patcheRefSource
     *
     * @param \Pericles3Bundle\Entity\Referentiel $patcheRefSource
     *
     * @return PatchReferentiel
     */
    public function setPatcheRefSource(\Pericles3Bundle\Entity\Referentiel $patcheRefSource = null)
    {
        $this->patcheRefSource = $patcheRefSource;

        return $this;
    }

    /**
     * Get patcheRefSource
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getPatcheRefSource()
    {
        return $this->patcheRefSource;
    }

    /**
     * Set patcheRefCible
     *
     * @param \Pericles3Bundle\Entity\Referentiel $patcheRefCible
     *
     * @return PatchReferentiel
     */
    public function setPatcheRefCible(\Pericles3Bundle\Entity\Referentiel $patcheRefCible = null)
    {
        $this->patcheRefCible = $patcheRefCible;

        return $this;
    }

    /**
     * Get patcheRefCible
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getPatcheRefCible()
    {
        return $this->patcheRefCible;
    }

    /**
     * Set patcheRefAieul
     *
     * @param \Pericles3Bundle\Entity\Referentiel $patcheRefAieul
     *
     * @return PatchReferentiel
     */
    public function setPatcheRefAieul(\Pericles3Bundle\Entity\Referentiel $patcheRefAieul = null)
    {
        $this->patcheRefAieul = $patcheRefAieul;

        return $this;
    }

    /**
     * Get patcheRefAieul
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getPatcheRefAieul()
    {
        return $this->patcheRefAieul;
    }
    public function getAieul()
    {
        return $this->getPatcheRefAieul();
    }
    
    public function getSource()
    {
        return $this->getPatcheRefSource();
    }
    
    
    public function getCible()
    {
        return $this->getPatcheRefCible();
    }
    
    
        
    
    

    /**
     * Set valide
     *
     * @param boolean $valide
     *
     * @return PatchReferentiel
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return boolean
     */
    public function getValide()
    {
        return $this->valide;
    }
    
    public function getComplete()
    {
        return ($this->GetSource() && $this->GetCible());
    }
    
    public function getCompleteAndValide()
    {
        return ($this->getComplete() && $this->getValide());
    }
    
    
    
    public function __toString()
    {
        $result="";
        if ($this->getSource())$result.=$this->getSource()->GetNumero();
        $result.="-->";
        if ($this->getCible())$result.=$this->getCible()->GetNumero();
        return $result;
    }
    
    
}
