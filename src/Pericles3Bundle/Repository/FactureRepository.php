<?php

namespace Pericles3Bundle\Repository;

/**
 * ConstatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FactureRepository extends \Doctrine\ORM\EntityRepository
{
    
     
        
    	public function findEtabAlier() 
        {
            $qb = $this->createQueryBuilder('facture');
            $qb->where("facture.concerneGestionnaire=0 AND facture.etablissement IS NULL "); 
            return $qb->getQuery()->getResult();
	}           
        
    	public function findLastFactureNum() 
        {
            $qb = $this->createQueryBuilder('facture');
            $qb->Select('max(facture.numFacture) as numFacture ');
            return $qb->getQuery()->getOneOrNullResult();
	}
        
        
        
    	public function findYear($year) 
        {
            if (is_numeric($year))
            {
                $qb = $this->createQueryBuilder('facture');
                $qb->where("facture.numFacture LIKE '".$year."%'"); 
                return $qb->getQuery()->getResult();
            }   
    	}   
 
        
    	public function findNonFinalisee() 
        {
            $qb = $this->createQueryBuilder('facture');
            $qb->where("facture.finalise=0"); 
            return $qb->getQuery()->getResult();
	}   
        
    	public function findNonPayee() 
        {
            $qb = $this->createQueryBuilder('facture');
            $qb->where("facture.payele IS NULL and facture.finalise=1"); 
            return $qb->getQuery()->getResult();
            
            
            
	}   
        
        public function findNonPayeeOld($limit=5) 
        {
            $qb = $this->createQueryBuilder('facture');
            $qb->where("facture.payele IS NULL and facture.finalise=1"); 
            $qb->OrderBy("facture.dateEmission"); 
            if ($limit)
            {
                $qb->setMaxResults($limit);
            }
            return $qb->getQuery()->getResult();
	}   
        
        

    	public function findSommeNonPayee() 
        {
            $qb = $this->createQueryBuilder('facture');
            $qb->Join('facture.facturePrestas', 'facturePrestas');
            $qb->Select('SUM(facturePrestas.montant) as total ');
            $qb->where("facture.payele IS NULL  and facture.finalise=1"); 
            return $qb->getQuery()->getOneOrNullResult();
	}   

        
    public function findFactureARenouvellerNum() 
    {
        $subquery = $this->createQueryBuilder('facture');
        $subquery->Select('max(facture.numFacture) as numFacture, max(facture.dateEmission) as dateEmission,etablissement.nom,etablissement.id');
        $subquery->Join('facture.facturePrestas', 'facturePrestas');
        $subquery->Join('facturePrestas.etablissement', 'etablissement');
        $subquery->Join('etablissement.category', 'category');
        $subquery->where("category.reel=1  and facture.finalise=1"); 
        
        $subquery->groupBy('etablissement.nom','etablissement.id');
        $subquery->OrderBy('numFacture');
        $subquery->having("dateEmission < '". date('Y-m-d',strtotime(date("Y-m-d", time()) . " - 365 day"))."'");
        return $subquery->getQuery()->getResult();
    }
    
    
    
        
    public function ProchainesFacturesAEcheances($limit=10) 
    {
        $subquery = $this->createQueryBuilder('facture');
        $subquery->Where("facture.dateEmission >= '". date('Y-m-d',strtotime(date("Y-m-d", time()) . " - 365 day"))."'");
        $subquery->OrderBy('facture.dateEmission');
        if ($limit) $subquery->setMaxResults($limit);
        return $subquery->getQuery()->getResult();
    }
    
        
        
}
