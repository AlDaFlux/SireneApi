<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Finess
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="code_finess_2", columns={"code_finess"})}, indexes={@ORM\Index(name="code_finess", columns={"code_finess"})})
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\FinessImportRepository")
 */
class FinessImport
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\FinessCategorie",  inversedBy="finessImport")
     * @ORM\JoinColumn(name="code_categorie", nullable=true)
     */
    private $codeCategorie;

     
    

    
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Departement",  inversedBy="finessImport")
     * @ORM\JoinColumn(name="departement_id", nullable=false)
     */
    private $departement;
    
 
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\FinessGestionnaireImport",  inversedBy="etablissements")
     * @ORM\JoinColumn(referencedColumnName="code_finess", nullable=true) 
     */
    private $gestionnaire;
    
     
    
    
    
    
    
    

    /**
     * Set codeFiness
     *
     * @param string $codeFiness
     *
     * @return FinessImport
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
     * @return FinessImport
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
     * @return FinessImport
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
     * @return FinessImport
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
     * @return FinessImport
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
     * @return FinessImport
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
     * @return FinessImport
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
     * Set tel
     *
     * @param string $tel
     *
     * @return FinessImport
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
     * @return FinessImport
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
     * Set codeCategorie
     *
     * @param \Pericles3Bundle\Entity\FinessCategorie $codeCategorie
     *
     * @return FinessImport
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
     * Set departement
     *
     * @param \Pericles3Bundle\Entity\Departement $departement
     *
     * @return FinessImport
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
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\FinessGestionnaireImport $gestionnaire
     *
     * @return FinessImport
     */
    public function setGestionnaire(\Pericles3Bundle\Entity\FinessGestionnaireImport $gestionnaire = null)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \Pericles3Bundle\Entity\FinessGestionnaireImport
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }
}
