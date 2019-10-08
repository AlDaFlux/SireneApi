<?php

namespace Pericles3Bundle\Repository;

/**
 * BibliothequeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EditorialMentionsLegalesRepository extends \Doctrine\ORM\EntityRepository
{
    	public function findLast()
	{
            $qb = $this->createQueryBuilder('clu');
            $qb->orderBy("clu.datePublication","DESC");
            $qb->where("clu.datePublication <= '".date("Y-m-d")."'");
            $qb->setMaxResults(1);
            return($qb->getQuery()->getOneOrNullResult());
	}


    
}