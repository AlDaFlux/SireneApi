<?php

namespace Pericles3Bundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


use Doctrine\ORM\Mapping as ORM;

/**
 * DemandeEtablissement
 *
 * @ORM\Table(name="demande_etat")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DemandeEtatRepository")
 */
class DemandeEtat
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
     * @ORM\Column(name="lib", type="string", length=255)
     */
    private $lib;

    
    
    
    
    
    
       
    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeEtablissement", mappedBy="etat")
    */
    private $demandesEtablissement;

    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeInfos", mappedBy="etat")
    */
    private $demandesInfos;

         
    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeGestionnaire", mappedBy="etat")
    */
    private $demandesGestionnaire;

       
    
    
    
    
    
      function __toString() 
    {
        return($this->lib);
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
     * Set lib
     *
     * @param string $lib
     *
     * @return DemandeEtat
     */
    public function setLib($lib)
    {
        $this->lib = $lib;

        return $this;
    }

    /**
     * Get lib
     *
     * @return string
     */
    public function getLib()
    {
        return $this->lib;
    }
    
    public function IsFini()
    {
        return($this->id==3);
    }
    public function ATraiter()
    {
        return($this->id==1);
    }
    
    public function IsDevis()
    {
        return($this->id==0);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->demandesEtablissement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->demandesInfos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->demandesGestionnaire = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add demandesEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement
     *
     * @return DemandeEtat
     */
    public function addDemandesEtablissement(\Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement)
    {
        $this->demandesEtablissement[] = $demandesEtablissement;

        return $this;
    }

    /**
     * Remove demandesEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement
     */
    public function removeDemandesEtablissement(\Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement)
    {
        $this->demandesEtablissement->removeElement($demandesEtablissement);
    }

    /**
     * Get demandesEtablissement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandesEtablissement()
    {
        return $this->demandesEtablissement;
    }

    /**
     * Add demandesInfo
     *
     * @param \Pericles3Bundle\Entity\DemandeInfos $demandesInfo
     *
     * @return DemandeEtat
     */
    public function addDemandesInfo(\Pericles3Bundle\Entity\DemandeInfos $demandesInfo)
    {
        $this->demandesInfos[] = $demandesInfo;

        return $this;
    }

    /**
     * Remove demandesInfo
     *
     * @param \Pericles3Bundle\Entity\DemandeInfos $demandesInfo
     */
    public function removeDemandesInfo(\Pericles3Bundle\Entity\DemandeInfos $demandesInfo)
    {
        $this->demandesInfos->removeElement($demandesInfo);
    }

    /**
     * Get demandesInfos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandesInfos()
    {
        return $this->demandesInfos;
    }

    /**
     * Add demandesGestionnaire
     *
     * @param \Pericles3Bundle\Entity\DemandeEtat $demandesGestionnaire
     *
     * @return DemandeEtat
     */
    public function addDemandesGestionnaire(\Pericles3Bundle\Entity\DemandeEtat $demandesGestionnaire)
    {
        $this->demandesGestionnaire[] = $demandesGestionnaire;

        return $this;
    }

    /**
     * Remove demandesGestionnaire
     *
     * @param \Pericles3Bundle\Entity\DemandeEtat $demandesGestionnaire
     */
    public function removeDemandesGestionnaire(\Pericles3Bundle\Entity\DemandeEtat $demandesGestionnaire)
    {
        $this->demandesGestionnaire->removeElement($demandesGestionnaire);
    }

    /**
     * Get demandesGestionnaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandesGestionnaire()
    {
        return $this->demandesGestionnaire;
    }
}
