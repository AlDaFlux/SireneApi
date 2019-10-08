<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;



/**
 * Etablissement
 *
 * @ORM\Entity
 */
class GestionnaireCategory
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
     * @ORM\Column(name="commentaire", type="string", length=255)
     */
    private $commentaire;
                

    

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Gestionnaire", mappedBy="category")
     */
    private $gestionnaires;

    
    

    /**
     * @var reel
     * @ORM\Column(type="boolean")
     */
    protected $reel;
        
    
    
    
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getNom();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return EtablissementCategory
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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return EtablissementCategory
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set reel
     *
     * @param boolean $reel
     *
     * @return EtablissementCategory
     */
    public function setReel($reel)
    {
        $this->reel = $reel;

        return $this;
    }

    /**
     * Get reel
     *
     * @return boolean
     */
    public function getReel()
    {
        return $this->reel;
    }
 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gestionnaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return GestionnaireCategory
     */
    public function addGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire)
    {
        $this->gestionnaires[] = $gestionnaire;

        return $this;
    }

    /**
     * Remove gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     */
    public function removeGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire)
    {
        $this->gestionnaires->removeElement($gestionnaire);
    }

    /**
     * Get gestionnaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGestionnaires()
    {
        return $this->gestionnaires;
    }
}
