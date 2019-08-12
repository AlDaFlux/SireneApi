<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;


use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;


/**
 * Bibliotheque
 *
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\PatchRepository")
 */
class Patch
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
    use TimestampableEntity;

    
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="patchSources")
     */
    private $patcheRefPublicSource;
    
    
    
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="patchCibles")
     */
    private $patcheRefPublicCible;
    

    
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic")
     */
    private $patcheRefPublicAieul;
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\PatchReferentiel", mappedBy="patch")
     */
    private $patchReferentiels;
    
    
        
    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\Patch", inversedBy="patchsIntermediares")
     */
    private $patchParent;

    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\Patch", mappedBy="patchParent")
     */
    private $patchsIntermediares;
    
    
    
    

    
    
    
 
    /** 
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $fini;

    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", mappedBy="patch")
     */
    private $etablissements;
    

    
    
      
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\PatchToDo",mappedBy="patch")
     */
    private $patchToDo;

    
 
    

    

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
     * Set patcheRefPublicSource
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $patcheRefPublicSource
     *
     * @return Patch
     */
    public function setPatcheRefPublicSource(\Pericles3Bundle\Entity\ReferentielPublic $patcheRefPublicSource = null)
    {
        $this->patcheRefPublicSource = $patcheRefPublicSource;

        return $this;
    }

    /**
     * Get patcheRefPublicSource
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getPatcheRefPublicSource()
    {
        return $this->patcheRefPublicSource;
    }

    /**
     * Set patcheRefPublicCible
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $patcheRefPublicCible
     *
     * @return Patch
     */
    public function setPatcheRefPublicCible(\Pericles3Bundle\Entity\ReferentielPublic $patcheRefPublicCible = null)
    {
        $this->patcheRefPublicCible = $patcheRefPublicCible;

        return $this;
    }

    /**
     * Get patcheRefPublicCible
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getPatcheRefPublicCible()
    {
        return $this->patcheRefPublicCible;
    }
    
    
    public function getCible()
    {
        return $this->patcheRefPublicCible;
    }
    public function getAieul()
    {
        return $this->patcheRefPublicAieul;
    }
    public function getSource()
    {
        return $this->patcheRefPublicSource;
    }

     
    
    public function getPatcheRefPublicByRefCible(Referentiel $referentielCible)
    {
        foreach ($this->getPatchReferentiels() as $patchReferentiel)
        {
            if ($patchReferentiel->getCible()==$referentielCible)
            {
                return($patchReferentiel);
            }
        }
    }
    
    
    public function getPatcheRefPublicByRefSource(Referentiel $referentielSource)
    {
        foreach ($this->getPatchReferentiels() as $patchReferentiel)
        {
            if ($patchReferentiel->getSource()==$referentielSource)
            {
                return($patchReferentiel);
            }
        }
    }
    
    
    public function getDeplace(Referentiel $referentielCible)
    {
        $patchRef=$this->getPatcheRefPublicByRefCible($referentielCible);
         
        if ($patchRef->GetSource())
        {
            $patchRefParent=$this->getPatcheRefPublicByRefSource($patchRef->GetSource()->GetParent());
            if ($patchRef->GetCible()->GetParent() != $patchRefParent->GetCible() )
            {
                return ($patchRefParent->GetSource());
            }
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function getReferentielSourceFromCible(Referentiel $referentiel)
    {

        foreach ($this->getPatchReferentiels() as $patchReferentiel)
        {
            if ($patchReferentiel->getCible()==$referentiel)
            {
                return($patchReferentiel->getSource());
            }
        }
    }


    public function getQuestionsDeletedFromCritereRefSource(Referentiel $reCritereSource)
    {
        $QuestionsDeleted = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getPatchReferentiels() as $patchReferentiel)
        {
            if ($patchReferentiel->getSource())
            {
                if ($patchReferentiel->getSource()->getParent()==$reCritereSource)
                {
                    if (! $patchReferentiel->getCible())
                    {
                        $QuestionsDeleted->Add($patchReferentiel->getSource());
                    }
                }
            }
        }
        /*        
                foreach ($this->patchSources as $patch)  { $refCibles->add($patch->getCible()); }
         */
        return($QuestionsDeleted );
    }
    

    public function getQuestionsDeplaceFromCritereRefSource(Referentiel $reCritereSource)
    {
        $QuestionsDeplace = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getPatchReferentiels() as $patchReferentiel)
        {
            if ($patchReferentiel->getSource()) // pour eviter le null
            {
                if ($patchReferentiel->getSource()->getParent()==$reCritereSource) //
                {
                    if ($patchReferentiel->getCible())
                    {
                        if ($patchReferentiel->getCible()->getParent()->GetNumero() != $reCritereSource->GetNumero())
                        {
                            $QuestionsDeplace->Add($patchReferentiel);
                        }
                    }
                }
            }
        } 
        return($QuestionsDeplace);
    }
    
    
    
    


    
    
    public function __toString()
    {
        return $this->getSource()."->".$this->getCible();
    }


    
    
    /**
     * Set patcheRefPublicAieul
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $patcheRefPublicAieul
     *
     * @return Patch
     */
    public function setPatcheRefPublicAieul(\Pericles3Bundle\Entity\ReferentielPublic $patcheRefPublicAieul = null)
    {
        $this->patcheRefPublicAieul = $patcheRefPublicAieul;

        return $this;
    }

    /**
     * Get patcheRefPublicAieul
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getPatcheRefPublicAieul()
    {
        return $this->patcheRefPublicAieul;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->patchReferentiels = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add patchReferentiel
     *
     * @param \Pericles3Bundle\Entity\PatchReferentiel $patchReferentiel
     *
     * @return Patch
     */
    public function addPatchReferentiel(\Pericles3Bundle\Entity\PatchReferentiel $patchReferentiel)
    {
        $this->patchReferentiels[] = $patchReferentiel;

        return $this;
    }

    /**
     * Remove patchReferentiel
     *
     * @param \Pericles3Bundle\Entity\PatchReferentiel $patchReferentiel
     */
    public function removePatchReferentiel(\Pericles3Bundle\Entity\PatchReferentiel $patchReferentiel)
    {
        $this->patchReferentiels->removeElement($patchReferentiel);
    }

    /**
     * Get patchReferentiels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatchReferentiels()
    {
        return $this->patchReferentiels;
    }

    
    
    
    
    public function getNbPatcheRefCible()
    {
        $nb=0;
        foreach ($this->getPatchReferentiels() as $ref)
        {
            if ($ref->getCible()) $nb++;
        }
        return ($nb);
    }
    
    
    public function getNbPatcheRefSource()
    {
        $nb=0;
        foreach ($this->getPatchReferentiels() as $ref)
        {
            if ($ref->getSource()) $nb++;
        }
        return ($nb);
    }
    
    
    

    
    
    /**
     * Set fini
     *
     * @param boolean $fini
     *
     * @return Patch
     */
    public function setFini($fini)
    {
        $this->fini = $fini;

        return $this;
    }

    /**
     * Get fini
     *
     * @return boolean
     */
    public function getFini()
    {
        return $this->fini;
    }
    
    
    

    /**
     * Add etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Patch
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
    
    
    public function getNbEtablissements()
    {
        return count($this->etablissements);
    }
 

    /**
     * Add patchParent
     *
     * @param \Pericles3Bundle\Entity\Patch $patchParent
     *
     * @return Patch
     */
    public function addPatchParent(\Pericles3Bundle\Entity\Patch $patchParent)
    {
        $this->patchParent[] = $patchParent;

        return $this;
    }

    /**
     * Remove patchParent
     *
     * @param \Pericles3Bundle\Entity\Patch $patchParent
     */
    public function removePatchParent(\Pericles3Bundle\Entity\Patch $patchParent)
    {
        $this->patchParent->removeElement($patchParent);
    }

    /**
     * Get patchParent
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatchParent()
    {
        return $this->patchParent;
    }

    /**
     * Add patchsIntermediare
     *
     * @param \Pericles3Bundle\Entity\Patch $patchsIntermediare
     *
     * @return Patch
     */
    public function addPatchsIntermediare(\Pericles3Bundle\Entity\Patch $patchsIntermediare)
    {
        $this->patchsIntermediares[] = $patchsIntermediare;
        $patchsIntermediare->addPatchParent($this);
        return $this;
    }
    
    

    /**
     * Remove patchsIntermediare
     *
     * @param \Pericles3Bundle\Entity\Patch $patchsIntermediare
     */
    public function removePatchsIntermediare(\Pericles3Bundle\Entity\Patch $patchsIntermediare)
    {
        $this->patchsIntermediares->removeElement($patchsIntermediare);
    }

    /**
     * Get patchsIntermediares
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatchsIntermediares()
    {
        return $this->patchsIntermediares;
    }
    
    public function getNbPatchsIntermediares()
    {
        return count($this->patchsIntermediares);
    }
    
    public function getHasPatchsIntermediares()
    {
        return count($this->patchsIntermediares);
    }
    
    
    public function getPatchsIntermediaresOrder()
    {
        $patchsIntermediaresSorted = new \Doctrine\Common\Collections\ArrayCollection();

        $cible=$this->GetSource();
        
        for ($i=1;$i<=count($this->patchsIntermediares);$i++)
        {
            foreach ($this->patchsIntermediares as $interPatch)
            {
                if ($interPatch->GetSource()==$cible) 
                {
                    $patchsIntermediaresSorted->Add($interPatch);
                    $cible=$interPatch->GetCible();
                    break;
                }
            }
        }
        return $patchsIntermediaresSorted;
    }
    public function getPatchsIntermediaresRefCibleOrder()
    {
        $patchsIntermediaresRefcible = new \Doctrine\Common\Collections\ArrayCollection();
        if ($this->getIntermediareOk()) $i=1;
        else $i=0;
        
        $interPatches=$this->getPatchsIntermediaresOrder();
        foreach ($interPatches as $interPatch)
        {
            if (count($interPatches)==$i) break;
                
            $patchsIntermediaresRefcible->Add($interPatch->getCible());
            $i++;
        }
        return $patchsIntermediaresRefcible;
    }
    
    public function getLastPatchsIntermediare()
    {
        return($this->getPatchsIntermediaresOrder()[$this->getNbPatchsIntermediares()-1]);
    }
    
    public function getIntermediareOk()
    {
        if ($this->getLastPatchsIntermediare())
        {
            return($this->getLastPatchsIntermediare()->getCible()==$this->getCible());
        }
    }



    public function getPatchRefCibleByInter(Referentiel $referentielSource)
    {
 
        foreach ($this->getPatchsIntermediaresOrder() as $interPatch)
        {
            $referentielSource=$interPatch->getPatcheRefPublicByRefSource($referentielSource)->GetCible();
            if (! $referentielSource) { break; }
        }
        
        if ($referentielSource) { return($referentielSource); }
    }
        
    
    
    
    function getStats()
    {
        $stats["OK"]=0;
        $stats["cible_verifie"]=0;
        $stats["cible_a_verifie"]=0;
        $stats["source_verifie"]=0;
        $stats["source_a_verifie"]=0;
        
        foreach ($this->getPatchReferentiels() as $ref)
        {
            if ($ref->GetCible() && $ref->GetSource())
            {
                $stats["OK"]++;
            }
            elseif ($ref->GetCible())
            {
                if ($ref->getValide())
                {
                    $stats["cible_verifie"]++;
                }
                else
                {
                    $stats["cible_a_verifie"]++;
                }
            }
            elseif ($ref->GetSource())
            {
                if ($ref->getValide())
                {
                    $stats["source_verifie"]++;
                }
                else
                {
                    $stats["source_a_verifie"]++;
                }
            }
        }
        return($stats);
    }
    
    
    
    

    /**
     * Add patchToDo
     *
     * @param \Pericles3Bundle\Entity\PatchToDo $patchToDo
     *
     * @return Patch
     */
    public function addPatchToDo(\Pericles3Bundle\Entity\PatchToDo $patchToDo)
    {
        $this->patchToDo[] = $patchToDo;

        return $this;
    }

    /**
     * Remove patchToDo
     *
     * @param \Pericles3Bundle\Entity\PatchToDo $patchToDo
     */
    public function removePatchToDo(\Pericles3Bundle\Entity\PatchToDo $patchToDo)
    {
        $this->patchToDo->removeElement($patchToDo);
    }

    /**
     * Get patchToDo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatchToDo()
    {
        return $this->patchToDo;
    }
}
