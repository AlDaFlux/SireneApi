<?php

namespace Pericles3Bundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;



use Doctrine\ORM\Mapping as ORM;

/**
 * Bibliotheque
 *
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\StatUserConnectRepository")
 */
class StatUserConnect
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    use TimestampableEntity;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User", inversedBy="statsUserConnect")
     */
    private $user;

    
    

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
     * Set user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return StatUserConnect
     */
    public function setUser(\Pericles3Bundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
