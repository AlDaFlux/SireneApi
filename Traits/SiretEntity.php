<?php

namespace Aldaflux\SireneApiBundle\Traits;

use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;



/**
 * Add siret 
 */
trait SiretEntity
{

    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=14, unique=true, nullable=true)
     * @Assert\Length(
     *      min = 14,
     *      max = 14,
     *      minMessage = "Le code Finess doit être composé de 14 chiffres",
     *      maxMessage = "Le code Finess doit être composé de 14 chiffres"
     * )
     */
    private $siret;


    
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    

}

