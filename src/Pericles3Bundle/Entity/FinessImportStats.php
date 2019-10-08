<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;



/**
 * FinessImport
 *
 * @ORM\Entity
 */
class FinessImportStats
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateMajData;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateMajImport;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateMajFiness;

    
    
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $logsBeforeUpdate;

    
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $logsAfterUpdate;


    
    

    
    
    
    use TimestampableEntity;
    
    
}
