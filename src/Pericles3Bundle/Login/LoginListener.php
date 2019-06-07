<?php

namespace Pericles3Bundle\Login;

use Pericles3Bundle\Entity\User;
use Pericles3Bundle\Entity\Creai;
use Pericles3Bundle\Entity\Gestionnaire;
use Pericles3Bundle\Entity\Etablissement;
use Pericles3Bundle\Entity\StatUserConnect;
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
                $statUser=new StatUserConnect();
                $statUser->setUser($user);
		$this->em->persist($statUser);
		$this->em->flush();
                
                $gestionnaire=null;
                
                if ($user->isCreai())
                {
                    $creai=$user->getCreai();
                    $creai->setDateLastConnect(new \DateTime());
                    $this->em->persist($creai);
                    $this->em->flush();
                }
                elseif ($user->IsAnEtablissement())
                {
                    $etablissement=$user->getEtablissement();
                    $etablissement->setDateLastConnect(new \DateTime());
                    $this->em->persist($etablissement);
                    $this->em->flush();
                    $gestionnaire= $etablissement->getGestionnaire();
                }
                elseif ($user->isGestionnaire ())
                {
                    $gestionnaire=$user->getGestionnaire();
                    foreach ($user->GetEtablissements() as $etablissement  )
                    {
                           $etablissement->setDateLastConnect(new \DateTime());
                            $this->em->persist($etablissement);
                            $this->em->flush();
                    }
                }
                if ($gestionnaire)
                {
                    $gestionnaire->setDateLastConnect(new \DateTime());
                    $this->em->persist($gestionnaire);
                    $this->em->flush();
                }
        }
         
}