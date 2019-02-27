<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Dimension
 *
 * @ORM\Table(name="dimension")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DimensionRepository")
 */
class Dimension
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
  	 * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Domaine",inversedBy="dimensions")
  	 * @ORM\JoinColumn(nullable=false)
   	 */
    private $domaine;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel",inversedBy="dimensions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentiel;

    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Critere", mappedBy="dimension")
     */
    private $criteres;

    public function __construct()
    {
        $this->criteres = new ArrayCollection();
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

    public function setDomaine(Domaine $domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getDomaine()
    {
        return $this->domaine;
    }

    public function addCritere(Critere $critere)
    {
        $this->criteres[] = $critere;

        $critere->setDimension($this);

        return $this;
    }

    public function removeCritere(Critere $critere)
    {
        $this->criteres->removeElement($critere);
    }

    public function getCriteres()
    {
        return $this->criteres;
    }
    
    
    public function getNbCriteres()
    {
        return count($this->criteres);
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

    
    public function getMoyenneNotes(){
        $moyenne = 0;
        $nb_criteres=0;
        $criteres = $this->getCriteres();
        foreach ($criteres as $critere ) {
            if ( ! ($critere->getNote()==-1)) // non concerné
            {
                $nb_criteres++;
                $moyenne+=$critere->getNote();
            }
        }
        
        if ($nb_criteres)
        {
            $moyenne=$moyenne/$nb_criteres;
            return round($moyenne, 1);
        }
        else
        {
            return(0);
        }
    }

    public function getNote()
    {
        return($this->getMoyenneNotes());
    }
    
    
    public function getNbQuestions(){
        $nb = 0;
        $criteres = $this->getCriteres();
        foreach ($criteres as $critere )
        {
            $nb+=$critere->getNbQuestions();
        }
        return ($nb);
    }
    
  
    
    
    
    
    public function getNbQuestionsRepondues(){
        $nb = 0;
        $criteres = $this->getCriteres();
        foreach ($criteres as $critere )
        {
            $nb+=$critere->getNbQuestionsRepondues();
        }
        return ($nb);
    }
    
    
    
    
    
    

    
    
    public function getStatus()
    {
        //Status de la dimension: 1-Non démarré, 2-En cours, 3:Saisi 4 A revoir - 5-modifie

        $isOneCritereEncours =false;
        $isOneCritereNonCommence = false;
        $isOneCritereTermine =false;
        $isOneCritereArevoir =false;
        $isOneCritereModifie =false;

        foreach ($this->getCriteres() as $critere ) {
            $crietereState = $critere->getStatus();

            switch ($crietereState) {
                case 1:
                        $isOneCritereNonCommence =true;
                    break;
                case 2:
                        $isOneCritereEncours =true;
                    break;
                case 3:
                        $isOneCritereTermine =true;
                    break;
                case 4:
                        $isOneCritereArevoir =true;
                    break;            
                case 5:
                        $isOneCritereModifie =true;
                case 6:
                        $isOneCritereModifie =true;
                    break;            
            }
        }

        if ($isOneCritereModifie) return 5;
        if ($isOneCritereArevoir) return 4;

        if ($isOneCritereEncours||($isOneCritereNonCommence && $isOneCritereTermine) )
            return 2;

        if(!$isOneCritereEncours && !$isOneCritereNonCommence)
            return 3;

        return 1;
    }
    
    public function getStatusCss()
    {
        switch ($this->getStatus()) 
        {
            case 1:
               return("todo");
               break;    
            case 2:
               return("doing");
               break;    
            case 3:
               return("done");
               break;    
            case 4:
               return("toedit");
               break;    
        }
    }
        
    public function getStatusLib()
    {
        switch ($this->getStatus()) 
        {
            case 1:
               return("A faire");
               break;    
            case 2:
               return("En cours");
               break;    
            case 3:
               return("Fait");
               break;    
            case 4:
               return("A revoir");
               break;    
        }
    }
    
    
            
    
    
       
    public function getNom()
    {
        return($this->referentiel->getNom());
    }
 
    
    public function getNbFichesAction()
    {
        $total=0;
        foreach ($this->getCriteres() as $critere) 
        {
            $total+=$critere->GetNbFichesAction();
        }
        return ($total);
    }
    
    
          
    public function getNbObjectifsOperationnel()
    {
        $total=0;
        foreach ($this->getCriteres() as $critere) 
        {
            $total+=$critere->getNbObjectifsOperationnel();
        }
        return ($total);
    }
    
    
           
    public function getNbObjectifsSince(\DateTime $date_since)
    {
        $total=0;
        foreach ($this->getCriteres() as $critere) 
        {
            $total+=$critere->getNbObjectifsSince($date_since);
        }
        return ($total);
    }
    
    
    
       
    public function getNbCriteresWObjectifsOperationnel()
    {
        $total=0;
        foreach ($this->getCriteres() as $critere) 
        {
            if ($critere->getNbObjectifsOperationnel()) $total++;
        }
        return ($total);
    }
    
     
       
    public function getNbCriteresWithNote()
    {
        $total=0;
        foreach ($this->getCriteres() as $critere) 
        {
            if ($critere->HaveNote()) $total++;
        }
        return ($total);
    }
    
    
    public function getOrdre()
    {
        return($this->referentiel->getOrdre());
    }
    
    
    public function getGraphLegend()
    {
        return($this->GetNumero());
    }
    
    
    public function getGraphData()
    {
        return($this->getMoyenneNotes());
    }

    
    
    public function getGraphSubData()
    {
        $datas= array();
        foreach ( $this->getCriteres() as $sub)
        {
            $datas[$sub->GetOrdre()]=$sub->getNote();
        }
        ksort($datas);
        return(implode(",",$datas));
    }

    public function getGraphSubLegend()
    {
        $datas= array();
        foreach ( $this->getCriteres() as $sub)
        {
            $datas[$sub->GetOrdre()]='"'.$sub->getGraphLegend().'"';
        }
        ksort($datas);
        return(implode(",",$datas));
    }

    
    public function getGraphSubNb()
    { 
        return($this->getNbCriteres());
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
     * toString
     * @return string
     */
    public function GetNumero() 
    {
        return $this->getDomaine()->GetNumero().".".$this->GetOrdre();
    }
    
    
    
       
    public function getNextDimension()
    {
        return $this->getDomaine()->getDimensionByOrdre($this->getOrdre()+1);
    }
    
    public function getPreviousDimension()
    {
        return $this->getDomaine()->getDimensionByOrdre($this->getOrdre()-1);
    }
    
        
    public function getCritereByOrdre($ordre)
    {
        foreach ($this->criteres as $critere)
        {
            if ($critere->getOrdre()==$ordre) {return($critere);}
        }
    } 
    
    
    
        /**
     * Get etablissement
     *
     * @return \Pericles3Bundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->GetDomaine()->getEtablissement();
    }

    
    function IsObsolete()
    {
        return ($this->getDomaine()->IsOboslete());
    }
    

    
    
    
}
