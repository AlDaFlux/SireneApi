<?php

namespace Pericles3Bundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;


use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;


/**
 * Constat
 *
 * @ORM\Table(name="editorial_clu")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\EditorialCLURepository")
 */
class EditorialCLU
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
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="\Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var User $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="\Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $updatedBy;

    /**
     * @var User $contentChangedBy
     *
     * @Gedmo\Blameable(on="change", field={"titre", "commentaire"})
     * @ORM\ManyToOne(targetEntity="\Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $contentChangedBy;


    /**
     * @var date $created
     *
     * @ORM\Column(name="date_create", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $dateCreate;

    /**
     * @var date $updated
     *
     * @ORM\Column(name="date_update", type="datetime")
     * @Gedmo\Timestampable
     */
    private $dateUpdate;
    


    /**
     * @var date $created
     *
     * @ORM\Column(type="datetime")
     */
    private $datePublication;
    
    
    
    
        
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $fichier;
    
    
    
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="commentaire", type="text",nullable=true)
     */
    private $commentaire;
    
    


    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\User", mappedBy="lastCluChecked")
     */
    private $users;
    
    
    

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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return EditorialCLU
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
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return EditorialCLU
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return EditorialCLU
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set createdBy
     *
     * @param \Pericles3Bundle\Entity\User $createdBy
     *
     * @return EditorialCLU
     */
    public function setCreatedBy(\Pericles3Bundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy
     *
     * @param \Pericles3Bundle\Entity\User $updatedBy
     *
     * @return EditorialCLU
     */
    public function setUpdatedBy(\Pericles3Bundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set contentChangedBy
     *
     * @param \Pericles3Bundle\Entity\User $contentChangedBy
     *
     * @return EditorialCLU
     */
    public function setContentChangedBy(\Pericles3Bundle\Entity\User $contentChangedBy = null)
    {
        $this->contentChangedBy = $contentChangedBy;

        return $this;
    }

    /**
     * Get contentChangedBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getContentChangedBy()
    {
        return $this->contentChangedBy;
    }

    /**
     * Set fichier
     *
     * @param string $fichier
     *
     * @return EditorialCLU
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * Get fichier
     *
     * @return string
     */
    public function getFichier()
    {
        return $this->fichier;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return EditorialCLU
     */
    public function addUser(\Pericles3Bundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Pericles3Bundle\Entity\User $user
     */
    public function removeUser(\Pericles3Bundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return EditorialCLU
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
}
