<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Finess
 *
 * @ORM\Table(name="finess", uniqueConstraints={@ORM\UniqueConstraint(name="code_finess_2", columns={"code_finess"})}, indexes={@ORM\Index(name="code_finess", columns={"code_finess"})})
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\FinessRepository")
 */
class Finess
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\FinessCategorie",  inversedBy="finess")
     * @ORM\JoinColumn(name="code_categorie", nullable=true)
     */
    private $codeCategorie;

    
    
    /**
     * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\Etablissement" ,  mappedBy="finess")
    */
    private $etablissement;
    

    
    /**
     * @var integer
     *
     * @ORM\Column(name="capacite_totale1", type="integer", nullable=true)
     */
    private $capaciteTotale1;

 
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=15, nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=15, nullable=true)
     */
    private $fax;
    
    
        
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Departement",  inversedBy="finess")
     * @ORM\JoinColumn(name="departement_id", nullable=false)
     */
    private $departement;
    

    
    
    
    /**
     * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\DemandeEtablissement", mappedBy="finess")
     */
    private $demandesEtablissement;
    
    
    

    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\FinessGestionnaire",  inversedBy="etablissements")
     * @ORM\JoinColumn(referencedColumnName="code_finess", nullable=true) 
     */
    private $gestionnaire;
    
    
       
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Pericles", mappedBy="finessEtablissement")
     */
    private $pericles;
    
 
    
    
    
    
    
    
       /**
     * Get libCategorie
     *
     * @return string
     */
    public function getLibCategorie()
    {
        return $this->codeCategorie;
    }
    
    public function getReferentielPublicDefaut()
    {
        
        return $this->codeCategorie->getReferentielPublicDefault();
    }
    
    
    
    
       
    

    /**
     * Set codeFiness
     *
     * @param string $codeFiness
     *
     * @return Finess
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

    /**
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return Finess
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
     * @return Finess
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
     * @return Finess
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
     * @return Finess
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
     * @return Finess
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
     * Set capaciteTotale1
     *
     * @param integer $capaciteTotale1
     *
     * @return Finess
     */
    public function setCapaciteTotale1($capaciteTotale1)
    {
        $this->capaciteTotale1 = $capaciteTotale1;

        return $this;
    }

    /**
     * Get capaciteTotale1
     *
     * @return integer
     */
    public function getCapaciteTotale1()
    {
        return $this->capaciteTotale1;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->codeFiness;
    }

    /**
     * Set codeCategorie
     *
     * @param \Pericles3Bundle\Entity\FinessCategorie $codeCategorie
     *
     * @return Finess
     */
    public function setCodeCategorie(\Pericles3Bundle\Entity\FinessCategorie $codeCategorie = null)
    {
        $this->codeCategorie = $codeCategorie;

        return $this;
    }

    /**
     * Get codeCategorie
     *
     * @return \Pericles3Bundle\Entity\FinessCategorie
     */
    public function getCodeCategorie()
    {
        return $this->codeCategorie;
    }
 
 
    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Finess
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
     * Set fax
     *
     * @param string $fax
     *
     * @return Finess
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }
    
          
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->codeFiness;
    }
    
     
            
            

    /**
     * Set departement
     *
     * @param \Pericles3Bundle\Entity\Departement $departement
     *
     * @return Finess
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
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Finess
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
    
    
    public function getHaveEtablissement()
    {
        if ($this->getEtablissement()) return (true);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->demandesEtablissement = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    
    

    /**
     * Set demandesEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement
     *
     * @return Finess
     */
    public function setDemandesEtablissement(\Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement = null)
    {
        $this->demandesEtablissement = $demandesEtablissement;

        return $this;
    }

    /**
     * Get demandesEtablissement
     *
     * @return \Pericles3Bundle\Entity\DemandeEtablissement
     */
    public function getDemandesEtablissement()
    {
        return $this->demandesEtablissement;
    }

    /**
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\FinessGestionnaire $gestionnaire
     *
     * @return Finess
     */
    public function setGestionnaire(\Pericles3Bundle\Entity\FinessGestionnaire $gestionnaire = null)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \Pericles3Bundle\Entity\FinessGestionnaire
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }

    /**
     * Add pericle
     *
     * @param \Pericles3Bundle\Entity\Pericles $pericle
     *
     * @return Finess
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
