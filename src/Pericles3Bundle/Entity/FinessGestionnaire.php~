<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Finess
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\FinessGestionnaireRepository")
 */
class FinessGestionnaire
{
    /**
     * @var string
     *
     * @ORM\Column(name="code_finess", type="string", length=9, nullable=true)
     * @ORM\Id
     */
    private $codeFiness;

    
    
    /**
     * @var string
     *
     * @ORM\Column(name="raison_sociale", type="string", length=255, nullable=true)
     */
    private $raisonSociale;

    /**
     * @var string
     *
     * @ORM\Column(name="complement_adresse", type="string", length=255, nullable=true)
     */
    private $complementAdresse;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

 

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=5, nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;
 
    
      
 
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=15, nullable=true)
     */
    private $tel;

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Departement",  inversedBy="finess_gestionnaire")
     * @ORM\JoinColumn(name="departement_id", nullable=false)
     */
    private $departement;
    
    
        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Finess",  mappedBy="gestionnaire")
     */
    private $etablissements;
    

     /**
     * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\Gestionnaire" ,  mappedBy="finess")
    */
    private $gestionnaire;
  
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeGestionnaire", mappedBy="finess")
     */
    private $demandesGestionnaire;


    
          
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Pericles", mappedBy="finessGestionnaire")
     */
    private $pericles;
    
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etablissements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codeFiness
     *
     * @param string $codeFiness
     *
     * @return FinessGestionnaire
     */
    public function setCodeFiness($codeFiness)
    {
        $this->codeFiness = $codeFiness;

        return $this;
    }

    /**
     * Get codeFiness
     *
     * @return string
     */
    public function getCodeFiness()
    {
        return $this->codeFiness;
    }
    
    
    public function getId()
    {
        return $this->codeFiness;
    }
    
    

    /**
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return FinessGestionnaire
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raisonSociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * Set complementAdresse
     *
     * @param string $complementAdresse
     *
     * @return FinessGestionnaire
     */
    public function setComplementAdresse($complementAdresse)
    {
        $this->complementAdresse = $complementAdresse;

        return $this;
    }

    /**
     * Get complementAdresse
     *
     * @return string
     */
    public function getComplementAdresse()
    {
        return $this->complementAdresse;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return FinessGestionnaire
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return FinessGestionnaire
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return FinessGestionnaire
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return FinessGestionnaire
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }
 
    /**
     * Set departement
     *
     * @param \Pericles3Bundle\Entity\Departement $departement
     *
     * @return FinessGestionnaire
     */
    public function setDepartement(\Pericles3Bundle\Entity\Departement $departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \Pericles3Bundle\Entity\Departement
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Add etablissement
     *
     * @param \Pericles3Bundle\Entity\Finess $etablissement
     *
     * @return FinessGestionnaire
     */
    public function addEtablissement(\Pericles3Bundle\Entity\Finess $etablissement)
    {
        $this->etablissements[] = $etablissement;

        return $this;
    }

    /**
     * Remove etablissement
     *
     * @param \Pericles3Bundle\Entity\Finess $etablissement
     */
    public function removeEtablissement(\Pericles3Bundle\Entity\Finess $etablissement)
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
    
    
    
    
    public function __toString() 
    {
        return $this->codeFiness." (".$this->raisonSociale.")";
    }

    

    /**
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return FinessGestionnaire
     */
    public function setGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire = null)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \Pericles3Bundle\Entity\Gestionnaire
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }
    
    
    public function getHaveGestionnaire()
    {
        if ($this->gestionnaire) return (true);
    }
    

    /**
     * Add demandesGestionnaire
     *
     * @param \Pericles3Bundle\Entity\DemandeGestionnaire $demandesGestionnaire
     *
     * @return FinessGestionnaire
     */
    public function addDemandesGestionnaire(\Pericles3Bundle\Entity\DemandeGestionnaire $demandesGestionnaire)
    {
        $this->demandesGestionnaire[] = $demandesGestionnaire;

        return $this;
    }

    /**
     * Remove demandesGestionnaire
     *
     * @param \Pericles3Bundle\Entity\DemandeGestionnaire $demandesGestionnaire
     */
    public function removeDemandesGestionnaire(\Pericles3Bundle\Entity\DemandeGestionnaire $demandesGestionnaire)
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

    /**
     * Add pericle
     *
     * @param \Pericles3Bundle\Entity\Pericles $pericle
     *
     * @return FinessGestionnaire
     */
    public function addPericle(\Pericles3Bundle\Entity\Pericles $pericle)
    {
        $this->pericles[] = $pericle;

        return $this;
    }

    /**
     * Remove pericle
     *
     * @param \Pericles3Bundle\Entity\Pericles $pericle
     */
    public function removePericle(\Pericles3Bundle\Entity\Pericles $pericle)
    {
        $this->pericles->removeElement($pericle);
    }

    /**
     * Get pericles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPericles()
    {
        return $this->pericles;
    }
    
    public function getHasPericles()
    {
        return count($this->pericles);
    }
    
    
}
