<?php

                /*
Dans service : 
    pericles3.service:
        class: Pericles3Bundle\Service\PericlesService
        arguments: ['@security.token_storage', '@doctrine.orm.entity_manager']

Ici : 
                 * 
                 */
/*
namespace Pericles3Bundle\Service;

use Pericles3Bundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;


class PericlesService
{

    protected $token_storage;
    protected $em;
	
    public function __construct(TokenStorage $token_storage, EntityManager $em) {
        $this->token_storage = $token_storage;
        $this->em = $em;
    }
	

    
   public function isSpam($text)
  {
    return strlen($text) < 50;
  }
  
  public function GetDomaines()
  {
    return($this->getUser()->GetDomaines());
  }
  
  
  public function GetUser()
  {
    return( $this->token_storage->getToken()->getUser());
  }
  
}

*/