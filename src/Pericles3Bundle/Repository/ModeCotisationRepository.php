<?php

namespace Pericles3Bundle\Repository;
 
/**
 * CritereRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModeCotisationRepository extends \Doctrine\ORM\EntityRepository
{
    
     
        public function findChoix() 
        {
            $qb = $this->createQueryBuilder('cotis');
            $qb->where('cotis.id>0 and cotis.id<5 ');
            $qb->OrderBy("cotis.id");
            return $qb->getQuery()->getResult();
        }
        
        
}
