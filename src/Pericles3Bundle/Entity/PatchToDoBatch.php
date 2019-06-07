<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;




/**
 * Creai
 *
 * @ORM\Table 
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\PatchToDoBatchRepository")
 */
class PatchToDoBatch
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
       
    

    use BlameableEntity;
    use TimestampableEntity;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDebutPatch;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFinPatch;
    
    
    
    

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
     * Set dateDebutPatch
     *
     * @param \DateTime $dateDebutPatch
     *
     * @return PatchToDoBatch
     */
    public function setDateDebutPatch($dateDebutPatch)
    {
        $this->dateDebutPatch = $dateDebutPatch;

        return $this;
    }

    /**
     * Get dateDebutPatch
     *
     * @return \DateTime
     */
    public function getDateDebutPatch()
    {
        return $this->dateDebutPatch;
    }

    /**
     * Set dateFinPatch
     *
     * @param \DateTime $dateFinPatch
     *
     * @return PatchToDoBatch
     */
    public function setDateFinPatch($dateFinPatch)
    {
        $this->dateFinPatch = $dateFinPatch;

        return $this;
    }

    /**
     * Get dateFinPatch
     *
     * @return \DateTime
     */
    public function getDateFinPatch()
    {
        return $this->dateFinPatch;
    }
}
