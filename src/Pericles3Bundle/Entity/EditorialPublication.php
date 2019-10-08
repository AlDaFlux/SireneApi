<?php

namespace Pericles3Bundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;


/**
 * Constat
 *
 * @ORM\Table(name="editorial_publication")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\EditorialPublicationRepository")
 */
class EditorialPublication
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;


    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="nom", type="string")
     */
    private $nom;
   
      
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Editorial", mappedBy="etatPublication")
     */
    private $articles;

 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EditorialPublication
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
     * Add article
     *
     * @param \Pericles3Bundle\Entity\Editorial $article
     *
     * @return EditorialPublication
     */
    public function addArticle(\Pericles3Bundle\Entity\Editorial $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \Pericles3Bundle\Entity\Editorial $article
     */
    public function removeArticle(\Pericles3Bundle\Entity\Editorial $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
    
    
     public function __toString() 
    {
        return $this->getNom();
    }

    
    
    
}
