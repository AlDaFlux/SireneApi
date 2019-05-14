<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;




/**
 * Creai
 *
 * @ORM\Table 
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\PatchToDoRepository")
 */
class PatchToDo
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement",inversedBy="patchToDo")
     */
    private $etablissement;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->codeRetour = 0;
        $this->memoryUsage = 0;
    }
    
    
    
    public function __toString() 
    {
        return("->".$this->getPatch()->getCible());
    }
    
    

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Patch",inversedBy="patchToDo")
     */
    private $patch;

    use BlameableEntity;
    use TimestampableEntity;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDebutPatch;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFinPatch;


    
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $memoryUsage;
     
    
    
        

    
    
    

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
     * Set dateDebutPatch
     *
     * @param string $dateDebutPatch
     *
     * @return PatchToDo
     */
    public function setDateDebutPatch($dateDebutPatch)
    {
        $this->dateDebutPatch = $dateDebutPatch;

        return $this;
    }

    /**
     * Get dateDebutPatch
     *
     * @return string
     */
    public function getDateDebutPatch()
    {
        return $this->dateDebutPatch;
    }

    /**
     * Set dateFinPatch
     *
     * @param string $dateFinPatch
     *
     * @return PatchToDo
     */
    public function setDateFinPatch($dateFinPatch)
    {
        $this->dateFinPatch = $dateFinPatch;

        return $this;
    }

    /**
     * Get dateFinPatch
     *
     * @return string
     */
    public function getDateFinPatch()
    {
        return $this->dateFinPatch;
    }
    
    
    function getDuree()
    {
        if ($this->GetFini())
        {
            $interval =  $this->dateFinPatch->diff($this->dateDebutPatch);
            return($interval);        
            //return($interval->format('%i minutes  %S secondes'));        
        }
    }
    
    

    /**
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return PatchToDo
     */
    public function setEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement = null)
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
     * Set patch
     *
     * @param \Pericles3Bundle\Entity\Patch $patch
     *
     * @return PatchToDo
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
    
    
    
    public function getCible()
    {
        return $this->getPatch()->GEtCible();
    }

    
    
    
    public function getEtat()
    {
        if ($this->getDateDebutPatch())
        {
            if ($this->getDateFinPatch()) { return("Fini"); }
            else { return("En cours"); }
        }
        else { return("A faire"); }
    }
    
    public function getEtatClass()
    {
        if ($this->getDateDebutPatch())
        {
            if ($this->getDateFinPatch()) { return("success"); }
            else { return("warning"); }
        }
        else { return("danger"); }
    }
    
    
    
    public function getAFaire()
    {
        return(! $this->getDateDebutPatch());
    }
    
    
    public function getEnCours()
    {
        return($this->getDateDebutPatch() && ! $this->getDateFinPatch());
    }
    
    
    
    
    
    
    public function getFini()
    {
        return($this->getDateDebutPatch() && $this->getDateFinPatch());
    }
    
    
    
    
    
    

    /**
     * Set memoryUsage
     *
     * @param integer $memoryUsage
     *
     * @return PatchToDo
     */
    public function setMemoryUsage($memoryUsage)
    {
        $this->memoryUsage = $memoryUsage;
        return $this;
    }

    public function addMemoryUsage($memoryUsage)
    {
        $this->memoryUsage = $this->memoryUsage + $memoryUsage;
        return $this;
    }

    /**
     * Get memoryUsage
     *
     * @return integer
     */
    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }
}
