<?php

namespace Pericles3Bundle\Repository;

/**
 * ReferentielRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReferentielRepository extends \Doctrine\ORM\EntityRepository
{
    
        public function FindTest() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.id = 11991 or referentiel.id = 8299 ");
            return $qb->getQuery()->getResult();
        }
    
        public function FindReferentielCibleManquantCriteres(\Pericles3Bundle\Entity\ReferentielPublic $PublicSource,\Pericles3Bundle\Entity\ReferentielPublic $PublicCible) 
        {
            // no nutilisé ?
            $qb = $this->createQueryBuilder('referentiel');
//            $qb->select(array('referentiel.nom as lib','criteres.id as id','\'pericles3_critere\' as path'));
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublic');
            $qb->LeftJoin('referentiel.ReferentielPublic', 'ReferentielPublic');
            
            
            $qb->where("ReferentielPublic.id = :source_id");
            $qb->andWhere("referentiel.typeReferentiel = 3");
            $qb->setParameter('source_id', $PublicSource->getId());
//            $qb->setParameter('etablissement_id', $etablissement->getId());
            $qb->setMaxResults(5);
            return $qb->getQuery()->getResult();
        }
         
        public function FindCriteresRefExterneManquant() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublic');
            $qb->LeftJoin('ReferentielPublic.referentielExterne', 'referentielExterne');
            $qb->where("referentielExterne.id IS NOT NULL");
            $qb->andWhere("referentiel.typeReferentiel = 3");
            $qb->andWhere("referentiel.ReferentielExterneNiv1 IS NULL");
            return $qb->getQuery()->getResult();
        }
         


        public function FindReferentielCibleSansSource(\Pericles3Bundle\Entity\ReferentielPublic $PublicCible,$type_id=0) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublic');
            $qb->LeftJoin('referentiel.sourceParent', 'sourceParent');
            $qb->where("ReferentielPublic.id = :id ");
            $qb->andWhere("sourceParent.id IS NULL");
            if ($type_id) $qb->AndWhere("referentiel.typeReferentiel=".$type_id);
            
            $qb->setMaxResults(3);
            
            $qb->setParameter('id', $PublicCible->getId());
            return $qb->getQuery()->getResult();
        }
          
   
     	public function findParentSansEnfant(\Pericles3Bundle\Entity\ReferentielPublic $ReferentielPublicParent,\Pericles3Bundle\Entity\ReferentielPublic $ReferentielPublicEnfant,$type_id=0) 
        {
            $nots = $this->createQueryBuilder('referentiel_n')
                    ->select(['sourceParent_n.id'])
                    ->Join('referentiel_n.ReferentielPublic', 'ReferentielPublic_n')
                    ->Join('referentiel_n.sourceParent', 'sourceParent_n')
                    ->where("ReferentielPublic_n.id = :id_ref_sub ");
            
            $qb = $this->createQueryBuilder('referentiel');
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublicParent');
            $qb->LeftJoin('referentiel.sourceChildren', 'children');
            $qb->LeftJoin('children.ReferentielPublic', 'ReferentielPublicChildren');
            $qb->where("ReferentielPublicParent.id = :id_parent_t ");
            
            if ($type_id) $qb->AndWhere("referentiel.typeReferentiel=".$type_id);
            
            $qb->setParameter('id_parent_t', $ReferentielPublicParent->getId());
            $qb->AndWhere($qb->expr()->notIn('referentiel.id', $nots->getDQL()));
            $qb->setParameter('id_ref_sub', $ReferentielPublicEnfant->getId());
            return $qb->getQuery()->getResult();
        }

/*
        public function FindDomaineCibleByEtablissementDomaineSource(\Pericles3Bundle\Entity\ReferentielPublic $PublicCible) 
        {
            // no nutilisé ?
            $qb = $this->createQueryBuilder('referentiel');
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublic');
            /*
            $qb->LeftJoin('referentiel.sourceParent', 'sourceParent');
            $qb->where("ReferentielPublic.id = :id ");
            $qb->andWhere("sourceParent.id IS NULL");
             * 
             * 
            
            
            $qb->setParameter('id', $PublicCible->getId());
            return $qb->getQuery()->getResult();
        }
         */

        
        
        public function FindChildByOrder(\Pericles3Bundle\Entity\Referentiel $Referentiel) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.parent=:id")->SetParameter("id",$Referentiel->GetId());
            $qb->orderBy("referentiel.ordre");
            return $qb->getQuery()->getResult();
        }
        
        
        
        public function FindNextByType(\Pericles3Bundle\Entity\Referentiel $Referentiel) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.id > :id")->SetParameter("id",$Referentiel->GetId());
            $qb->andWhere("referentiel.typeReferentiel= :type")->SetParameter("type",$Referentiel->TypeReferentiel());
            $qb->andWhere("referentiel.ReferentielPublic= :public")->SetParameter("public",$Referentiel->GetReferentielPublic());
            $qb->orderBy("referentiel.id");
            $qb->setMaxResults(1);
            $retour=$qb->getQuery()->getOneOrNullResult();
            if ($retour) { return ($retour); }
            elseif ($Referentiel->TypeReferentiel()->GetId()<>4)
            {
                return ($this->FindFirstByType($Referentiel->GetReferentielPublic(), $Referentiel->TypeReferentiel()->GetId()+1) );
            }
        }
        
        public function FindNextByTypeOrphan(\Pericles3Bundle\Entity\Referentiel $Referentiel) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.id > :id")->SetParameter("id",$Referentiel->GetId());
            $qb->andWhere("referentiel.typeReferentiel= :type")->SetParameter("type",$Referentiel->TypeReferentiel());
            $qb->andWhere("referentiel.ReferentielPublic= :public")->SetParameter("public",$Referentiel->GetReferentielPublic());
            $qb->andWhere("referentiel.sourceParent IS null");
            $qb->orderBy("referentiel.id");
            $qb->setMaxResults(1);
            $retour=$qb->getQuery()->getOneOrNullResult();
            if ($retour) { return ($retour); }
            elseif ($Referentiel->TypeReferentiel()->GetId()<>4)
            {
                return ($this->FindFirstByTypeOrphan($Referentiel->GetReferentielPublic(), $Referentiel->TypeReferentiel()->GetId()+1) );
            }
        }
        
        
        
        public function FindFirstByType(\Pericles3Bundle\Entity\ReferentielPublic $ReferentielPublic, $type_id) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->Where("referentiel.typeReferentiel= :type")->SetParameter("type",$type_id);
            $qb->andWhere("referentiel.ReferentielPublic= :public")->SetParameter("public",$ReferentielPublic);
            $qb->orderBy("referentiel.id");
            $qb->setMaxResults(1);
            $retour=$qb->getQuery()->getOneOrNullResult();
            return ($retour);
        }
        

        public function FindFirstByTypeOrphan(\Pericles3Bundle\Entity\ReferentielPublic $ReferentielPublic, $type_id) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->Where("referentiel.typeReferentiel= :type")->SetParameter("type",$type_id);
            $qb->andWhere("referentiel.ReferentielPublic= :public")->SetParameter("public",$ReferentielPublic);
            $qb->andWhere("referentiel.sourceParent IS null");
            $qb->orderBy("referentiel.id");
            $qb->setMaxResults(1);
            $retour=$qb->getQuery()->getOneOrNullResult();
            return ($retour);
        }
        

        public function FindFirstOrphan(\Pericles3Bundle\Entity\ReferentielPublic $ReferentielPublic) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->Where("referentiel.ReferentielPublic= :public")->SetParameter("public",$ReferentielPublic);
            $qb->andWhere("referentiel.sourceParent IS null");
            $qb->orderBy("referentiel.id");
            $qb->setMaxResults(1);
            $retour=$qb->getQuery()->getOneOrNullResult();
            return ($retour);
        }
        

        
        
        
        

        public function FindByUserDomaines(\Pericles3Bundle\Entity\User $User) 
        {
            // en dev non utilisé
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.typeReferentiel=1");
            $qb->setMaxResults(5);
            return $qb->getQuery()->getResult();
        }
        
        
        
        
       	public function FindByLibellePublicType($lib,\Pericles3Bundle\Entity\ReferentielPublic $Public,$type=0) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.nom = :ocurence AND referentiel.typeReferentiel=".$type." AND  referentiel.ReferentielPublic = :public ")
                    ->setParameter('public', $Public->getId())
                    ->setParameter('ocurence', $lib)
                    ;
            $qb->setMaxResults(1);
            $retour=$qb->getQuery()->getOneOrNullResult();
            
            if ($retour) { return ($retour); }
        }
        
        
     
        
       	public function FindByPublic(\Pericles3Bundle\Entity\ReferentielPublic $Public) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.ReferentielPublic = :public ")->setParameter('public', $Public->getId());
  //          $qb->setMaxResults(1000);
            return $qb->getQuery()->getResult();
        }
        
    
       	public function FindCritereByPublic(\Pericles3Bundle\Entity\ReferentielPublic $Public) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.typeReferentiel=3 AND  referentiel.ReferentielPublic = :public ")->setParameter('public', $Public->getId());
//            $qb->setMaxResults(1000);
            return $qb->getQuery()->getResult();
        }
        

       	public function FindDimensionsByPublic(\Pericles3Bundle\Entity\ReferentielPublic $Public) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.typeReferentiel=2 AND  referentiel.ReferentielPublic = :public ")->setParameter('public', $Public->getId());
//            $qb->setMaxResults(1000);
            return $qb->getQuery()->getResult();
        }

       	public function FindQuestionsByPublic(\Pericles3Bundle\Entity\ReferentielPublic $Public) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.typeReferentiel=4 AND  referentiel.ReferentielPublic = :public ")->setParameter('public', $Public->getId());
            return $qb->getQuery()->getResult();
        }
        

        public function FindSaufQuestions() 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->where("referentiel.typeReferentiel<4 ");
            return $qb->getQuery()->getResult();
        }
        
        
       	public function FindByEtablissementOccurenceDomaine($etablissement,$occurence) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->select(array('referentiel.nom as lib','domaines.id as id','\'pericles3_domaine\' as path'));
            $qb->Join('referentiel.domaines', 'domaines');
            $qb->where("referentiel.nom LIKE :occurence AND  domaines.etablissement = :etablissement_id")->setParameter('etablissement_id', $etablissement->getId())->setParameter('occurence',"%".$occurence."%");
                
            $qb->setMaxResults(25);
            return $qb->getQuery()->getResult();
        }
        
       	public function FindByEtablissementOccurenceDimension($etablissement,$occurence) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->select(array('referentiel.nom as lib','dimensions.id as id','\'pericles3_dimension\' as path'));
            $qb->Join('referentiel.dimensions', 'dimensions');
            $qb->Join('dimensions.domaine', 'domaines');
            $qb->where("referentiel.nom LIKE :occurence  AND domaines.etablissement = :etablissement_id")->setParameter('etablissement_id', $etablissement->getId())->setParameter('occurence',"%".$occurence."%");
            $qb->setMaxResults(25);
            return $qb->getQuery()->getResult();
        }
        
        public function FindByEtablissementOccurenceCritere($etablissement,$occurence) 
        {
            $qb = $this->createQueryBuilder('referentiel');
            $qb->select(array('referentiel.nom as lib','criteres.id as id','\'pericles3_critere\' as path'));
            $qb->Join('referentiel.criteres', 'criteres');
            $qb->Join('criteres.dimension', 'dimensions');
            $qb->Join('dimensions.domaine', 'domaines');
            $qb->where("referentiel.nom LIKE :occurence  AND domaines.etablissement = :etablissement_id")->setParameter('etablissement_id', $etablissement->getId())->setParameter('occurence',"%".$occurence."%");
            $qb->setMaxResults(25);
            return $qb->getQuery()->getResult();
        }
        
        
        
        public function FindReferentielPasDansPatchSourceAll() 
        {
            $nots = $this->createQueryBuilder('referentiel_n')
                    ->Join('referentiel_n.patchSources', 'PatchReferentiel_n')
                    ->Join('PatchReferentiel_n.patch', 'patch_n');
            $qb = $this->createQueryBuilder('referentiel');
            $qb->select(array('referentiel.nom as ref_lib','patch.id as patch_id'));
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublic');
            $qb->InnerJoin('ReferentielPublic.patchSources', 'patch');
            $qb->Where($qb->expr()->notIn('referentiel.id', $nots->getDQL()));
            return $qb->getQuery()->getResult();
        }        

        public function FindReferentielPasDansPatchCibleAll() 
        {
            $nots = $this->createQueryBuilder('referentiel_n')
                    ->Join('referentiel_n.patchCibles', 'PatchReferentiel_n')
                    ->Join('PatchReferentiel_n.patch', 'patch_n');
            $qb = $this->createQueryBuilder('referentiel');
            $qb->select(array('referentiel.nom as ref_lib','patch.id as patch_id'));
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublic');
            $qb->InnerJoin('ReferentielPublic.patchCibles', 'patch');
            $qb->Where($qb->expr()->notIn('referentiel.id', $nots->getDQL()));
            return $qb->getQuery()->getResult();
        }        
        


        public function FindReferentielPasDansPatchSource(\Pericles3Bundle\Entity\ReferentielPublic $Public,\Pericles3Bundle\Entity\Patch $patch) 
        {
            $nots = $this->createQueryBuilder('referentiel_n')
                    //->select(['sourceParent_n.id'])
//                    ->select(['referentiel_n.id'])
                    ->Join('referentiel_n.patchSources', 'PatchReferentiel_n')
                    ->Join('PatchReferentiel_n.patch', 'patch_n')
                    ->where("patch_n.id = ".$patch->GetId());

            $qb = $this->createQueryBuilder('referentiel');
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublic');
            $qb->where("ReferentielPublic.id = ".$Public->GetId());
            $qb->AndWhere($qb->expr()->notIn('referentiel.id', $nots->getDQL()));
//            $qb->setParameter('id_ref_sub', $ReferentielPublicEnfant->getId());
            return $qb->getQuery()->getResult();
        }        
        
          public function FindReferentielPasDansPatchCible(\Pericles3Bundle\Entity\ReferentielPublic $Public,\Pericles3Bundle\Entity\Patch $patch) 
        {
            $nots = $this->createQueryBuilder('referentiel_n')
                    //->select(['sourceParent_n.id'])
//                    ->select(['referentiel_n.id'])
                    ->Join('referentiel_n.patchCibles', 'PatchReferentiel_n')
                    ->Join('PatchReferentiel_n.patch', 'patch_n')
                    ->where("patch_n.id = ".$patch->GetId());

            $qb = $this->createQueryBuilder('referentiel');
            $qb->Join('referentiel.ReferentielPublic', 'ReferentielPublic');
            $qb->where("ReferentielPublic.id = ".$Public->GetId());
            $qb->AndWhere($qb->expr()->notIn('referentiel.id', $nots->getDQL()));
//            $qb->setParameter('id_ref_sub', $ReferentielPublicEnfant->getId());
            return $qb->getQuery()->getResult();
        }        
        
        
}
