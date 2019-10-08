<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;

                


/**
 * Etablissement
 *
 * @ORM\Table 
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\PericlesRepository")
 * @Gedmo\Loggable
 */
class Pericles
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
     * @ORM\Column(type="string", length=255)
     */
    private $gestionnaireNom;
        
    /**
     * @var string
     *
     * @ORM\Column(  type="string", length=255)
     */
    private $nom;

    
    /**
     * @var string
     *
     * @ORM\Column( type="string", length=255)
     */
    private $typeStructure;

    
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;
                

                
        
    
   
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=25, nullable=true)
     */
    private $tel;
                
    
       
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

       
       
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $finessText;

    
    
                
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Finess", inversedBy="pericles")
     * @ORM\JoinColumn(name="finess_etablissement", referencedColumnName="code_finess", nullable=true) 
     */
    private $finessEtablissement;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\FinessGestionnaire", inversedBy="pericles")
     * @ORM\JoinColumn(name="finess_gestionnaire", referencedColumnName="code_finess", nullable=true) 
     */
    private $finessGestionnaire;
    
    
    
                
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="pericles")
     * @ORM\JoinColumn(nullable=true) 
     */
    private $etablissement;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Gestionnaire", inversedBy="pericles")
     * @ORM\JoinColumn(nullable=true) 
     */
    private $gestionnaire;
    
    

    
    /**
     * @ORM\ManytoOne(targetEntity="Pericles3Bundle\Entity\Departement", inversedBy="pericles")
     */
    private $departement;

        
    
    
     
    
    
    

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
     * Set gestionnaireNom
     *
     * @param string $gestionnaireNom
     *
     * @return Pericles
     */
    public function setGestionnaireNom($gestionnaireNom)
    {
        $this->gestionnaireNom = $gestionnaireNom;

        return $this;
    }

    /**
     * Get gestionnaireNom
     *
     * @return string
     */
    public function getGestionnaireNom()
    {
        return $this->gestionnaireNom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Pericles
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
    
    public function __toString() 
    {
        return $this->getNom();
    }
    

    /**
     * Set typeStructure
     *
     * @param string $typeStructure
     *
     * @return Pericles
     */
    public function setTypeStructure($typeStructure)
    {
        $this->typeStructure = $typeStructure;

        return $this;
    }

    /**
     * Get typeStructure
     *
     * @return string
     */
    public function getTypeStructure()
    {
        return $this->typeStructure;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Pericles
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
     * Set tel
     *
     * @param string $tel
     *
     * @return Pericles
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
     * Set email
     *
     * @param string $email
     *
     * @return Pericles
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set finessText
     *
     * @param string $finessText
     *
     * @return Pericles
     */
    public function setFinessText($finessText)
    {
        $this->finessText = $finessText;

        return $this;
    }

    /**
     * Get finessText
     *
     * @return string
     */
    public function getFinessText()
    {
        return $this->finessText;
    }

     

    /**
     * Set finessEtablissement
     *
     * @param \Pericles3Bundle\Entity\Finess $finessEtablissement
     *
     * @return Pericles
     */
    public function setFinessEtablissement(\Pericles3Bundle\Entity\Finess $finessEtablissement = null)
    {
        $this->finessEtablissement = $finessEtablissement;

        return $this;
    }

    /**
     * Get finessEtablissement
     *
     * @return \Pericles3Bundle\Entity\Finess
     */
    public function getFinessEtablissement()
    {
        return $this->finessEtablissement;
    }

    /**
     * Set finessGestionnaire
     *
     * @param \Pericles3Bundle\Entity\FinessGestionnaire $finessGestionnaire
     *
     * @return Pericles
     */
    public function setFinessGestionnaire(\Pericles3Bundle\Entity\FinessGestionnaire $finessGestionnaire = null)
    {
        $this->finessGestionnaire = $finessGestionnaire;

        return $this;
    }

    /**
     * Get finessGestionnaire
     *
     * @return \Pericles3Bundle\Entity\FinessGestionnaire
     */
    public function getFinessGestionnaire()
    {
        return $this->finessGestionnaire;
    }

    /**
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Pericles
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
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return Pericles
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

    /**
     * Set departement
     *
     * @param \Pericles3Bundle\Entity\Departement $departement
     *
     * @return Pericles
     */
    public function setDepartement(\Pericles3Bundle\Entity\Departement $departement = null)
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
}
