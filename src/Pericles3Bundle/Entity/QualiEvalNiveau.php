<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bibliotheque
 *
 * @ORM\Entity
 */
class QualiEvalNiveau
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    
    
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2)
     */
    private $code;

    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\QualiEvalReferentiel",mappedBy="niveau")
     */
    private $QEReferentiel;



        
    

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
     * Set nom
     *
     * @param string $nom
     *
     * @return BibiolthequeTypeDoc
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
    
          
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getNom();
    }
    
    
    
    
    
    
}
