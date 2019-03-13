<?php

namespace Pericles3Bundle\Repository;

/**
 * BibliothequeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BibliothequeRepository extends \Doctrine\ORM\EntityRepository
{
    
    	public function findLast($limit=5)
	{
            $qb = $this->createQueryBuilder('biblio');
            if ($limit) { $qb->setMaxResults($limit); }
            $qb->orderBy("biblio.dateUpdate","DESC");
            return $qb->getQuery()->getResult();
	}
        
	public function findLastByEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement, $limit=9)
	{
            $qb = $this->createQueryBuilder('biblio');
            $qb->setMaxResults($limit);
            $qb->orderBy("biblio.dateUpdate","DESC");
            $qb->where("biblio.etablissement = :etablissement_id")->setParameter('etablissement_id', $etablissement->getId());
            return $qb->getQuery()->getResult();
	}
        

        
        
        public function FindByEtablissementOccurence($etablissement,$occurence) 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            $qb->select(array('bibliotheque.message as lib','bibliotheque.id as id','\'pericles3_bibliotheque_show\' as path'));
            $qb->where("(bibliotheque.message LIKE :occurence OR  bibliotheque.fichier LIKE :occurence ) AND bibliotheque.etablissement = :etablissement_id")->setParameter('etablissement_id', $etablissement->getId())->setParameter('occurence',"%".$occurence."%");
            $qb->setMaxResults(25);
            return $qb->getQuery()->getResult();
        }
        
    
        public function FindIdBiblioByFile($etablissement,$filename) 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            $qb->select('bibliotheque.id as id');
            $qb->where("bibliotheque.fichier = :filename AND bibliotheque.etablissement = :etablissement_id")->setParameter('etablissement_id', $etablissement->getId())->setParameter('filename',$filename);
            $qb->setMaxResults(1);
            $result=$qb->getQuery()->getOneOrNullResult();
            return $result['id'];
        }
        public function FindBiblioByFile($etablissement,$filename) 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            $qb->where("bibliotheque.fichier = :filename AND bibliotheque.etablissement = :etablissement_id")->setParameter('etablissement_id', $etablissement->getId())->setParameter('filename',$filename);
            $qb->setMaxResults(1);
            $result=$qb->getQuery()->getOneOrNullResult();
            return $result;
        }
        
        
        public function FindBiblioByFileAll($filename) 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            $qb->where("bibliotheque.fichier = :filename ")->setParameter('filename',$filename);
            $result=$qb->getQuery()->getResult();
            return $result;
        }
        
        
        public function FindIdBiblioGestionnaireByFile($gestionnaire,$filename) 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            $qb->select('bibliotheque.id as id');
            $qb->where("bibliotheque.fichier = :filename AND bibliotheque.gestionnaire = :gestionnaire_id")->setParameter('gestionnaire_id', $gestionnaire->getId())->setParameter('filename',$filename);
            $qb->setMaxResults(1);
            $result=$qb->getQuery()->getOneOrNullResult();
            return $result['id'];
        }
        
        public function FindBiblioGestionnaireByFile($gestionnaire,$filename) 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            $qb->where("bibliotheque.fichier = :filename AND bibliotheque.gestionnaire = :gestionnaire_id")->setParameter('gestionnaire_id', $gestionnaire->getId())->setParameter('filename',$filename);
            $qb->setMaxResults(1);
            $result=$qb->getQuery()->getOneOrNullResult();
            return $result;
        }
        
        public function FindBiblioEtablissementsByGestionnaire(\Pericles3Bundle\Entity\User $User ,$limit=0) 
        {
            
            $qb = $this->createQueryBuilder('bibliotheque');
            if ($limit) { $qb->setMaxResults($limit); }
            $qb->Join('bibliotheque.etablissement', 'etablissement');
            if ($User->getIsAdminPole())
            {
                $qb->Join('etablissement.userPole', 'userPole');
                $qb->where('userPole.id = :user_id')->setParameter('user_id', $User->getId());
            }
            else
            {
                $qb->where("etablissement.gestionnaire = :gestionnaire_id")->setParameter('gestionnaire_id', $User->getGestionnaire()->getId());
            }
            $qb->orderBy("bibliotheque.dateUpdate","DESC");
            return $qb->getQuery()->getResult();
        }
        
        
        public function FindBiblioGestionnaireByGestionnaire($gestionnaire,$limit=0) 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            if ($limit) { $qb->setMaxResults($limit); }
            $qb->where("bibliotheque.gestionnaire = :gestionnaire_id")->setParameter('gestionnaire_id', $gestionnaire->getId());
            $qb->orderBy("bibliotheque.dateUpdate","DESC");
            return $qb->getQuery()->getResult();
        }
        
        
           
        public function findFichiersManquant() 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            $qb->where("bibliotheque.type_message = 'fichier' and (bibliotheque.fichier IS NULL or bibliotheque.fichier='')");
            return $qb->getQuery()->getResult();
        }
        
        public function findFichiers() 
        {
            $qb = $this->createQueryBuilder('bibliotheque');
            $qb->where("bibliotheque.type_message = 'fichier' and (bibliotheque.fichier IS NOT NULL AND bibliotheque.fichier<>'')");
            return $qb->getQuery()->getResult();
        }
        
        
        
   
        
}