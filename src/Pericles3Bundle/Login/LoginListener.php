<?php

namespace Pericles3Bundle\Login;

use Pericles3Bundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;

class LoginListener
{
	protected $token_storage;
	protected $em;
	
	public function __construct(TokenStorage $token_storage, EntityManager $em) {
		$this->token_storage = $token_storage;
		$this->em = $em;
	}
	
	public function login() {
		$user = $this->token_storage->getToken()->getUser();
		$user->setDateLastConnect(new \DateTime());
		$this->em->persist($user);
		$this->em->flush();
	}
        
        
}