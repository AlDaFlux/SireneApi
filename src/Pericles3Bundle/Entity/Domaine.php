<?php

namespace Pericles3Bundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domaine
 *
 * @ORM\Table(name="domaine")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DomaineRepository")
 */
class Domaine
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
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="domaines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etablissement;
    
    
    
    
    
    
    
    
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="domaines")
     * @ORM\JoinColumn(name="referentiel_id", nullable=false)
     */
    private $referentiel;
    
    
    
    
    

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\CommentaireDomaine", mappedBy="domaine")
     */
    private $commentaires;

    
    
    
    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Preuve", mappedBy="domaine")
    */
    private $preuves;

    
    
        
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DomaineObjectifStrategique", mappedBy="domaine")
     */
    private $objectifs_srategique;

    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Dimension", mappedBy="domaine")
     */
    private $dimensions;


    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->dimensions = new ArrayCollection();
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
    
    public function setEtablissement(Etablissement $etablissement)
    {
    	$this->etablissement = $etablissement;
    
    	return $this;
    }
    
    public function getEtablissement()
    {
    	return $this->etablissement;
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

    public function getReferentielPublicSource()
    {
    	return $this->getReferentiel()->getReferentielPublic()->GetSourceParent();
    }
    
    
    
    
    
    public function addCommentaire(CommentaireDomaine $commentaire)
    {
        $this->commentaires[] = $commentaire;
        $commentaire->setDomaine($this);
        return $this;
    }

    public function removeCommentaire(CommentaireDomaine $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }

    public function getCommentaires()
    {
        return $this->commentaires;
    }
    public function getNbCommentaires()
    {
        return count($this->commentaires);
    }

    public function addDimension(Dimension $dimension)
    {
        $this->dimensions[] = $dimension;

        $dimension->setDomaine($this);

        return $this;
    }

    public function removeDimension(Dimension $dimension)
    {
        $this->dimensions->removeElement($dimension);
    }

    public function getDimensions()
    {
        return $this->dimensions;
    }
    
    public function getNbDimensions()
    {
        return count($this->dimensions);
    }
    
    
    public function getNextDomaine()
    {
        return $this->etablissement->getDomaineByOrdre($this->getOrdre()+1);
    }
    public function getPreviousDomaine()
    {
        return $this->etablissement->getDomaineByOrdre($this->getOrdre()-1);
    }
    
    public function getDimensionByOrdre($ordre)
    {
        foreach ($this->dimensions as $dimension)
        {
            if ($dimension->getOrdre()==$ordre) {return($dimension);}
        }
    }
    
    
    public function getNbCriteresWithNote()
    {
        $total=0;
        foreach ($this->getDimensions() as $dimension ) {
            $total+=$dimension->getNbCriteresWithNote();
        }
        return ($total);
    }
    


    public function getMoyenneNotes(){
        $moyenne = 0;
        $dimensions = $this->getDimensions();
        if (count($dimensions)<=0)
            return 0;
        foreach ($dimensions as $dimension ) {
            $moyenne+=$dimension->getMoyenneNotes();
        }
        $moyenne=$moyenne/count($dimensions);
        return round($moyenne, 1);
    }

    public function getNbQuestions(){
        $nb = 0;
        foreach ($this->getDimensions() as $dimension ) {
            $nb+=$dimension->getNbQuestions();
        }
        return ($nb);
    }
    

    public function getNbQuestionsRepondues(){
        $nb = 0;
        foreach ($this->getDimensions() as $dimension ) {
            $nb+=$dimension->getNbQuestionsRepondues();
        }
        return ($nb);
    }
    
    
    public function getGraphLegend()
    {
//                return("'".$this->getOrdre().":".strstr($this->getNom(), ' ', true)."'");
        return($this->getNumero());
    }
    
    
    public function getGraphData()
    {
        return($this->getMoyenneNotes());
    }

    
    public function getGraphSubData()
    {
        $datas= array();
        foreach ( $this->getDimensions() as $sub)
        {
            $datas[$sub->GetOrdre()]=$sub->getMoyenneNotes();
        }
        ksort($datas);
        return(implode(",",$datas));
    }

    public function getGraphSubLegend()
    {
        $datas= array();
        foreach ( $this->getDimensions() as $sub)
        {
            $datas[$sub->GetOrdre()]='"'.$sub->getGraphLegend().'"';
        }
        ksort($datas);
        return(implode(",",$datas));
    }

    
    public function getGraphSubNb()
    { 
        return($this->getNbDimensions());
    }

    
    
    
        
    
    
    
    
    public function getStatus()
    {
        //Status de la dimension: 1-Non démarré, 2-En cours, 3:Saisi

        $isOneDimensionEncours =false;
        $isOneDimensionNonCommence = false;
        $isOneDimensionTermine =false;

        foreach ($this->getDimensions() as $dimension ) {
            $dimensionState = $dimension->getStatus();

            switch ($dimensionState) {
                case 1:
                    if (!$isOneDimensionNonCommence)
                        $isOneDimensionNonCommence =true;
                    break;
                case 2:
                    if (!$isOneDimensionEncours)
                        $isOneDimensionEncours =true;
                    break;
                case 3:
                    if (!$isOneDimensionTermine)
                        $isOneDimensionTermine =true;
                    break;
            }
        }

        if ($isOneDimensionEncours||($isOneDimensionNonCommence && $isOneDimensionTermine) )
            return 2;

        if(!$isOneDimensionEncours && !$isOneDimensionNonCommence)
            return 3;

        return 1;
    }
                
    public function getFini()
    {
        return($this->getStatus()==3);
    }

    
    
    public function getNom()
    {
        return($this->referentiel->getNom());
    }
    
        
    public function getNomCourt()
    {
        return($this->referentiel->getNomCourt());
    }
    
    public function getFirstWord()
    {
        $t=explode(" ",$this->referentiel->getNom());
        return($t[0]);
    }
    
    

    public function getOrdre()
    {
        return($this->referentiel->getOrdre());
    }
                
                
    /**
     * Add objectifsSrategique
     *
     * @param \Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsSrategique
     *
     * @return Domaine
     */
    public function addObjectifsSrategique(\Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsSrategique)
    {
        $this->objectifs_srategique[] = $objectifsSrategique;
        $objectifsSrategique->setDomaine($this);
        return $this;
    }

    /**
     * Remove objectifsSrategique
     *
     * @param \Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsSrategique
     */
    public function removeObjectifsSrategique(\Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsSrategique)
    {
        $this->objectifs_srategique->removeElement($objectifsSrategique);
    }

    /**
     * Get objectifsSrategique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectifsSrategique()
    {
        return $this->objectifs_srategique;
    }
    

    /**
     * GRetourne le nombre d'objectifs strategiques
     *
     * @return integer
     */
    public function getNbObjectifsSrategique()
    {
        return count($this->objectifs_srategique);
    }
          
    public function getObjectifsSrategiqueSince(\DateTime $date_since)
    {
        $objectifs=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getObjectifsSrategique() as $objectif ) 
        {
            if ($objectif->getDateEcheance()>=$date_since)
            {
                $objectifs->Add($objectif);
            }
        }
        return $objectifs;
    }
    
    
    public function getObjectifsTermines()
    {
        $objectifs=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getObjectifsSrategique() as $objectif ) 
        {
            if ($objectif->getTermine())
            {
                $objectifs->Add($objectif);
            }
        }
        return $objectifs;
    }
    
    public function getObjectifsNonTermines()
    {
        $objectifs=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getObjectifsSrategique() as $objectif ) 
        {
            if (! $objectif->getTermine())
            {
                $objectifs->Add($objectif);
            }
        }
        return $objectifs;
    }
    
    
    
    public function getObjectifsEnRetard()
    {
        $objectifs=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getObjectifsSrategique() as $objectif ) 
        {
            if ($objectif->getEnRetard())
            {
                $objectifs->Add($objectif);
            }
        }
        return $objectifs;
    }
    
    
    
    
    public function getNbObjectifsSrategiqueSince(\DateTime $date_since)
    {
        return count($this->getObjectifsSrategiqueSince($date_since));
    }
                
    public function getNbObjectifsOperationnelSince(\DateTime $date_since)
    {
        $total=0;
        foreach ($this->getDimensions() as $Dimension) 
        {
            $total+=$Dimension->getNbObjectifsSince($date_since);
        }
        return $total;
    }
    
    
    public function getNbObjectifsOperationnel()
    {
        $total=0;
        foreach ($this->getDimensions() as $Dimension) 
        {
            $total+=$Dimension->getNbObjectifsOperationnel();
        }
        return $total;
    }
    
    

    
    public function getNbObjectifs()
    {
        return($this->getNbObjectifsSrategique() + $this->getNbObjectifsOperationnel());
    }
                
    public function getNbObjectifsSince(\DateTime $date_since)
    {
        return($this->getNbObjectifsSrategiqueSince($date_since) + $this->getNbObjectifsOperationnelSince($date_since));
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
     * Add preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preuve
     *
     * @return Domaine
     */
    public function addPreuve(\Pericles3Bundle\Entity\Preuve $preuve)
    {
        $this->preuves[] = $preuve;
        $preuve->setDomaine($this);
        return $this;
    }

    /**
     * Remove preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     */
    public function removePreuve(\Pericles3Bundle\Entity\Preuve $preuve)
    {
        $this->preuves->removeElement($preuve);
    }

    /**
     * Get preuves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreuves()
    {
        return $this->preuves;
    }
    
    public function getNbPreuves()
    {
        return count($this->preuves);
    }
    
        
    /**
     * toString
     * @return string
     */
    public function GetNumero() 
    {
        return $this->getOrdre();
    }
    
    
    public function GetEvalSource()
    {
        $Source=$this->getReferentiel()->getSourceParent();
        if ($Source) return($Source->GetDomaineEtablissement($this->getEtablissement()));
    }
           
    
   
    
    
    

    /**
     * Add preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     *
     * @return Domaine
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
    
    
    function IsOboslete()
    {
        return (! ($this->getEtablissement()->getReferentielPublic()== $this->getReferentielPublic()));
    }
    
    
    
    
}
