<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bibliotheque
 *
 * @ORM\Table(name="ba_type_source")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\BibliothequeAncreaiTypeSourceRepository")
 */
class BibliothequeAncreaiTypeSource
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_court", type="string", length=255)
     */
    private $titre_court;

    
         
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\BibliothequeAncreai", mappedBy="typeSourceBA")
     */
    private $bibliothequesAncreai;


    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bibliothequesAncreai = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     *
     * @return BibliothequeAncreaiTypeSource
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set titreCourt
     *
     * @param string $titreCourt
     *
     * @return BibliothequeAncreaiTypeSource
     */
    public function setTitreCourt($titreCourt)
    {
        $this->titre_court = $titreCourt;

        return $this;
    }

    /**
     * Get titreCourt
     *
     * @return string
     */
    public function getTitreCourt()
    {
        return $this->titre_court;
    }

    /**
     * Add bibliothequesAncreai
     *
     * @param \Pericles3Bundle\Entity\BibliothequeAncreai $bibliothequesAncreai
     *
     * @return BibliothequeAncreaiTypeSource
     */
    public function addBibliothequesAncreai(\Pericles3Bundle\Entity\BibliothequeAncreai $bibliothequesAncreai)
    {
        $this->bibliothequesAncreai[] = $bibliothequesAncreai;

        return $this;
    }

    /**
     * Remove bibliothequesAncreai
     *
     * @param \Pericles3Bundle\Entity\BibliothequeAncreai $bibliothequesAncreai
     */
    public function removeBibliothequesAncreai(\Pericles3Bundle\Entity\BibliothequeAncreai $bibliothequesAncreai)
    {
        $this->bibliothequesAncreai->removeElement($bibliothequesAncreai);
    }

    /**
     * Get bibliothequesAncreai
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBibliothequesAncreai()
    {
        return $this->bibliothequesAncreai;
    }
    
    
    
     /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getTitreCourt();
    }
    
}
