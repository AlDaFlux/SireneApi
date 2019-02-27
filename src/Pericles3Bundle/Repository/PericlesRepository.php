<?php

namespace Pericles3Bundle\Repository;

/**
 * ConstatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PericlesRepository extends \Doctrine\ORM\EntityRepository
{
    
     

    public function findsansEtab() 
    {
        $qb = $this->createQueryBuilder('pericles');
        $qb->Where("pericles.etablissement IS NULL ");
        return $qb->getQuery()->getResult();
    }
    
    
    public function findSansEtabParCreai(\Pericles3Bundle\Entity\Creai $creai) 
    {
        $qb = $this->createQueryBuilder('pericles');
        $qb->Join('pericles.departement', 'departement');
        $qb->Join('departement.creai', 'creai');
        
        $qb->Where("pericles.etablissement IS NULL and creai.id=".$creai->getId());
        return $qb->getQuery()->getResult();
    }
    
    
    
        
    public function findProspectionReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic) 
    {
        $qb = $this->createQueryBuilder('pericles');
        $qb->Join('pericles.finessEtablissement', 'finess');
        $qb->LeftJoin('pericles.etablissement', 'etablissementArsene');
        $qb->Join('finess.codeCategorie', 'categorie');
        $qb->Join('categorie.referentielPublicDefault', 'refpublic');
        $qb->Where('etablissementArsene.id is null and  refpublic.id = '.$referentielPublic->GetId());
        return $qb->getQuery()->getResult();
    }
    
    
    
    
    
    
}
