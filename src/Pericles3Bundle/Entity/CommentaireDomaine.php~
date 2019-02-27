<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentaireDomaine
 *
 * @ORM\Table(name="commentaire_domaine")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\CommentaireDomaineRepository")
 */
class CommentaireDomaine
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Domaine",inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domaine;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User",inversedBy="commentaires")
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
     * @return CommentaireDomaine
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


    public function setDomaine(Domaine $domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }


    public function getDomaine()
    {
        return $this->domaine;
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

    
    

    public function getEtablissement()
    {
        return $this->getDomaine()->GetEtablissement();
    }
    
    

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return CommentaireDomaine
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
        return $this->getCommentaire();
    }
    
    
}
