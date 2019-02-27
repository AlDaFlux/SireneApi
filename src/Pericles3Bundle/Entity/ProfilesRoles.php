<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Preuve
 *
 * @ORM\Table(name="profil_roles")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\ProfilesRolesRepository")
 */
class ProfilesRoles
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
     * @ORM\Column(name="name_profil", type="text", nullable=true)
     */
    private $NameProfil;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $Description;
    
    
        
    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $Roles;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type_user", type="text", nullable=true)
     */
    private $TypeUser;
    
        
    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="text", nullable=true)
     */
    private $Icon;
    
    
    
    
        
    
    

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
     * Set nameProfil
     *
     * @param string $nameProfil
     *
     * @return ProfilesRoles
     */
    public function setNameProfil($nameProfil)
    {
        $this->NameProfil = $nameProfil;

        return $this;
    }

    /**
     * Get nameProfil
     *
     * @return string
     */
    public function getNameProfil()
    {
        return $this->NameProfil;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ProfilesRoles
     */
    public function setDescription($description)
    {
        $this->Description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return ProfilesRoles
     */
    public function setRoles($roles)
    {
        $this->Roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->Roles;
    }

    /**
     * Set typeUser
     *
     * @param string $typeUser
     *
     * @return ProfilesRoles
     */
    public function setTypeUser($typeUser)
    {
        $this->TypeUser = $typeUser;

        return $this;
    }

    /**
     * Get typeUser
     *
     * @return string
     */
    public function getTypeUser()
    {
        return $this->TypeUser;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return ProfilesRoles
     */
    public function setIcon($icon)
    {
        $this->Icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->Icon;
    }
    
       
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getNameProfil();
    }
            
            
}
