<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Critere
 *
 * @ORM\Table(name="critere")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\CritereRepository")
 */
class Critere
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Dimension",inversedBy="criteres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dimension;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Constat", mappedBy="critere")
     */
    private $constats;

   

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Question", mappedBy="critere")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Preuve", mappedBy="critere")
     */
    private $preuves;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel",  inversedBy="criteres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;

    
     
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\DomaineExterne",  inversedBy="criteres")
     */
    private $domaineExterne;
     
    
    
    
     /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\ObjectifOperationnel",  inversedBy="criteres")
     * @ORM\JoinTable(name="critere_objectif")
     */
    private $objectifs;  


    /**
     * @var arevoir
     * @ORM\Column(type="integer", options={"default":0})
     */
    protected $arevoir;
    
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeCritere", mappedBy="critere_original")
     */
    private $sauvegardes;

    
    
    public function getCritereSauvegarde(\Pericles3Bundle\Entity\Sauvegarde $Sauvegarde)
    {
        foreach ($this->getSauvegardes() as $CritereSauvegarde)
        {
            if ($CritereSauvegarde->GetSauvegarde()==$Sauvegarde)
            {
                return($CritereSauvegarde);
            }
        }
    }


    public function getOldNote(\Pericles3Bundle\Entity\Sauvegarde $Sauvegarde)
    {
        $CritereSauvegarde=$this->getCritereSauvegarde($Sauvegarde);
        if ($CritereSauvegarde)
        {
            return($CritereSauvegarde->GetNote());
        }
        else
        {
            return(-2);
        }
    }
    
    
    
    
    
    
    public function getEvolution(\Pericles3Bundle\Entity\Sauvegarde $Sauvegarde)
    {
        $retour="INCONNU";
        $CritereSauvegarde=$this->getCritereSauvegarde($Sauvegarde);
        if ($CritereSauvegarde)
        {
            if ($this->getNote()==$CritereSauvegarde->getNote()) $retour="stable";
            elseif ($this->getNote()<$CritereSauvegarde->getNote()) $retour="baisse";
            else $retour="hausse";
        }
        else 
        {
            $retour="nouveau";
        }
        return($retour);
    }
    
    
    public function __construct()
    {
        $this->constats = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->preuves = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Critere
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        if ($this->note) return $this->note;
        else {return (0);}
    }
    
    public function getNonConcerne()
    {
        return ($this->note==-1);
    }
    
    public function getConcerne()
    {
        return (! ($this->note==-1));
    }
    
    
    

    public function HaveNote()
    {
        return ($this->note);
    }
    


    public function setDimension(Dimension $dimension)
    {
        $this->dimension = $dimension;
        return $this;
    }

    public function getDimension()
    {
        return $this->dimension;
    }

    public function addConstat(Constat $constat)
    {
        $this->constats[] = $constat;

        $constat->setCritere($this);

        return $this;
    }

    public function removeConstat(Constat $constat)
    {
        $this->constats->removeElement($constat);
    }

    public function getConstats()
    {
        return $this->constats;
    }
    
    public function getConstatsSince(\DateTime $date_since)
    {
        $constats=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getConstats() as $constat ) 
        {
            if ($constat->getDateCreate()>=$date_since)
            {
                $constats->Add($constat);
            }
        }
        return $constats;
    }
    
    
    
    
    public function getNbConstats()
    {
        return count($this->constats);
    }
 
  
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
        $question->setCritere($this);
        return $this;
    }

    public function removeQuestion(Question $question)
    {
        $this->questions->removeElement($question);
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function addPreuve(Preuve $preuve)
    {
        $this->preuves[] = $preuve;
        $preuve->setCritere($this);
        return $this;
    }
    

    public function removePreuve(Preuve $preuve)
    {
        $this->preuves->removeElement($preuve);
    }

    public function getPreuves()
    {
        return $this->preuves;
    }
    
    public function getNbPreuves()
    {
        return count($this->preuves);
    }
    
    public function getPreuvesPaq()
    {
        $preuves = new ArrayCollection();
        foreach ($this->getObjectifs() as $objectif)
        {
            foreach ($objectif->getPreuves() as $preuve)
            {
                 $preuves->Add($preuve);
                 
    //                 if (!  $preuves->contains($preuve)) { }
            }
        }
        return $preuves;
    }
    
    public function getNbPreuvesPaq()
    { 
        return count($this->getPreuvesPaq());
    }
    
    
    
       
    public function getPreuvesSince(\DateTime $date_since)
    {
        $preuves=  new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($this->getPreuves() as $preuve ) 
        {
            if ($preuve->getDateCreate()>=$date_since)
            {
                $preuves->Add($preuve);
            }
        }
        return $preuves;
    }
    
    
    
    
    
    public function setReferentiel(Referentiel $referentiel)
    {
    	$this->referentiel = $referentiel;
    
    	return $this;
    }
    
    public function getReferentiel()
    {
    	return $this->referentiel;
    }

    
    public function getReferentielPublic()
    {
    	return $this->getReferentiel()->getReferentielPublic();
    }

     
    public function getStatus2017Desuet()
    {
        //Status du critère: 1-Non démarré, 2-En cours, 3:Saisi 0 : Non concerné - > 4 A revoir 5-modifie
        if ($this->getModifie()) return 5;
        if ($this->GetArevoir()) return 4;
        if ($this->note==-1)
            return 0;
        if ($this->note>0)
            return 3;

        $isQuestionRepondue =false;
        foreach ($this->getQuestions() as $question ) {
            if ($question->getReponse()!=null && !$isQuestionRepondue)
            {
                $isQuestionRepondue=true;
            }
        }

        if ($isQuestionRepondue)
            return 2;
        else
            return 1;
    }
     
    public function getStatus()
    {
        //Status du critère: 1-Non démarré, 2-En cours, 3:Saisi 0 : Non concerné - > 4 A revoir 5-modifie - 6 nouveau
        if ($this->getNouveau()) return 6;
        if ($this->getModifie()) return 5;
        
        if ($this->GetArevoir())return 4;
            $AllQuestionRepondue =true;
            $NoneQuestionRepondue =true;
            foreach ($this->getQuestions() as $question ) 
            {
                if ($question->getARepondre()) { $AllQuestionRepondue=false; }
                if (! $question->getARepondre()) { $NoneQuestionRepondue=false; }
            }
        
        if ($this->note==-1) { return 0; }
        if ($this->note==0 && $NoneQuestionRepondue) { return 1; }
        if ($this->note>0 && $AllQuestionRepondue) 
        {
            return 3;
        }
        else 
        {
            return 2;
        }
    }
     

    public function getPourcentageNon()
    {
        //Status du critère: 1-Non démarré, 2-En cours, 3:Saisi 0 : Non concerné
        $QuestionNon =0 ;
        $Questions =0 ;
        foreach ($this->getQuestions() as $question ) {
            if (! $question->getReponse())
            {
                $QuestionNon+=1;
            }
            $Questions+=1;
        }
        return((round($QuestionNon/$Questions*100)));
    }
    
 
    
    public function getOrdre()
    {
        return($this->referentiel->getOrdre());
    }

    
    
 
    /**
     * Add preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     *
     * @return Critere
     */
    public function addPreufe(\Pericles3Bundle\Entity\Preuve $preufe)
    {
        $this->preuves[] = $preufe;

        return $this;
    }

    /**
     * Remove preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     */
    public function removePreufe(\Pericles3Bundle\Entity\Preuve $preufe)
    {
        $this->preuves->removeElement($preufe);
    }
    
    
    
           
    public function getNom()
    {
        return($this->referentiel->getNom());
    }
      
   
    
    
    public function getNbQuestionsRepondues()
    {
        $total=0;
        foreach ($this->questions as $question)
        {
            if (! $question->getARepondre()) $total++;
        }
        return $total;
    }
    
    
    
    public function getNbQuestions()
    {
        return(count($this->questions ));
    }
    
    
    
    
    
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getNom();
    }
    

    /**
     * Add objectif
     *
     * @param \Pericles3Bundle\Entity\ObjectifOperationnel $objectif
     *
     * @return Critere
     */
    public function addObjectif(\Pericles3Bundle\Entity\ObjectifOperationnel $objectif)
    {
        $this->objectifs[] = $objectif;

        return $this;
    }

    /**
     * Remove objectif
     *
     * @param \Pericles3Bundle\Entity\ObjectifOperationnel $objectif
     */
    public function removeObjectif(\Pericles3Bundle\Entity\ObjectifOperationnel $objectif)
    {
        $this->objectifs->removeElement($objectif);
    }
    
    

    /**
     * Get objectifs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectifs()
    {
        return $this->objectifs;
    }
    
    /**
     * Get objectifs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNbObjectifsOperationnel()
    {
        return count($this->objectifs);
    }
    
    
           
    public function getObjectifsSince(\DateTime $date_since)
    {
        $objectifs=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getObjectifs() as $objectif ) 
        {
            if ($objectif->getDateDebut()>=$date_since or $objectif->getDateFin()>=$date_since)
            {
                $objectifs->Add($objectif);
            }
        }
        return $objectifs;
    }
    
    public function getNbObjectifsSince(\DateTime $date_since)
    {
        return count($this->getObjectifsSince($date_since));
    }
    
    
    
    
    
    
    
 
    /**
     * Set domaineExterne
     *
     * @param \Pericles3Bundle\Entity\DomaineExterne $domaineExterne
     *
     * @return Critere
     */
    public function setDomaineExterne(\Pericles3Bundle\Entity\DomaineExterne $domaineExterne = null)
    {
        $this->domaineExterne = $domaineExterne;

        return $this;
    }

    /**
     * Get domaineExterne
     *
     * @return \Pericles3Bundle\Entity\DomaineExterne
     */
    public function getDomaineExterne()
    {
        return $this->domaineExterne;
    }
    
    
    public function getReferentielExterneN1()
    {
        if ($this->domaineExterne)
        {
            return($this->domaineExterne->getReferentielExterneN1());
        }
    }
    
    
    public function getReferentielExterneN1Normal()
    {
        if ($this->getReferentielPublic()->getReferentielExterne())
        {
            return($this->getReferentielPublic()->getReferentielExterne()->getReferentielExterneNiv1ByCritereRef($this->getReferentiel()));
        }
    }
    
    
    public function getReferentielExterneN1OK()
    {
        return($this->getReferentielExterneN1Normal()==$this->getReferentielExterneN1());
    }
    
    
    
    
    
    
    /*
    public function getRefDomaineN1ExterneNormal()
    {
        foreach ($this->getReferentielPublic()->GetReferentielExterne()->getReferentielExterneNiv1ByEtablissement($this->getEtablissement()) as $n1)
        {
            return($n1);
        }
    }
    */
    
    
        /**
     * toString
     * @return string
     */
    public function GetNumero() 
    {
        return $this->getDimension()->GetNumero().".".$this->GetOrdre();
    }
    
    
       
    public function getGraphLegend()
    {
        return("'".$this->GetNumero()."'");
    }
    
    
    public function getGraphData()
    {
        if ($this->getNote()) return($this->getNote());
        else return(0);
    }
           
    public function getNextCritere()
    {
             
             
        return $this->getDimension()->getCritereByOrdre($this->getOrdre()+1);
    }
    
    public function getPreviousCritere()
    {
              return $this->getDimension()->getCritereByOrdre($this->getOrdre()-1);
    }
    
    
    

    /**
     * Set arevoir
     *
     * @param boolean $arevoir
     *
     * @return Critere
     */
    public function setArevoir($arevoir)
    {
        $this->arevoir = $arevoir;

        return $this;
    }

    /**
     * Get arevoir
     *
     * @return boolean
     */
    public function getArevoir()
    {
        return ($this->arevoir==1);
    }
    
    public function getModifie()
    {
        return ($this->arevoir==3);
    }
    
    public function getNouveau()
    {
        return ($this->arevoir==4);
    }
    
    public function getModifieReferentiel()
    {
        if ($this->arevoir!=4)
        {
            $this->arevoir=3;
        }
    }
    
    
    
    
    
    
    
            /**
     * Get etablissement
     *
     * @return \Pericles3Bundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->GetDimension()->getEtablissement();
    }

    public function getRBPP()
    {
        return $this->GetReferentiel()->getRBPP();
    }
    
    public function getRbpppComment()
    {
        return $this->GetReferentiel()->getRbpppComment();
    }

    
    
     
    
    public function GetEvalSource()
    {
        $Source=$this->getReferentiel()->getSourceParent();
        if ($Source) return($this->getReferentiel()->GetSourceParent()->GetCritereEtablissement($this->getEtablissement()));
    }
 
    
    public function GetEvalCible(ReferentielPublic $ReferentielPublicCible)
    {
//        $Source=$this->getReferentiel()->getSourceChildren();

        $ReferentielChild=null; 
        foreach ($this->getReferentiel()->getSourceChildren() as $child)
        {
            if ($child->GetReferentielPublic()==$ReferentielPublicCible)
            {
                $ReferentielChild=$child;
            }
        }
        
        if ($ReferentielChild)
        {
            return($ReferentielChild->getCritereEtablissementReferentiel($this->getEtablissement(),$ReferentielPublicCible));
        }
        
    }
    
    
    

    /**
     * Add sauvegarde
     *
     * @param \Pericles3Bundle\Entity\SauvegardeCritere $sauvegarde
     *
     * @return Critere
     */
    public function addSauvegarde(\Pericles3Bundle\Entity\SauvegardeCritere $sauvegarde)
    {
        $this->sauvegardes[] = $sauvegarde;

        return $this;
    }

    /**
     * Remove sauvegarde
     *
     * @param \Pericles3Bundle\Entity\SauvegardeCritere $sauvegarde
     */
    public function removeSauvegarde(\Pericles3Bundle\Entity\SauvegardeCritere $sauvegarde)
    {
        $this->sauvegardes->removeElement($sauvegarde);
    }

    /**
     * Get sauvegardes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSauvegardes()
    {
        return $this->sauvegardes;
    }
    
    
    function IsObsolete()
    {
        return ($this->getDimension()->IsObsolete());
    }
    
    
}
