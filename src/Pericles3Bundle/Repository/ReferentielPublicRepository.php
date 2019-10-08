<?php

namespace Pericles3Bundle\Repository;

/**
 * ReferentielPublicRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReferentielPublicRepository extends \Doctrine\ORM\EntityRepository
{
     	public function findAll() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.fini=1");
            return $qb->getQuery()->getResult();
        }
        
        
     	public function findNonfini() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.fini<>1");
            return $qb->getQuery()->getResult();
        }
        
     	public function findVeryAll() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            return $qb->getQuery()->getResult();
        }
        
        
     	public function findAllUser(\Pericles3Bundle\Entity\User $user) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->Join('referentiel.users', 'users');
            $qb->where('users.id = :id_user');
            $qb->setParameter('id_user', $user->getId());               
            return $qb->getQuery()->getResult();
        }
         
        public function findNonEnCours() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.fini<>0");
            return $qb->getQuery()->getResult();
        }
        
        public function findNonDesuet() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.fini<>-2");
            $qb->orderBy("referentiel.createdAt","DESC");
            return $qb->getQuery()->getResult();
        }
        

        
        public function findDesuet() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.fini=-2");
            $qb->orderBy("referentiel.createdAt","DESC");
            return $qb->getQuery()->getResult();
        }
        
        
        
        public function findSansParents() 
        {
            $qb = $this->createQueryBuilder('ref');
            $qb->where("ref.sourceParent IS NULL");
            
            return $qb->getQuery()->getResult();
        }
         
        
        public function findWithParents() 
        {
            $qb = $this->createQueryBuilder('ref');
            $qb->where("ref.sourceParent IS NOT NULL AND  ref.fini<>0");
            
            return $qb->getQuery()->getResult();
        }
        
        public function findSourcePatch() 
        {
            $qb = $this->createQueryBuilder('ref');
            $qb->Join('ref.patchSources', 'patchSources');
            $qb->where("ref.fini<>0");
            return $qb->getQuery()->getResult();
        }
        
        public function findAlpha() 
        {
            $qb = $this->createQueryBuilder('ref');
            $qb->where("ref.versionningParent IS NULL");
            return $qb->getQuery()->getResult();
        }
        
        
         
         
        
}
