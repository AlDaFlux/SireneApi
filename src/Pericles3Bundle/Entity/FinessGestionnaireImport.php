<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Finess
 *
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\FinessGestionnaireImportRepository")
 */
class FinessGestionnaireImport
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Departement",  inversedBy="finess_gestionnaire_import")
     * @ORM\JoinColumn(name="departement_id", nullable=false)
     */
    private $departement;
    
    
        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\FinessImport",  mappedBy="gestionnaire")
     */
    private $etablissements;
    
       
}
