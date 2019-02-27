<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Constat
 *
 * @ORM\Table(name="constat")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\ConstatRepository")
 */
class Constat
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
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Critere",inversedBy="constats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $critere;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User",inversedBy="constats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;
 
    
    

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Constat
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


    public function setCritere(Critere $critere)
    {
        $this->critere = $critere;

        return $this;
    }


    public function getCritere()
    {
        return $this->critere;
    }


    public function getEtablissement()
    {
        return $this->getCritere()->GetEtablissement();
    }

    
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Constat
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }
    
    
      
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return "".$this->getCommentaire();
    }
    

    
    
}
