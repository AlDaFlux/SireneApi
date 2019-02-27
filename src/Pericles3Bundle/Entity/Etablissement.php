<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;


use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;



/**
 * Etablissement
 *
 * @ORM\Table(name="etablissement")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\EtablissementRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Etablissement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    
    use SoftDeleteableEntity;
    use BlameableEntity;

        
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=255, nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    
    /**
     * @var string
     *
     * @ORM\Column(name="logo_fichier_name", type="string", length=255, nullable=true)
     */
    private $logo_fichier_name;

    
    /**
     * @var int
     *
     * @ORM\Column(name="capacite_acceuil", type="integer", nullable=true)
     */
    private $capaciteAcceuil;

    /**
     * @var int
     *
     * @ORM\Column(name="hebergement_complet", type="integer", nullable=true)
     */
    private $hebergementComplet;

    /**
     * @var int
     *
     * @ORM\Column(name="acceuil_jour", type="integer", nullable=true)
     */
    private $acceuilJour;

    /**
     * @var int
     *
     * @ORM\Column(name="accueil_temporaire", type="integer", nullable=true)
     */
    private $accueilTemporaire;
  
    /**
     * @var int
     *
     * @ORM\Column(name="nombre_etb", type="float", nullable=true)
     */
    private $nombreEtb;

    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_dernier_projet", type="datetime", nullable=true)
     */
    private $dateDernierProjet;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_derniere_evaluation_interne", type="datetime", nullable=true)
     */
    private $dateDerniereEvaluationInterne;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_derniere_evaluation_externe", type="datetime", nullable=true)
     */
    private $dateDerniereEvaluationExterne;
  
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Domaine", mappedBy="etablissement")
     */
    private $domaines;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DomaineExterne", mappedBy="etablissement")
     */
    private $domainesExterne;


    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="etablissements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentielPublic;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Patch", inversedBy="etablissements")
     */
    private $patch;
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Creai", inversedBy="etablissements")
     * @ORM\OrderBy({"nom" = "asc"})
     */
    private $creai;

    /**
     * @var int
     *
     * @ORM\Column(name="delegation_creai", type="integer", nullable=true)
     */
    private $delegationCreai;


    
    
    
    /**
     * @ORM\ManytoOne(targetEntity="Pericles3Bundle\Entity\Departement", inversedBy="etablissements")
     */
    private $departement;

    
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Gestionnaire", inversedBy="etablissements")
     */
    private $gestionnaire;
    
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\User", mappedBy="etablissement")
     */
    private $users;
    

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Preuve", mappedBy="etablissement")
     */
    private $preuves;
    
    
    
    
        
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\ObjectifOperationnel", mappedBy="etablissement")
     */
    private $objectifsOperationnel;
    
            
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DomaineObjectifStrategique", mappedBy="etablissement")
     */
    private $objectifsStrategique;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Bibliotheque", mappedBy="etablissement")
     */
    private $bibliotheques;
    
    
    /**
     * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\Finess", inversedBy="etablissement")
     * @ORM\JoinColumn(name="finess", referencedColumnName="code_finess", nullable=true) 
     */
    private $finess;
    


     
    /**
    * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\DemandeEtablissement", mappedBy="Etablissement")
    */
    private $demandeEtablissement;


    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ModeCotisation", inversedBy="etablissements")
     * @ORM\JoinColumn(nullable=false) 
     */
    private $modeCotisation;

    

    

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Pericles", mappedBy="etablissement")
     */
    private $pericles;
    
    
    
    
    
    
   
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=15, nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=15, nullable=true)
     */
    private $fax;
    
    
       
    /**
     * @var string
     *
     * @ORM\Column(name="directeur", type="string", nullable=true)
     */
    private $directeur;

    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Sauvegarde", mappedBy="etablissement")
     */
    private $sauvegardes;

    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User",  inversedBy="etablissements_cree")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CreatedBy;
    
    

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $CreatedDate;

    
    
    /**
     * @var string
     *
     * @ORM\Column(name="princip_contractualisations", type="text", nullable=true)
     */
    private $princip_contractualisations;

    /**
     * @var string
     *
     * @ORM\Column(name="princip_valeurs", type="text", nullable=true)
     */
    private $princip_valeurs;

    /**
     * @var string
     *
     * @ORM\Column(name="princip_objectifs", type="text", nullable=true)
     */
    private $princip_objectifs;

    /**
     * @var string
     *
     * @ORM\Column(name="princip_caractéristiques", type="text", nullable=true)
     */
    private $princip_caractéristiques;

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\StockageEtablissement", inversedBy="etablissements")
     */
    private $StockageEtablissement;
    

    
        
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\EtablissementCategory", inversedBy="etablissements")
     */
    private $category;

    
    /**
     * @var int
     *
     * @ORM\Column(name="size_upload_cache", type="integer")
     */
    private $sizeTotalFileUploadCache;
     

    
    /**
     * @var int
     *
     * @ORM\Column(name="nb_questions_repondues_cache", type="integer")
     */
    private $nbQuestionsReponduesCache;
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="nb_criteres_notes_cache", type="integer")
     */
    private $nbCriteresNotesCache;
    
    

    
    
    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\User", mappedBy="etablissementsPole")
     */
    private $userPole;


 
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Facture", mappedBy="etablissement")
     */
    private $factures;

       
         
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\FacturePresta", mappedBy="etablissement")
     */
    private $facturePrestas;

    
    
    
      
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\PatchToDo",mappedBy="etablissement")
     */
    private $patchToDo;

    
  

    /**
     * @ORM\Column(type="boolean")
     */
    protected $qualiEval;
           

    
    
    public function __construct()
    {
    	$this->domaines = new ArrayCollection();
        $this->delegationCreai=0;
        $this->sizeTotalFileUploadCache=0;
        $this->nbQuestionsReponduesCache=0;
        $this->nbCriteresNotesCache=0;
        $this->setQualiEval(false);
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Etablissement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;}

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Etablissement
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Etablissement
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Etablissement
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set capaciteAcceuil
     *
     * @param integer $capaciteAcceuil
     *
     * @return Etablissement
     */
    public function setCapaciteAcceuil($capaciteAcceuil)
    {
    	if ($capaciteAcceuil == '')
    		$capaciteAcceuil = null;
        $this->capaciteAcceuil = $capaciteAcceuil;

        return $this;
    }

    /**
     * Get capaciteAcceuil
     *
     * @return int
     */
    public function getCapaciteAcceuil()
    {
        return $this->capaciteAcceuil;
    }

    /**
     * Set hebergementComplet
     *
     * @param integer $hebergementComplet
     *
     * @return Etablissement
     */
    public function setHebergementComplet($hebergementComplet)
    {
    	if ($hebergementComplet == '')
    		$hebergementComplet = null;
        $this->hebergementComplet = $hebergementComplet;

        return $this;
    }

    /**
     * Get hebergementComplet
     *
     * @return int
     */
    public function getHebergementComplet()
    {
        return $this->hebergementComplet;
    }

    /**
     * Set acceuilJour
     *
     * @param integer $acceuilJour
     *
     * @return Etablissement
     */
    public function setAcceuilJour($acceuilJour)
    {
    	if ($acceuilJour == '')
    		$acceuilJour = null;
        $this->acceuilJour = $acceuilJour;

        return $this;
    }

    /**
     * Get acceuilJour
     *
     * @return int
     */
    public function getAcceuilJour()
    {
        return $this->acceuilJour;
    }

    /**
     * Set accueilTemporaire
     *
     * @param integer $accueilTemporaire
     *
     * @return Etablissement
     */
    public function setAccueilTemporaire($accueilTemporaire)
    {
    	if ($accueilTemporaire == '')
    		$accueilTemporaire = null;
        $this->accueilTemporaire = $accueilTemporaire;

        return $this;
    }

    /**
     * Get accueilTemporaire
     *
     * @return int
     */
    public function getAccueilTemporaire()
    {
        return $this->accueilTemporaire;
    }

    /**
     * Set uniteProtege
     *
     * @param integer $uniteProtege
     *
     * @return Etablissement
     */
    public function setUniteProtege($uniteProtege)
    {
    	if ($uniteProtege == '')
    		$uniteProtege = null;
        $this->uniteProtege = $uniteProtege;

        return $this;
    }

    /**
     * Get uniteProtege
     *
     * @return int
     */
    public function getUniteProtege()
    {
        return $this->uniteProtege;
    }

    /**
     * Set serviceDomicile
     *
     * @param integer $serviceDomicile
     *
     * @return Etablissement
     */
    public function setServiceDomicile($serviceDomicile)
    {
    	if ($serviceDomicile == '')
    		$serviceDomicile = null;
        $this->serviceDomicile = $serviceDomicile;

        return $this;
    }

    /**
     * Get serviceDomicile
     *
     * @return int
     */
    public function getServiceDomicile()
    {
        return $this->serviceDomicile;
    }

    /**
     * Set nombreEtb
     *
     * @param integer $nombreEtb
     *
     * @return Etablissement
     */
    public function setNombreEtb($nombreEtb)
    {
    	if ($nombreEtb == '')
    		$nombreEtb = null;
        $this->nombreEtb = $nombreEtb;

        return $this;
    }

    /**
     * Get nombreEtb
     *
     * @return int
     */
    public function getNombreEtb()
    {
        return $this->nombreEtb;
    }

    /**
     * Set dateDernierProjet
     *
     * @param \DateTime $dateDernierProjet
     *
     * @return Etablissement
     */
    public function setDateDernierProjet($dateDernierProjet)
    {
    	if ($dateDernierProjet == '')
    		$dateDernierProjet = null;
        $this->dateDernierProjet = $dateDernierProjet;

        return $this;
    }

    /**
     * Get dateDernierProjet
     *
     * @return \DateTime
     */
    public function getDateDernierProjet()
    {
        return $this->dateDernierProjet;
    }

    public function setDateDerniereConvention($dateDerniereConvention)
    {
    	if ($dateDerniereConvention == '')
    		$dateDerniereConvention = null;
        $this->dateDerniereConvention = $dateDerniereConvention;

        return $this;
    }

    /**
     * Get dateDerniereConvention
     *
     * @return \DateTime
     */
    public function getDateDerniereConvention()
    {
        return $this->dateDerniereConvention;
    }

    /**
     * Set dateDerniereEvaluationInterne
     *
     * @param \DateTime $dateDerniereEvaluationInterne
     *
     * @return Etablissement
     */
    public function setDateDerniereEvaluationInterne($dateDerniereEvaluationInterne)
    {
    	if ($dateDerniereEvaluationInterne == '')
    		$dateDerniereEvaluationInterne = null;
        $this->dateDerniereEvaluationInterne = $dateDerniereEvaluationInterne;

        return $this;
    }

    /**
     * Get dateDerniereEvaluationInterne
     *
     * @return \DateTime
     */
    public function getDateDerniereEvaluationInterne()
    {
        return $this->dateDerniereEvaluationInterne;
    }

    /**
     * Set dateDerniereEvaluationExterne
     *
     * @param \DateTime $dateDerniereEvaluationExterne
     *
     * @return Etablissement
     */
    public function setDateDerniereEvaluationExterne($dateDerniereEvaluationExterne)
    {
    	if ($dateDerniereEvaluationExterne == '')
    		$dateDerniereEvaluationExterne = null;
        $this->dateDerniereEvaluationExterne = $dateDerniereEvaluationExterne;

        return $this;
    }

    /**
     * Get dateDerniereEvaluationExterne
     *
     * @return \DateTime
     */
    public function getDateDerniereEvaluationExterne()
    {
        return $this->dateDerniereEvaluationExterne;
    }

    /**
     * Set girMoyen
     *
     * @param integer $girMoyen
     *
     * @return Etablissement
     */
    public function setGirMoyen($girMoyen)
    {
    	if ($girMoyen == '')
    		$girMoyen = null;
        $this->girMoyen = $girMoyen;

        return $this;
    }

    /**
     * Get girMoyen
     *
     * @return int
     */
    public function getGirMoyen()
    {
        return $this->girMoyen;
    }

    /**
     * Set dateEvaluationGmp
     *
     * @param \DateTime $dateEvaluationGmp
     *
     * @return Etablissement
     */
    public function setDateEvaluationGmp($dateEvaluationGmp)
    {
    	if ($dateEvaluationGmp == '')
    		$dateEvaluationGmp = null;
        $this->dateEvaluationGmp = $dateEvaluationGmp;

        return $this;
    }

    /**
     * Get dateEvaluationGmp
     *
     * @return \DateTime
     */
    public function getDateEvaluationGmp()
    {
        return $this->dateEvaluationGmp;
    }

    /**
     * Set pathosMoyen
     *
     * @param integer $pathosMoyen
     *
     * @return Etablissement
     */
    public function setPathosMoyen($pathosMoyen)
    {
    	if ($pathosMoyen == '')
    		$pathosMoyen = null;
        $this->pathosMoyen = $pathosMoyen;

        return $this;
    }

    /**
     * Get pathosMoyen
     *
     * @return int
     */
    public function getPathosMoyen()
    {
        return $this->pathosMoyen;
    }

    /**
     * Set dateEvaluationPmp
     *
     * @param \DateTime $dateEvaluationPmp
     *
     * @return Etablissement
     */
    public function setDateEvaluationPmp($dateEvaluationPmp)
    {
    	if ($dateEvaluationPmp == '')
    		$dateEvaluationPmp = null;
        $this->dateEvaluationPmp = $dateEvaluationPmp;

        return $this;
    }

    /**
     * Get dateEvaluationPmp
     *
     * @return \DateTime
     */
    public function getDateEvaluationPmp()
    {
        return $this->dateEvaluationPmp;
    }
    
    public function addDomaine(Domaine $domaine)
    {
    	$this->domaines[] = $domaine;
    
    	$domaine->setEtablissement($this);
    
    	return $this;
    }
    
    public function removeDomaine(Domaine $domaine)
    {
    	$this->domaines->removeElement($domaine);
    }
    
    
    
    public function getDomaines()
    {
        if ($this->getReferentielPublic()) return $this->getDomainesReferentiel($this->getReferentielPublic());
        else return($this->domaines);
    }
    
    
    public function getNbDomaines()
    {
        return(count($this->domaines));
    }
    
    
    public function getDomainesObsolete()
    {
        
        $domaines=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->domaines as $domaine ) 
        {
            if ($domaine->getReferentielPublic()!=$this->getReferentielPublic())
            {
                $domaines->Add($domaine);
            }
        }
        return $domaines;
    }
    
    
    
    
    public function getDomainesReferentiel(ReferentielPublic $ReferentielPublic)
    {
         $domaines=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->domaines as $domaine ) 
        {
            if ($domaine->getReferentielPublic()==$ReferentielPublic)
            {
                $domaines->Add($domaine);
            }
        }
        return $domaines;
                
    }
    
                

    public function getDomaineByOrdre($ordre)
    {
        foreach ($this->getDomaines() as $domaine)
        {
            if ($domaine->getOrdre()==$ordre) {return($domaine);}
        }
    }

                
     

    /**
     * Set referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     *
     * @return Etablissement
     */
    public function setReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic = null)
    {
        $this->referentielPublic = $referentielPublic;

        return $this;
    }
 
    /**
     * Get referentielPublic
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getReferentielPublic()
    {
        return $this->referentielPublic;
    }
    
    
    public function getReferentielExterne()
    {
        return $this->getReferentielPublic()->getReferentielExterne();
    }

    
    public function getPublic()
    {
        if ($this->referentielPublic)
        {
            return $this->referentielPublic->GetPublic();
        }
    }

  
    

    /**
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return Etablissement
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
    
    
    public function getStatus()
    {
        //Status de la Domaine: 1-Non démarré, 2-En cours, 3:Saisi

        $isOneDomaineEncours =false;
        $isOneDomaineNonCommence = false;
        $isOneDomaineTermine =false;

        foreach ($this->getDomaines() as $Domaine ) {
            $DomaineState = $Domaine->getStatus();

            switch ($DomaineState) {
                case 1:
                    if (!$isOneDomaineNonCommence)
                        $isOneDomaineNonCommence =true;
                    break;
                case 2:
                    if (!$isOneDomaineEncours)
                        $isOneDomaineEncours =true;
                    break;
                case 3:
                    if (!$isOneDomaineTermine)
                        $isOneDomaineTermine =true;
                    break;
            }
        }

        if ($isOneDomaineEncours||($isOneDomaineNonCommence && $isOneDomaineTermine) )
            return 2;

        if(!$isOneDomaineEncours && !$isOneDomaineNonCommence)
            return 3;

        return 1;
    }
    
    
    public function getSaisieTerminee()
    {
        return($this->getNbCriteres()==$this->getNbCriteresNotesCache());
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
     * Add user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return Etablissement
     */
    public function addUser(\Pericles3Bundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Pericles3Bundle\Entity\User $user
     */
    public function removeUser(\Pericles3Bundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    
    public function getNbUsers()
    {
        return count($this->users);
    }
    
   
    
                
    /**
     * Add objectifsStrategique
     *
     * @param \Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsStrategique
     *
     * @return Etablissement
     */
    public function addObjectifsStrategique(\Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsStrategique)
    {
        $this->objectifsStrategique[] = $objectifsStrategique;

        return $this;
    }

    /**
     * Remove objectifsStrategique
     *
     * @param \Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsStrategique
     */
    public function removeObjectifsStrategique(\Pericles3Bundle\Entity\DomaineObjectifStrategique $objectifsStrategique)
    {
        $this->objectifsStrategique->removeElement($objectifsStrategique);
    }

    /**
     * Get objectifsStrategique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectifsStrategique()
    {
        return $this->objectifsStrategique;
    }
    
    public function getNbObjectifsStrategique()
    {
        return count($this->objectifsStrategique);
    }
    

    /**
     * Add bibliotheque
     *
     * @param \Pericles3Bundle\Entity\Bibliotheque $bibliotheque
     *
     * @return Etablissement
     */
    public function addBibliotheque(\Pericles3Bundle\Entity\Bibliotheque $bibliotheque)
    {
        $this->bibliotheques[] = $bibliotheque;

        return $this;
    }

    /**
     * Remove bibliotheque
     *
     * @param \Pericles3Bundle\Entity\Bibliotheque $bibliotheque
     */
    public function removeBibliotheque(\Pericles3Bundle\Entity\Bibliotheque $bibliotheque)
    {
        $this->bibliotheques->removeElement($bibliotheque);
    }

    /**
     * Get bibliotheques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBibliotheques()
    {
        return $this->bibliotheques;
    }
    
    /**
     * Get bibliotheques
     *
     * @return Integer
     */
    public function getNbBibliotheques()
    {
        return count($this->bibliotheques);
    }
    
    
    public function getNbBibliothequesFichier()
    {
        $total=0;
        foreach ($this->getBibliotheques() as $Bibliotheque) 
        {
            if ($Bibliotheque->GetFichier()<>'') $total+=1;
        }
        return $total;
    }
    
    
    
    
    
    
    
    
    
    
                

    /**
     * Get referentielExterneNiv1
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentielExterneNiv1()
    {
        if ($this->getReferentielExterne())
        {
            return ($this->getReferentielExterne()->getReferentielExterneNiv1());
        }
    }
    
    
    
    

    /**
     * Add domainesExterne
     *
     * @param \Pericles3Bundle\Entity\DomaineExterne $domainesExterne
     *
     * @return Etablissement
     */
    public function addDomainesExterne(\Pericles3Bundle\Entity\DomaineExterne $domainesExterne)
    {
        $this->domainesExterne[] = $domainesExterne;

        return $this;
    }

    /**
     * Remove domainesExterne
     *
     * @param \Pericles3Bundle\Entity\DomaineExterne $domainesExterne
     */
    public function removeDomainesExterne(\Pericles3Bundle\Entity\DomaineExterne $domainesExterne)
    {
        $this->domainesExterne->removeElement($domainesExterne);
    }

    /**
     * Get domainesExterne
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDomainesExterne()
    {
        return $this->domainesExterne;
    }
    
    
    public function getDomainesExterneObsolete()
    {
        $domaines=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->domainesExterne as $domaine ) 
        {
            if ($domaine->hasOnlyObsolete())
            {
                $domaines->Add($domaine);
            }
        }
        return $domaines;
    }
    
    
    
    
    
    public function getNbDomainesExterne()
    {
        return count($this->domainesExterne);
    }

                

    /**
     * Get codeFiness
     *
     * @return string
     */
    public function getCodeFiness()
    {
        if ($this->finess) return $this->finess;
    }

    
    /**
     * Set creai
     *
     * @param \Pericles3Bundle\Entity\Creai $creai
     *
     * @return Etablissement
     */
    public function setCreai(\Pericles3Bundle\Entity\Creai $creai = null)
    {
        $this->creai = $creai;

        return $this;
    }

    /**
     * Get creai
     *
     * @return \Pericles3Bundle\Entity\Creai
     */
    public function getCreai()
    {
        return $this->creai;
    }
    
      
    public function getCreaiCascade()
    {
        if ($this->creai) return $this->creai;
        elseif ($this->getGestionnaire())
        {
            if ($this->getGestionnaire()->GetCreai()) return $this->getGestionnaire()->GetCreai();
        }
    }
    
    /**
     * GRetourne le nombre d'objectifs strategiques
     *
     * @return integer
     */
    public function getNbObjectifsSrategique()
    {
        $total=0;
        foreach ($this->getDomaines() as $Domaine) 
        {
            $total+=$Domaine->getNbObjectifsSrategique();
        }
        return $total;
    }
    
    
    

    
    public function getNbObjectifsOperationnel()
    {
        return(count($this->objectifsOperationnel));
    }
    
    
    public function getNbCommentaires()
    {
        $total=0;
        foreach ($this->getDomaines() as $Domaine) 
        {
            $total+=$Domaine->getNbCommentaires();
        }
        return $total;
    }
    
    
    
    

    /**
     * Add preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     *
     * @return Etablissement
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

    /**
     * Get preuves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreuves()
    {
        return $this->preuves;
    }
    
    
    public function getPreuvesFichier()
    {
        $PreuvesFichier=array();
        foreach ($this->preuves as $preuve) 
        {
            if ($preuve->getFichier()) $PreuvesFichier[]=$preuve->getFichier();
            
        }
        return array_unique($PreuvesFichier);
    }
    
    
                
    
    
    

    /**
     * Add sauvegarde
     *
     * @param \Pericles3Bundle\Entity\Sauvegarde $sauvegarde
     *
     * @return Etablissement
     */
    public function addSauvegarde(\Pericles3Bundle\Entity\Sauvegarde $sauvegarde)
    {
        $this->sauvegardes[] = $sauvegarde;

        return $this;
    }

    /**
     * Remove sauvegarde
     *
     * @param \Pericles3Bundle\Entity\Sauvegarde $sauvegarde
     */
    public function removeSauvegarde(\Pericles3Bundle\Entity\Sauvegarde $sauvegarde)
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
    
    
    
    
    

    public function getMoyenneNotes(){
        $moyenne = 0;
        $domaines = $this->getDomaines();
        if (count($domaines)<=0)
            return 0;
        foreach ($domaines as $domaine) {
            $moyenne+=$domaine->getMoyenneNotes();
        }
        $moyenne=$moyenne/count($domaines);
        return round($moyenne, 1);
    }


    
    public function GetUploadFolderPath() 
    {
        return ("st01/etablissement_".$this->getId());
    }
    
    
    
    
    
    /**
     *
     */
    public function getNbSauvegardes()
    {
        return count($this->sauvegardes);
    }
    
    
    /**
     *
     */
    public function getSauvegardesReferentielDesuet()
    {
        $sauvegardes=  new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->GetSauvegardes() as $sauvegarde) 
        {
            if ($sauvegarde->getReferentielDesuet()) $sauvegardes->Add($sauvegarde);
        }
        return $sauvegardes;
    }
    
    
    /**
     *
     */
    public function getNbSauvegardesReferentielDesuet()
    {
        return count($this->getSauvegardesReferentielDesuet());
    }
    
    
    
    
    /**
     *
     */
    public function getLastSauvegarde()
    {
        if ($this->sauvegardes) return $this->sauvegardes[count($this->sauvegardes)-1];
    }
    
    
    /**
     *
     */
    public function getLastSauvegardeAgo($format)
    {
        $maintenant = new \DateTime("now");
        $interval=$this->getLastSauvegarde()->GetDateCreate()->diff($maintenant);
//        $interval->format('%R%a days');
        return($interval->format($format));
    }
    
    
    
    

    /**
     * Set logoFichierName
     *
     * @param string $logoFichierName
     *
     * @return Etablissement
     */
    public function setLogoFichierName($logoFichierName)
    {
        $this->logo_fichier_name = $logoFichierName;

        return $this;
    }

    /**
     * Get logoFichierName
     *
     * @return string
     */
    public function getLogoFichierName()
    {
        return $this->logo_fichier_name;
    }
    
    
    public function hasLogo()
    {
        return ($this->logo_fichier_name);
    }
    
    /**
     * Get Logo POath
     *
     * @return string
     */
    public function getLogoPath()
    {
        return('/upload/'.$this->GetUploadFolderPath()."/".$this->getLogoFichierName());
    }
    
    
    
    

    /**
     * Set finess
     *
     * @param \Pericles3Bundle\Entity\Finess $finess
     *
     * @return Etablissement
     */
    public function setFiness(\Pericles3Bundle\Entity\Finess $finess = null)
    {
        $this->finess = $finess;

        return $this;
    }

    /**
     * Get finess
     *
     * @return \Pericles3Bundle\Entity\Finess
     */
    public function getFiness()
    {
        return $this->finess;
    }
    
    public function getDemande()
    {
        if ($this->finess)
        {
            return $this->finess->getDemandesEtablissement();
        }
    }
    
    
    
    

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Etablissement
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Etablissement
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set directeur
     *
     * @param string $directeur
     *
     * @return Etablissement
     */
    public function setDirecteur($directeur)
    {
        $this->directeur = $directeur;

        return $this;
    }

    /**
     * Get directeur
     *
     * @return string
     */
    public function getDirecteur()
    {
        return $this->directeur;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Etablissement
     */
    public function setCreatedDate($createdDate)
    {
        $this->CreatedDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->CreatedDate;
    }

    /**
     * Set createdBy
     *
     * @param \Pericles3Bundle\Entity\User $createdBy
     *
     * @return Etablissement
     */
    public function setCreatedBy(\Pericles3Bundle\Entity\User $createdBy)
    {
        $this->CreatedBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->CreatedBy;
    }

    /**
     * Set principContractualisations
     *
     * @param string $principContractualisations
     *
     * @return Etablissement
     */
    public function setPrincipContractualisations($principContractualisations)
    {
        $this->princip_contractualisations = $principContractualisations;

        return $this;
    }

    /**
     * Get principContractualisations
     *
     * @return string
     */
    public function getPrincipContractualisations()
    {
        return $this->princip_contractualisations;
    }

    /**
     * Set principValeurs
     *
     * @param string $principValeurs
     *
     * @return Etablissement
     */
    public function setPrincipValeurs($principValeurs)
    {
        $this->princip_valeurs = $principValeurs;

        return $this;
    }

    /**
     * Get principValeurs
     *
     * @return string
     */
    public function getPrincipValeurs()
    {
        return $this->princip_valeurs;
    }

    /**
     * Set principObjectifs
     *
     * @param string $principObjectifs
     *
     * @return Etablissement
     */
    public function setPrincipObjectifs($principObjectifs)
    {
        $this->princip_objectifs = $principObjectifs;

        return $this;
    }

    /**
     * Get principObjectifs
     *
     * @return string
     */
    public function getPrincipObjectifs()
    {
        return $this->princip_objectifs;
    }

    /**
     * Set principCaractéristiques
     *
     * @param string $principCaractéristiques
     *
     * @return Etablissement
     */
    public function setPrincipCaractéristiques($principCaractéristiques)
    {
        $this->princip_caractéristiques = $principCaractéristiques;
        return $this;
    }

    /**
     * Get principCaractéristiques
     *
     * @return string
     */
    public function getPrincipCaractéristiques()
    {
        return $this->princip_caractéristiques;
    }

    /**
     * Set departement
     *
     * @param \Pericles3Bundle\Entity\Departement $departement
     *
     * @return Etablissement
     */
    public function setDepartement(\Pericles3Bundle\Entity\Departement $departement = null)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \Pericles3Bundle\Entity\Departement
     */
    public function getDepartement()
    {
        return $this->departement;
    }
    

    /**
     * Set delegationCreai
     *
     * @param integer $delegationCreai
     *
     * @return Etablissement
     */
    public function setDelegationCreai($delegationCreai)
    {
        $this->delegationCreai = $delegationCreai;

        return $this;
    }

    /**
     * Get delegationCreai
     *
     * @return integer
     */
    public function getDelegationCreai()
    {
        return $this->delegationCreai;
    }

    /**
     * Add objectifsOperationnel
     *
     * @param \Pericles3Bundle\Entity\ObjectifOperationnel $objectifsOperationnel
     *
     * @return Etablissement
     */
    public function addObjectifsOperationnel(\Pericles3Bundle\Entity\ObjectifOperationnel $objectifsOperationnel)
    {
        $this->objectifsOperationnel[] = $objectifsOperationnel;

        return $this;
    }

    /**
     * Remove objectifsOperationnel
     *
     * @param \Pericles3Bundle\Entity\ObjectifOperationnel $objectifsOperationnel
     */
    public function removeObjectifsOperationnel(\Pericles3Bundle\Entity\ObjectifOperationnel $objectifsOperationnel)
    {
        $this->objectifsOperationnel->removeElement($objectifsOperationnel);
    }

    /**
     * Get objectifsOperationnel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectifsOperationnel()
    {
        return $this->objectifsOperationnel;
    }
    
    public function getNbConstats()
    {
        $nb=0;
        foreach ($this->domaines as $domaine)
        {
            foreach ($domaine->getDimensions() as  $dimension)
            {
                foreach ($dimension->getCriteres() as $critere)
                {
                    $nb+=$critere->getNbConstats();
                }
            }
        }
        return $nb;
    }
    
    
         
    public function getNbPreuves()
    {
        return count($this->preuves);
    }
    
    
    public function getNbCriteres()
    {
        return ($this->referentielPublic->getNbCriteresCache());
    }
    
    public function getNbCriteresCount()
    {
        $nb=0;
        foreach ($this->domaines as $domaine)
        {
            foreach ($domaine->getDimensions() as $dimension)
            {
                $nb+=count($dimension->GetCriteres());
            }
        }
        return ($nb);
    }
    
    
    public function getCriteres()
    {

        $criteres=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getDomaines() as $domaine)
        {
            foreach ($domaine->getDimensions() as $dimension)
            {
                foreach ($dimension->getCriteres() as $critere)
                {
                    $criteres->Add($critere);
                }
            }
        }
        return ($criteres);
    }
    
    
    
    public function getNbQuestionsCount()
    {
        $nb=0;
        foreach ($this->domaines as $domaine)
        {
            foreach ($domaine->getDimensions() as $dimension)
            {
                foreach ($dimension->getCriteres() as $critere)
                {
                    $nb+=count($critere->GetQuestions());
                }
            }
        }
        return ($nb);
    }
    
    

    
    public function getNbQuestions()
    {
        return ($this->referentielPublic->getNbQuestionsCache());
    }
                

    
    

    public function getNbCriteresWithNote()
    {
        $total=0;
        foreach ($this->getDomaines() as $Domaine ) {
            $total+=$Domaine->getNbCriteresWithNote();
        }
        return ($total);
    }
    
    
    
    /*
    public function getNbQuestions(){
        $nb = 0;
        foreach ($this->getDomaines() as $Domaine ) {
            $nb+=$Domaine->getNbQuestions();
        }
        return ($nb);
    }
    */
    
    
    public function getNbQuestionsRepondues(){
        $nb = 0;
        foreach ($this->getDomaines() as $Domaine ) {
            $nb+=$Domaine->getNbQuestionsRepondues();
        }
        return ($nb);
    }

    
    public function getNbCommentairesDomaine()
    {
        $nb=0;
        foreach ($this->getDomaines() as $Domaine )
        {
            $nb+=$Domaine->getNbCommentaires();
        }
        return($nb);
    }
        
    
    
    
    
    // revoi vrai si pas de notes et pas de réponses
    public function getSwitchableRef()
    {
        return($this->getEvaluationVide() && $this->getPasDelementsRatacheRef());
    }
    
    public function getEvaluationVide()
    {
        return(! ($this->getNbCriteresWithNote()+$this->getNbQuestionsRepondues()));
    }
    
    
    public function getPasDelementsRatacheRef()
    {
        $nb=0;
        $nb+=$this->getNbObjectifsStrategique();
        $nb+=$this->getNbConstats();
        $nb+=$this->getNbSauvegardes();
  
        $nb+=$this->getNbCommentairesDomaine();
        foreach ($this->getDomaines() as $Domaine )
        {
            $nb+=$Domaine->getNbPreuves();
            foreach ($Domaine->getDimensions() as $Dimension  )
            {
                foreach ($Dimension->getCriteres() as $Critere )
                {
                    $nb+=$Critere->getNbObjectifsOperationnel();
                    $nb+=$Critere->getNbPreuves();
                }
            }
        }
        return(! $nb);
    }
        
    
    
    
    
      
    public function GetMessageCantDelete()
    {
        $message="";
        if ($this->GetNbCommentairesDomaine()) $message.="<li>L'établissement des des commentaires dans les domaines";
        if ($this->GetNbBibliotheques()) $message.="<li>L'établissement des entrées dans la bibliotheque";
        if ($this->GetNbConstats()) $message.="<li>L'établissement a des constats";
        if ($this->getNbObjectifsStrategique()) $message.="<li>L'établissement a des objectifs stratégiques";
        if ($this->getNbObjectifsOperationnel()) $message.="<li>L'établissement a  des objectifs opérationnels";
        if ($this->GetNbPreuves()) $message.="<li>L'établissement contient des preuves";
        if ($this->GetNbSauvegardes()) $message.="<li>L'établissement a des sauvegardes";
        if ($message)  return("<ul>++++".$message."</ul>");
    }
    

    public function getCantDelete()
    {
        return($this->GetNbBibliotheques()+
                $this->GetNbPreuves()+
                $this->GetNbConstats() + 
                $this->getNbObjectifsStrategique() + 
                $this->getNbObjectifsOperationnel()+
                $this->GetNbSauvegardes());
    }
    
    
    

    /**
     * Set demandeEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandeEtablissement
     *
     * @return Etablissement
     */
    public function setDemandeEtablissement(\Pericles3Bundle\Entity\DemandeEtablissement $demandeEtablissement = null)
    {
        $this->demandeEtablissement = $demandeEtablissement;
        $demandeEtablissement->setEtablissement($this);
        return $this;
    }

    /**
     * Get demandeEtablissement
     *
     * @return \Pericles3Bundle\Entity\DemandeEtablissement
     */
    public function getDemandeEtablissement()
    {
        return $this->demandeEtablissement;
    }
    
    


    
    

    /**
     * Set stockageEtablissement
     *
     * @param \Pericles3Bundle\Entity\StockageEtablissement $stockageEtablissement
     *
     * @return Etablissement
     */
    public function setStockageEtablissement(\Pericles3Bundle\Entity\StockageEtablissement $stockageEtablissement = null)
    {
        $this->StockageEtablissement = $stockageEtablissement;

        return $this;
    }

    /**
     * Get stockageEtablissement
     *
     * @return \Pericles3Bundle\Entity\StockageEtablissement
     */
    public function getStockageEtablissement()
    {
        return $this->StockageEtablissement;
    }
    
    
    public function sizeMaxUpload()
    {
        return($this->getStockageEtablissement()->getCapacite());
    }


    /**
     * Set sizeTotalFileUploadCache
     *
     * @param integer $sizeTotalFileUploadCache
     *
     * @return Etablissement
     */
    public function setSizeTotalFileUploadCache($sizeTotalFileUploadCache)
    {
        $this->sizeTotalFileUploadCache = $sizeTotalFileUploadCache;

        return $this;
    }

    /**
     * Get sizeTotalFileUploadCache
     *
     * @return integer
     */
    public function getSizeTotalFileUploadCache()
    {
        return $this->sizeTotalFileUploadCache;
    }

    /**
     * Set category
     *
     * @param \Pericles3Bundle\Entity\EtablissementCategory $category
     *
     * @return Etablissement
     */
    public function setCategory(\Pericles3Bundle\Entity\EtablissementCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Pericles3Bundle\Entity\EtablissementCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    
    public function IsReel()
    {
        return $this->category->getReel();
    }

    public function GetFinContrat()
    {
        return $this->category->getId()==6;
    }

    
    
    
    
    
    /**
     * Set nbQuestionsReponduesCache
     *
     * @param integer $nbQuestionsReponduesCache
     *
     * @return Etablissement
     */
    public function setNbQuestionsReponduesCache($nbQuestionsReponduesCache)
    {
        $this->nbQuestionsReponduesCache = $nbQuestionsReponduesCache;

        return $this;
    }

    /**
     * Get nbQuestionsReponduesCache
     *
     * @return integer
     */
    public function getNbQuestionsReponduesCache()
    {
        return $this->nbQuestionsReponduesCache;
    }

    /**
     * Set nbCriteresNotesCache
     *
     * @param integer $nbCriteresNotesCache
     *
     * @return Etablissement
     */
    public function setNbCriteresNotesCache($nbCriteresNotesCache)
    {
        $this->nbCriteresNotesCache = $nbCriteresNotesCache;

        return $this;
    }

    /**
     * Get nbCriteresNotesCache
     *
     * @return integer
     */
    public function getNbCriteresNotesCache()
    {
        return $this->nbCriteresNotesCache;
    }
    
    
      
    public function GenereCache()
    {
        $this->SetNbCriteresNotesCache($this->getNbCriteresWithNote());
        $this->SetNbQuestionsReponduesCache($this->GetNbQuestionsRepondues());
        
        
    }
                
    
                

    /**
     * Get userPole
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserPole()
    {
        return $this->userPole;
    }

    /**
     * Add userPole
     *
     * @param \Pericles3Bundle\Entity\User $userPole
     *
     * @return Etablissement
     */
    public function addUserPole(\Pericles3Bundle\Entity\User $userPole)
    {
        $this->userPole[] = $userPole;

        return $this;
    }

    /**
     * Remove userPole
     *
     * @param \Pericles3Bundle\Entity\User $userPole
     */
    public function removeUserPole(\Pericles3Bundle\Entity\User $userPole)
    {
        $this->userPole->removeElement($userPole);
    }
    
    
    
    
    public function getGraphLegend()
    {
//                return("'".$this->getOrdre().":".strstr($this->getNom(), ' ', true)."'");
        return($this->__toString());
    }
    
    
    public function getGraphData()
    {
        return($this->getMoyenneNotes());
    }

    
    public function getGraphSubData()
    {
        $datas= array();
        foreach ( $this->getDomaines() as $sub)
        {
            $datas[$sub->GetOrdre()]=$sub->getMoyenneNotes();
        }
        ksort($datas);
        return(implode(",",$datas));
    }

    public function getGraphSubLegend()
    {
        $datas= array();
        foreach ( $this->getDomaines() as $sub)
        {
            $datas[$sub->GetOrdre()]='"'.$sub->getGraphLegend().'"';
        }
        ksort($datas);
        return(implode(",",$datas));
    }

    
    public function getGraphSubNb()
    { 
        return(count($this->getDomaines()));
    }

    
    public function GetEtablissement()
    { 
        return($this);
    }

    

    /**
     * Add facture
     *
     * @param \Pericles3Bundle\Entity\Facture $facture
     *
     * @return Etablissement
     */
    public function addFacture(\Pericles3Bundle\Entity\Facture $facture)
    {
        $this->factures[] = $facture;

        return $this;
    }

    /**
     * Remove facture
     *
     * @param \Pericles3Bundle\Entity\Facture $facture
     */
    public function removeFacture(\Pericles3Bundle\Entity\Facture $facture)
    {
        $this->factures->removeElement($facture);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFactures()
    {
        return $this->factures;
    }
    
    public function getFacturesAll()
    {
        $factures=  new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->getFacturePrestas() as $presta) 
        {
                $factures->Add($presta->getFacture());
        }
        return $factures;
    }
    
    public function getFacturesNotAVoir()
    {
        $factures=  new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->getFacturePrestas() as $presta) 
        {
            if (! $presta->getFacture()->getAvoir()) $factures->Add($presta->getFacture());
        }
        return $factures;
    }
    
    public function getPrestasNotAVoir()
    {
        $prestas=  new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->getFacturePrestas() as $presta) 
        {
            if (! $presta->getFacture()->getAvoir()) $prestas->Add($presta);
        }
        return $prestas;
    }
    
    public function getNbPrestasNotAVoir()
    {
        return(count($this->getPrestasNotAVoir()));
    }
    
    
    
    public function getNbFacturesNotAVoir()
    {
        return(count($this->getFacturesNotAVoir()));
                
    }
    
    
    
    
    
    

    /**
     * Add facturePresta
     *
     * @param \Pericles3Bundle\Entity\FacturePresta $facturePresta
     *
     * @return Etablissement
     */
    public function addFacturePresta(\Pericles3Bundle\Entity\FacturePresta $facturePresta)
    {
        $this->facturePrestas[] = $facturePresta;

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
    
    
    
    public function getNbFacturePrestas()
    {
        return count($this->facturePrestas);
    }
    
    public function getLastFacture()
    {
        
        $last_facture=$this->getLastFacturePresta();
        if ($last_facture) return($last_facture->getFacture ());
    }
    
    
    
    
    public function getLastFacturePresta()
    {
        $lastdate=0;
        $last_facture=null;
        foreach ($this->getPrestasNotAVoir() as $presta)
        {
            if ($presta->getFacture()->getDateEmission()->getTimestamp()>$lastdate)
            {
                $last_facture=$presta;
            }
        }
        return $last_facture;
    }
    
    public function getFirstFacturePresta()
    {
        $lastdate=0;
        $last_facture=null;
        foreach ($this->facturePrestas as $presta)
        {
            if ($lastdate==0)
            {
                $lastdate = $presta->getDateEmission()->getTimestamp();
                $last_facture=$presta;
            }
            if ($presta->getDateEmission()->getTimestamp()<$lastdate)
            {
                $last_facture=$presta;
            }
        }
        return $last_facture;
    }
    
    
    public function getNewFactureRenouvellementIndice()
    {
        $last_facture=$this->getLastFacturePresta();
        if ($last_facture) return($last_facture->getRenouvellement()+1);
        else return(0);
    }
    
    
    
    

    /**
     * Set modeCotisation
     *
     * @param \Pericles3Bundle\Entity\ModeCotisation $modeCotisation
     *
     * @return Etablissement
     */
    public function setModeCotisation(\Pericles3Bundle\Entity\ModeCotisation $modeCotisation = null)
    {
        $this->modeCotisation = $modeCotisation;

        return $this;
    }

    /**
     * Get modeCotisation
     *
     * @return \Pericles3Bundle\Entity\ModeCotisation
     */
    public function getModeCotisation()
    {
        return $this->modeCotisation;
    }
    
    
    public function getFacturable()
    {
        return ($this->getModeCotisation()->GetId()!=13);
    }
    
    
    
    public function getMontantFirst()
    {
        return $this->getModeCotisation()->getMontantFirst();
    }
    
    public function getMontantRenew()
    {
        return $this->getModeCotisation()->getMontantRenew();
    }
    
    

    /**
     * Set patch
     *
     * @param \Pericles3Bundle\Entity\Patch $patch
     *
     * @return Etablissement
     */
    public function setPatch(\Pericles3Bundle\Entity\Patch $patch = null)
    {
        $this->patch = $patch;

        return $this;
    }

    /**
     * Get patch
     *
     * @return \Pericles3Bundle\Entity\Patch
     */
    public function getPatch()
    {
        return $this->patch;
    }
    
    public function getIsPatched()
    {
        return $this->patch;
    }
    
    
    public function getPatchable()
    {
        return count($this->getPatchs());
    }
    
    public function getPatchs()
    {
        return $this->getReferentielPublic()->getPatchSources();
    }
    

    public function getDefautPatch()
    {
        $cible=$this->getReferentielPublicCible();
        foreach ($this->getPatchs() as $patch)
        {
            if ($patch->GetCible()->GetId()==$cible->GetId()) return($patch);
        }
    }
    
    
    public function getReferentielPublicCibleMemeBranche()
    {
        return($this->getReferentielPublicCible()->getTheLastGood()==$this->getReferentielPublic()->getTheLastGood());
    }
     
    
    
    
    
    
    
    
    public function getReferentielPublicCible()
    {
        $refPublic=null;
        
        if ($this->getFiness())
        {
           $refPublic=$this->getFiness()->getReferentielPublicDefaut();
        }
        
        if (! $refPublic and $this->getReferentielPublic()->getFiniAndLast())
        {
            $refPublic= $this->getReferentielPublic();
        }
        
        if (! $refPublic)
        {
            $refPublic= $this->getReferentielPublic()->getTheLastGood();
        }
        
        
        
        return($refPublic);
    }
    
     public function GetAMigrer()
    {
        return($this->getReferentielPublicCible()<>$this->getReferentielPublic());
    }
    
    
    
    
    
    
    
    


    /**
     * Add pericle
     *
     * @param \Pericles3Bundle\Entity\Pericles $pericle
     *
     * @return Etablissement
     */
    public function addPericle(\Pericles3Bundle\Entity\Pericles $pericle)
    {
        $this->pericles[] = $pericle;

        return $this;
    }

    /**
     * Remove pericle
     *
     * @param \Pericles3Bundle\Entity\Pericles $pericle
     */
    public function removePericle(\Pericles3Bundle\Entity\Pericles $pericle)
    {
        $this->pericles->removeElement($pericle);
    }

    /**
     * Get pericles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPericles()
    {
        return $this->pericles;
    }

    /**
     * Add patchToDo
     *
     * @param \Pericles3Bundle\Entity\PatchToDo $patchToDo
     *
     * @return Etablissement
     */
    public function addPatchToDo(\Pericles3Bundle\Entity\PatchToDo $patchToDo)
    {
        $this->patchToDo[] = $patchToDo;

        return $this;
    }

    /**
     * Remove patchToDo
     *
     * @param \Pericles3Bundle\Entity\PatchToDo $patchToDo
     */
    public function removePatchToDo(\Pericles3Bundle\Entity\PatchToDo $patchToDo)
    {
        $this->patchToDo->removeElement($patchToDo);
    }

    /**
     * Get patchToDo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatchToDo()
    {
        return $this->patchToDo;
    }
    
    
     public function getPatchToDoAFaire()
    {
         foreach ($this->getPatchToDo() as $patchToDo) 
         {
             if ($patchToDo->GetAFaire()) return($patchToDo);
         }
    }
    
    
    
    
    
    

    /**
     * Set qualiEval
     *
     * @param boolean $qualiEval
     *
     * @return Etablissement
     */
    public function setQualiEval($qualiEval)
    {
        $this->qualiEval = $qualiEval;

        return $this;
    }

    /**
     * Get qualiEval
     *
     * @return boolean
     */
    public function getQualiEval()
    {
        return $this->qualiEval;
    }
}
