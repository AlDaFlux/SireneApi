<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;


use \Datetime;


/**
 * Facture
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\FactureRepository")
 */
class Facture
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=false)
     * @ORM\Id
     */
    private $numFacture;

    
    /**
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="\Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var User $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="\Pericles3Bundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $updatedBy;

    
    /** 
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $concerneGestionnaire;
             
        
    /** 
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $genere;
             
                 
        
    /** 
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $nonRenouvelable;
             
    
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $remise;
    
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $remise_libelle;
    
    
    
    /** 
     * @var boolean
     *
     * @ORM\Column(type="integer")
     */
    private $finalise;
             
     
    
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;


    
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateEmission;

    
   
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="factures")
     */
    private $etablissement;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Gestionnaire", inversedBy="factures")
     */
    private $gestionnaire;

    
    
   
    
   
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\FactureMoyenPaiement", inversedBy="factures")
     * @ORM\JoinColumn(nullable=true)
     */
    private $MoyenPaiement;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\FactureRappel", mappedBy="facture")
     */
    private $rappels;

    
    
      
    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montant;



      /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle_gestionnaire;


    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $neufcent;

    
    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cinccentcinquante;

    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $payele;



    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle_paiement;


    
    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ren300;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ren150;

    
    
     /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\FacturePresta", mappedBy="facture",orphanRemoval=true, cascade={"all"})
     */
    private $facturePrestas;


   

    /**
     * Set numFacture
     *
     * @param string $numFacture
     *
     * @return Facture
     */
    public function setNumFacture($numFacture)
    {
        $this->numFacture = $numFacture;

        return $this;
    }

    /**
     * Get numFacture
     *
     * @return string
     */
    public function getNumFacture()
    {
        return $this->numFacture;
    }
    
    public function getNumFactureFormate()
    {
        return str_replace("_", "/", $this->numFacture) ;
    }
    
    
    
    
    

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Facture
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set dateEmission
     *
     * @param \DateTime $dateEmission
     *
     * @return Facture
     */
    public function setDateEmission($dateEmission)
    {
        $this->dateEmission = $dateEmission;

        return $this;
    }

    /**
     * Get dateEmission
     *
     * @return \DateTime
     */
    public function getDateEmission()
    {
        return $this->dateEmission;
    }
 
    public function getPlusdunAn()
    {
        $oldate = new DateTime();
        $oldate->modify('-1 year');
        return ($this->dateEmission<$oldate);
    }
 
    public function getPlusdunAn15jours()
    {
        $oldate = new DateTime();
        $oldate->modify('-1 year');
        $oldate->modify('+15 days');
        return ($this->dateEmission<$oldate);
    }
    
    public function getOldDate()
    {
        $oldate = new DateTime();
        $oldate->modify('-1 year');
        $oldate->modify('-15 days');
        return ($oldate);
    }
    
    
    
    
    function isLastFacture()
    {
        return($this==$this->GetLastFacture());
    }
    
    
    function GetLastFacture()
    {
        return($this->getConcerne()->GetLastFacture());
    }
    
    
 
    
    
    
    
    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return Facture
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set libelleGestionnaire
     *
     * @param string $libelleGestionnaire
     *
     * @return Facture
     */
    public function setLibelleGestionnaire($libelleGestionnaire)
    {
        $this->libelle_gestionnaire = $libelleGestionnaire;

        return $this;
    }

    /**
     * Get libelleGestionnaire
     *
     * @return string
     */
    public function getLibelleGestionnaire()
    {
        return $this->libelle_gestionnaire;
    }

    /**
     * Set neufcent
     *
     * @param integer $neufcent
     *
     * @return Facture
     */
    public function setNeufcent($neufcent)
    {
        $this->neufcent = $neufcent;

        return $this;
    }

    /**
     * Get neufcent
     *
     * @return integer
     */
    public function getNeufcent()
    {
        return $this->neufcent;
    }

    /**
     * Set cinccentcinquante
     *
     * @param integer $cinccentcinquante
     *
     * @return Facture
     */
    public function setCinccentcinquante($cinccentcinquante)
    {
        $this->cinccentcinquante = $cinccentcinquante;

        return $this;
    }

    /**
     * Get cinccentcinquante
     *
     * @return integer
     */
    public function getCinccentcinquante()
    {
        return $this->cinccentcinquante;
    }

 
    /**
     * Set payele
     *
     * @param \DateTime $payele
     *
     * @return Facture
     */
    public function setPayele($payele)
    {
        $this->payele = $payele;

        return $this;
    }

    /**
     * Get payele
     *
     * @return \DateTime
     */
    public function getPayele()
    {
        return $this->payele;
    }

    /**
     * Set libellePaiement
     *
     * @param string $libellePaiement
     *
     * @return Facture
     */
    public function setLibellePaiement($libellePaiement)
    {
        $this->libelle_paiement = $libellePaiement;

        return $this;
    }

    /**
     * Get libellePaiement
     *
     * @return string
     */
    public function getLibellePaiement()
    {
        return $this->libelle_paiement;
    }

    /**
     * Set ren300
     *
     * @param integer $ren300
     *
     * @return Facture
     */
    public function setRen300($ren300)
    {
        $this->ren300 = $ren300;

        return $this;
    }

    /**
     * Get ren300
     *
     * @return integer
     */
    public function getRen300()
    {
        return $this->ren300;
    }

    /**
     * Set ren150
     *
     * @param integer $ren150
     *
     * @return Facture
     */
    public function setRen150($ren150)
    {
        $this->ren150 = $ren150;

        return $this;
    }

    /**
     * Get ren150
     *
     * @return integer
     */
    public function getRen150()
    {
        return $this->ren150;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Facture
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    
    /**
     * Get creai
     *
     * @return \Pericles3Bundle\Entity\Creai
     */
    public function getCreai()
    {
        if ($this->getConcerne()) return $this->getConcerne()->getCreai();
    }
    
    
    
    

    /**
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Facture
     */
    public function setEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \Pericles3Bundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return Facture
     */
    public function setGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire = null)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \Pericles3Bundle\Entity\Gestionnaire
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }
    
    
    

    /**
     * Set concerneGestionnaire
     *
     * @param boolean $concerneGestionnaire
     *
     * @return Facture
     */
    public function setConcerneGestionnaire($concerneGestionnaire)
    {
        $this->concerneGestionnaire = $concerneGestionnaire;

        return $this;
    }

    /**
     * Get concerneGestionnaire
     *
     * @return boolean
     */
    public function getConcerneGestionnaire()
    {
        return $this->concerneGestionnaire;
    }
    
    public function getConcerneTypeLib()
    {
        if ($this->concerneGestionnaire) return("GESTIONNAIRE");
        else return("ETABLISSEMENT");
    }
    
    public function getConcerne()
    {
        if ($this->concerneGestionnaire) return($this->getGestionnaire());
        else return($this->getEtablissement ());
    }


    
    function __toString() 
    {
        return($this->numFacture);
    }    
    
    function GetYear() 
    {
        return(substr($this->numFacture,0,4));
    }    
    
    
    
    
    

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return Facture
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    public function getHasPDF()
    {
        return $this->fileName;
    }
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturePrestas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add facturePresta
     *
     * @param \Pericles3Bundle\Entity\FacturePresta $facturePresta
     *
     * @return Facture
     */
    public function addFacturePresta(\Pericles3Bundle\Entity\FacturePresta $facturePresta)
    {
        $this->facturePrestas[] = $facturePresta;
        $facturePresta->setFacture($this);
        return $this;
    }

    /**
     * Remove facturePresta
     *
     * @param \Pericles3Bundle\Entity\FacturePresta $facturePresta
     */
    public function removeFacturePresta(\Pericles3Bundle\Entity\FacturePresta $facturePresta)
    {
        $this->facturePrestas->removeElement($facturePresta);
    }

    /**
     * Get facturePrestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturePrestas()
    {
        return $this->facturePrestas;
    }
    
    
    public function getFacturePrestasAllLast()
    {
        $last_presta=true;
        foreach ($this->getFacturePrestasEtablissements() as $presta )
        {
            $last_presta=$last_presta && $presta->isLastFacturePresta();
        }
        return $last_presta;
    }
    
    public function getFacturePrestasHasLast()
    {
        $last_presta=false;
        foreach ($this->getFacturePrestasEtablissements() as $presta )
        {
            $last_presta=($last_presta or $presta->isLastFacturePresta());
        }
        return $last_presta;
    }
    
    
    
    
    public function getFacturePrestasEtablissements()
    {
        $prestasEtablissement= new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($this->getFacturePrestas() as $presta )
        {
            if ($presta->getEtablissement())
            {
                $prestasEtablissement->add($presta);
            
            }
        }
        return $prestasEtablissement;
    }
    
    
    
    
    
    
    
    
    public function getMontantRemise()
    {
        if ($this->remise)
        {
            if (strpos($this->remise,"%"))
            {
                $montant=$this->getMontantTotalTotalPresta();
                $taux= str_replace("%", "", $this->remise);
                return($montant*(($taux)/100));
            }
            else
            {
                return($this->remise);
            }
        }
        return(0);
    }
    
    public function getMontantTotalTotalPresta()
    {
        $montant=0;
        foreach ($this->facturePrestas as $presta)
        {
            $montant+=$presta->GetMontant();
        }
       
        return($montant);
    }
    
    
    public function getMontantTotal()
    {
        return($this->getMontantTotalTotalPresta()-$this->getMontantRemise());
    }
    
    
    
    
    public function getHasPrestaGestionnaire()
    {
        foreach($this->facturePrestas as $presta)
        {
            if ($presta->getGestionnaire()) return(true);
        }
    }
    
    
    Function getHasEtablissement(\Pericles3Bundle\Entity\Etablissement $etab)
    {
        
        foreach($this->facturePrestas as $presta)
        {
            if ($presta->getEtablissement()==$etab) return(true);
        }
    }
    
    Function getHasNotEtablissement(\Pericles3Bundle\Entity\Etablissement $etab)
    {
        return(! $this->getHasEtablissement($etab));
    }
    
    Function getDefautMontant()
    {
        
        $montant_defaut=0;
        foreach($this->facturePrestas as $presta)
        {
            if ($presta->getEtablissement())
            {
                return($presta->GetMontant());
            }
        }
    }

    
    
    
    
    

    /**
     * Set remise
     *
     * @param string $remise
     *
     * @return Facture
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return string
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * Set remiseLibelle
     *
     * @param string $remiseLibelle
     *
     * @return Facture
     */
    public function setRemiseLibelle($remiseLibelle)
    {
        $this->remise_libelle = $remiseLibelle;

        return $this;
    }

    /**
     * Get remiseLibelle
     *
     * @return string
     */
    public function getRemiseLibelle()
    {
        return $this->remise_libelle;
    }

    /**
     * Set finalise
     *
     * @param boolean $finalise
     *
     * @return Facture
     */
    public function setFinalise($finalise)
    {
        $this->finalise = $finalise;

        return $this;
    }

    /**
     * Get finalise
     *
     * @return boolean
     */
    public function getFinalise()
    {
        return $this->finalise;
    }
    
    public function getEstFinalise()
    {
        return $this->finalise==1;
    }
    
    public function getAvoir()
    {
        return ($this->finalise==2);
    }
    
    
    
    
    public function getNotFinalized()
    {
        return (! $this->finalise==1);
    }
    
    
    
    public function AdresseLigne1()
    {
        return($this->getConcerne()->GetAdresse());
    }
    
    
    public function AdresseLigne2()
    {
        return($this->getConcerne()->GetCodePostal()." ".$this->getConcerne()->GetVille());
    }
    


    
 

    /**
     * Set genere
     *
     * @param boolean $genere
     *
     * @return Facture
     */
    public function setGenere($genere)
    {
        $this->genere = $genere;

        return $this;
    }

    /**
     * Get genere
     *
     * @return boolean
     */
    public function getGenere()
    {
        return $this->genere;
    }

    /**
     * Set moyenPaiement
     *
     * @param \Pericles3Bundle\Entity\FactureMoyenPaiement $moyenPaiement
     *
     * @return Facture
     */
    public function setMoyenPaiement(\Pericles3Bundle\Entity\FactureMoyenPaiement $moyenPaiement = null)
    {
        $this->MoyenPaiement = $moyenPaiement;

        return $this;
    }

    /**
     * Get moyenPaiement
     *
     * @return \Pericles3Bundle\Entity\FactureMoyenPaiement
     */
    public function getMoyenPaiement()
    {
        return $this->MoyenPaiement;
    }

    /**
     * Add rappel
     *
     * @param \Pericles3Bundle\Entity\FactureRappel $rappel
     *
     * @return Facture
     */
    public function addRappel(\Pericles3Bundle\Entity\FactureRappel $rappel)
    {
        $this->rappels[] = $rappel;

        return $this;
    }

    /**
     * Remove rappel
     *
     * @param \Pericles3Bundle\Entity\FactureRappel $rappel
     */
    public function removeRappel(\Pericles3Bundle\Entity\FactureRappel $rappel)
    {
        $this->rappels->removeElement($rappel);
    }

    /**
     * Get rappels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRappels()
    {
        return $this->rappels;
    }
    
    public function getLastRappel()
    {
        
        $lastdate=0;
        $last_rappel=null;
        foreach ($this->rappels as $rappel)
        {
            if ($rappel->getDateRappel()->getTimestamp()>$lastdate)
            {
                $last_rappel=$rappel;
            }
        }
        return $last_rappel;
    }
    
 
    
    
 

    /**
     * Set nonRenouvelable
     *
     * @param boolean $nonRenouvelable
     *
     * @return Facture
     */
    public function setNonRenouvelable($nonRenouvelable)
    {
        $this->nonRenouvelable = $nonRenouvelable;

        return $this;
    }

    /**
     * Get nonRenouvelable
     *
     * @return boolean
     */
    public function getNonRenouvelable()
    {
        return $this->nonRenouvelable;
    }
    
     
    public function getDatePluOneYear()
    {
        $date_creation_plus = clone  $this->getDateEmission();
        $date_creation_plus->modify("+1 year");
        return($date_creation_plus);
    }

    
    

    /**
     * Set createdBy
     *
     * @param \Pericles3Bundle\Entity\User $createdBy
     *
     * @return Facture
     */
    public function setCreatedBy(\Pericles3Bundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy
     *
     * @param \Pericles3Bundle\Entity\User $updatedBy
     *
     * @return Facture
     */
    public function setUpdatedBy(\Pericles3Bundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
