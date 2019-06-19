<?php

namespace Pericles3Bundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;


/**
 * Constat
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\EditorialMentionsLegalesRepository")
 */
class EditorialMentionsLegales
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
     * @ORM\Column(type="text")
     */
    private $contenu;
    

    

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return EditorialMentionsLegales
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }
 
    
    public function __toString()
    {
        return $this->contenu;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return EditorialMentionsLegales
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
     * @return EditorialMentionsLegales
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
     * Set createdBy
     *
     * @param \Pericles3Bundle\Entity\User $createdBy
     *
     * @return EditorialMentionsLegales
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
     * @return EditorialMentionsLegales
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
     * @return EditorialMentionsLegales
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
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return EditorialMentionsLegales
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
}
