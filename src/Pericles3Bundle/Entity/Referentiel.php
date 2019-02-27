<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Gedmo\Mapping\Annotation as Gedmo;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;





/**
 * Referentiel
 *
 * @ORM\Table(name="referentiel")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\ReferentielRepository")
 */
class Referentiel
{

    use SoftDeleteableEntity;
    use BlameableEntity;
         
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Gedmo\Versioned
     * 
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "La question ne peut dépasser {{ limit }} caracteres de long",
     * )
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer")
     */
    private $ordre;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\TypeReferentiel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeReferentiel;

    
    
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="referentiels")
    * @ORM\JoinColumn(nullable=false)
    */
    private $ReferentielPublic;

    
    
    
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielExterneNiv1", inversedBy="referentiels")
    * @ORM\JoinColumn(nullable=true)
    */
    private $ReferentielExterneNiv1;
    	
    
    
    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Domaine",  mappedBy="referentiel")
    * @ORM\JoinColumn(nullable=true)
    */
    private $domaines;
    
    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Dimension",  mappedBy="referentiel")
    * @ORM\JoinColumn(nullable=true)
    */
    private $dimensions;
    
    
    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Critere",  mappedBy="referentiel")
    * @ORM\JoinColumn(nullable=true)
    */
    private $criteres;
    

    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Question",  mappedBy="referentiel")
    * @ORM\JoinColumn(nullable=true)
    */
    private $questions;
    

    
    
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Referentiel", mappedBy="parent")
     */
    private $children;
    
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Referentiel", inversedBy="sourceChildren")
     */
    private $sourceParent;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Referentiel", mappedBy="sourceParent")
     */
    private $sourceChildren;
    
    
    
    
    
    
    
    
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom_court", type="string", length=255, nullable=true)
     */
    private $nom_court;
    
    /**
     * @var string
     *
     * @ORM\Column(name="reponse_oui", type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "La réponse positive ne peut dépasser {{ limit }} caracteres de long",
     * )
     */
    private $reponse_oui;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse_non", type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "La réponse négative  ne peut dépasser {{ limit }} caracteres de long",
     * )
     */
    private $reponse_non;
    

    
    
    /** 
     * @var boolean
     *
     * @ORM\Column(name="non_concerne", type="boolean")
     */
    private $non_concerne;
    
    
    /** 
     * @var boolean
     *
     * @ORM\Column(name="verifie", type="boolean")
     */
    private $verifie;
    

    
    

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\BibliothequeAncreai", inversedBy="Criterereferentiel")
     */
    private $RBPP;

      
    
    /**
     * @var string
     *
     * @ORM\Column(name="rbppp_comment", type="string", length=255, nullable=true)
     */
    private $rbppp_comment;
    
    
    
    
    
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\PatchReferentiel", mappedBy="patcheRefSource")
     */
    private $patchSources;
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\PatchReferentiel", mappedBy="patcheRefCible")
     */
    private $patchCibles;
    
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\PatchReferentiel", mappedBy="patcheRefAieul")
     */
    private $patchAieuls;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeDomaine", mappedBy="referentiel")
     */
    private $sauvegardeDomaine;
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeDimension", mappedBy="referentiel")
     */
    private $sauvegardeDimension;
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeCritere", mappedBy="referentiel")
     */
    private $sauvegardeCritere;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\SauvegardeQuestion", mappedBy="referentiel")
     */
    private $sauvegardeQuestion;
    

    

    
  // …
    
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
     * @return Referentiel
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
        return $this->nom;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return Referentiel
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }
    
    
    function GetNbChildren()
    {
         return count($this->children);
    }
    
    function GetMaxOrdreChildren()
    {
        $max=0;
        foreach ($this->getChildren() as $child)
        {
            $max=max($max,$child->getOrdre());
        }
        return($max);
    }

    function GetChildrenOrdreOK()
    {
        $max=0;
        $total=0;
        $nb=0;
            
        foreach ($this->getChildren() as $child)
        {
            $total+=$child->getOrdre();
            $max=max($max,$child->getOrdre());
            $nb++;
        }
            
        return((($max*$max+$max)/2)==$total);
            
    }
    function GetTmpNB()
    {
        $max=0;
        $total=0;
        $nb=0;
        foreach ($this->getChildren() as $child)
        {
            $total+=$child->getOrdre();
            $max=max($max,$child->getOrdre());
            $nb++;
        }
        $max=$nb;
        return($nb);
    }
    

    function GetNotFact()
    {
        $total=0;
        foreach ($this->getChildren() as $child)
        {
            $total+=$child->getOrdre();
        }
        return($total);
    }
    

    
    
    
    
    
    public function setTypeReferentiel(TypeReferentiel $typeReferentiel)
    {
    	$this->typeReferentiel = $typeReferentiel;
    
    	return $this;
    }
    
    public function TypeReferentiel()
    {
    	return $this->typeReferentiel;
    }

    /**
     * Get typeReferentiel
     *
     * @return \Pericles3Bundle\Entity\TypeReferentiel
     */
    public function getTypeReferentiel()
    {
        return $this->typeReferentiel;
    }

    /**
     * Set referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     *
     * @return Referentiel
     */
    public function setReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        $referentielPublic->addReferentiel($this);
        $this->ReferentielPublic = $referentielPublic;
        return $this;
    }

    /**
     * Get referentielPublic
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getReferentielPublic()
    {
        return $this->ReferentielPublic;
    }
    
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set parent
     *
     * @param \Pericles3Bundle\Entity\Referentiel $parent
     *
     * @return Referentiel
     */
    public function setParent(\Pericles3Bundle\Entity\Referentiel $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Pericles3Bundle\Entity\Referentiel $child
     *
     * @return Referentiel
     */
    public function addChild(\Pericles3Bundle\Entity\Referentiel $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Pericles3Bundle\Entity\Referentiel $child
     */
    public function removeChild(\Pericles3Bundle\Entity\Referentiel $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    

    /**
     * Set nomCourt
     *
     * @param string $nomCourt
     *
     * @return Referentiel
     */
    public function setNomCourt($nomCourt)
    {
        $this->nom_court = $nomCourt;

        return $this;
    }

    /**
     * Get nomCourt
     *
     * @return string
     */
    public function getNomCourt()
    {
        if ($this->nom_court)
        {
            return $this->nom_court;
        }
        else
        {
            return $this->nom;
        }
        
    }
    
    
    

    /**
     * Set reponseOui
     *
     * @param string $reponseOui
     *
     * @return Referentiel
     */
    public function setReponseOui($reponseOui)
    {
        $this->reponse_oui = $reponseOui;

        return $this;
    }

    /**
     * Get reponseOui
     *
     * @return string
     */
    public function getReponseOui()
    {
       return ($this->reponse_oui);
    }
    
    
    public function getReponseOuiLib()
    {
        if ($this->reponse_oui) return ($this->reponse_oui);
        return ($this->getNom()." : OUI");
    }
    
    public function getReponseOuiNonSaisi()
    {
        return((strlen($this->reponse_oui)>5 and strlen($this->reponse_non)>5 ));
    }
    

    /**
     * Set reponseNon
     *
     * @param string $reponseNon
     *
     * @return Referentiel
     */
    public function setReponseNon($reponseNon)
    {
        $this->reponse_non = $reponseNon;

        return $this;
    }

    /**
     * Get reponseNon
     *
     * @return string
     */
    public function getReponseNon()
    {
        return ($this->reponse_non);
    }
    
    public function getReponseNonLib()
    {
        if ($this->reponse_non) return ($this->reponse_non);
        return ($this->getNom()." : NON");
    }
    

    /**
     * Set nonConcerne
     *
     * @param boolean $nonConcerne
     *
     * @return Referentiel
     */
    public function setNonConcerne($nonConcerne)
    {
        $this->non_concerne = $nonConcerne;

        return $this;
    }

    /**
     * Get nonConcerne
     *
     * @return boolean
     */
    public function getNonConcerne()
    {
        return $this->non_concerne;
    }

    /**
     * Set referentielExterneNiv1
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterneNiv1 $referentielExterneNiv1
     *
     * @return Referentiel
     */
    public function setReferentielExterneNiv1(\Pericles3Bundle\Entity\ReferentielExterneNiv1 $referentielExterneNiv1 = null)
    {
        $this->ReferentielExterneNiv1 = $referentielExterneNiv1;

        return $this;
    }

    /**
     * Get referentielExterneNiv1
     *
     * @return \Pericles3Bundle\Entity\ReferentielExterneNiv1
     */
    public function getReferentielExterneNiv1()
    {
        return $this->ReferentielExterneNiv1;
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
     * Add domaine
     *
     * @param \Pericles3Bundle\Entity\Domaine $domaine
     *
     * @return Referentiel
     */
    public function addDomaine(\Pericles3Bundle\Entity\Domaine $domaine)
    {
        $this->domaines[] = $domaine;

        return $this;
    }

    /**
     * Remove domaine
     *
     * @param \Pericles3Bundle\Entity\Domaine $domaine
     */
    public function removeDomaine(\Pericles3Bundle\Entity\Domaine $domaine)
    {
        $this->domaines->removeElement($domaine);
    }

    /**
     * Get domaines
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDomaines()
    {
        return $this->domaines;
    }
            
    
    public function getDomaineEtablissement(Etablissement $Etablissement)
    {
        foreach ($this->domaines as $domaine)
        {
             if ($domaine->GetEtablissement()==$Etablissement ) return($domaine);
        }
    }
      
    public function getDimensionEtablissement(Etablissement $Etablissement)
    {
        foreach ($this->dimensions as $dimension)
        {
             if ($dimension->GetEtablissement()==$Etablissement ) return($dimension);
        }
    }
      
    public function getCritereEtablissement(Etablissement $Etablissement)
    {
        foreach ($this->criteres as $critere)
        {
             if ($critere->GetEtablissement()==$Etablissement ) return($critere);
        }
    }
    
         
    public function getQuestionEtablissement(Etablissement $Etablissement)
    {
        foreach ($this->getQuestions() as $question)
        {
            if ($question->GetEtablissement()==$Etablissement) return($question);
        }
    }
    
    
    
    
    
    
    
    public function getDomaineEtablissementReferentiel(Etablissement $Etablissement, ReferentielPublic $ReferentielPublic)
    {
        foreach ($this->domaines as $domaine)
        {
             if ($domaine->GetEtablissement()==$Etablissement && $domaine->GetReferentielPublic()==$ReferentielPublic ) return($domaine);
        }
    }
      
    public function getDimensionEtablissementReferentiel(Etablissement $Etablissement, ReferentielPublic $ReferentielPublic)
    {
        foreach ($this->dimensions as $dimension)
        {
             if ($dimension->GetEtablissement()==$Etablissement  && $dimension->GetReferentielPublic()==$ReferentielPublic ) return($dimension);
        }
    }
    
    
    public function getCritereEtablissementReferentiel(Etablissement $Etablissement, ReferentielPublic $ReferentielPublic)
    {
        foreach ($this->criteres as $critere)
        {
             if ($critere->GetEtablissement()==$Etablissement  && $critere->GetReferentielPublic()==$ReferentielPublic ) return($critere);
        }
    }
    
         
    public function getQuestionEtablissementReferentiel(Etablissement $Etablissement, ReferentielPublic $ReferentielPublic)
    {
        foreach ($this->getQuestions() as $question)
        {
            if ($question->GetEtablissement()==$Etablissement && $question->GetReferentielPublic()==$ReferentielPublic ) return($question);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    

    /**
     * Add dimension
     *
     * @param \Pericles3Bundle\Entity\Dimension $dimension
     *
     * @return Referentiel
     */
    public function addDimension(\Pericles3Bundle\Entity\Dimension $dimension)
    {
        $this->dimensions[] = $dimension;

        return $this;
    }

    /**
     * Remove dimension
     *
     * @param \Pericles3Bundle\Entity\Dimension $dimension
     */
    public function removeDimension(\Pericles3Bundle\Entity\Dimension $dimension)
    {
        $this->dimensions->removeElement($dimension);
    }

    /**
     * Get dimensions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Add critere
     *
     * @param \Pericles3Bundle\Entity\Critere $critere
     *
     * @return Referentiel
     */
    public function addCritere(\Pericles3Bundle\Entity\Critere $critere)
    {
        $this->criteres[] = $critere;

        return $this;
    }

    /**
     * Remove critere
     *
     * @param \Pericles3Bundle\Entity\Critere $critere
     */
    public function removeCritere(\Pericles3Bundle\Entity\Critere $critere)
    {
        $this->criteres->removeElement($critere);
    }

    /**
     * Get criteres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriteres()
    {
        return $this->criteres;
    }
    
    /*
    public function getCriteresEtablissement(Etablissement $Etablissement)
    {
        $criteres=new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->criteres as $critere)
        {
            if ($critere->GetEtablissement()===$Etablissement)
            {
                $criteres[]=$critere;
            }
        }
        return ($criteres);
    }
     * 
     */
    
    
    public function GetEvals()
    {
        switch ($this->GetTypeReferentiel()->getId())
        {
            case 1:
                return($this->getDomaines());
                    break;
            case 2:
                return($this->getDimensions());
                    break;
            case 3:
                return($this->getCriteres());
                    break;
            case 4:
                return($this->getQuestions());
                    break;
        }
    }
    
    public function GetEvaluationStarted()
    {
        return(count($this->GetEvals()));
    }
    
    
        
    /**
     * toString
     * @return string
     */
    public function GetNumero() 
    {
        if ($this->GetTypeReferentiel()->getId()==1)
        {
            return($this->GetOrdre());
        }
        else
        {
            return($this->getParent()->GetNumero().".".$this->GetOrdre());
        }
    }
    
    public function getDomaine()
    {
        if ($this->GetTypeReferentiel()->getId()==1)
        {
            return($this);
        }
        else
        {
            return($this->getParent()->getDomaine());
        }
    }
     
    public function getDimension()
    {
        if ($this->GetTypeReferentiel()->getId()==1)
        {
            return(null);
        }
        elseif ($this->GetTypeReferentiel()->getId()==2)
        {
            return($this);
        }
        else
        {
            return($this->getParent()->getDimension());
        }
    }
    
    public function getCritere()
    {
        if ($this->GetTypeReferentiel()->getId()<3)
        {
            return(null);
        }
        elseif ($this->GetTypeReferentiel()->getId()==3)
        {
            return($this);
        }
        else
        {
            return($this->getParent()->getCritere());
        }
    }
    
    
    public function getElement()
    {
        if ($this->GetTypeReferentiel()->getId()<4)
        {
            return(null);
        }
        elseif ($this->GetTypeReferentiel()->getId()==4)
        {
            return($this);
        }
        else
        {
            return($this->getParent()->getElement());
        }
    }
    
    
    
    

    /**
     * Set rBPP
     *
     * @param \Pericles3Bundle\Entity\BibliothequeAncreai $rBPP
     *
     * @return Referentiel
     */
    public function setRBPP(\Pericles3Bundle\Entity\BibliothequeAncreai $rBPP = null)
    {
        $this->RBPP = $rBPP;

        return $this;
    }

    /**
     * Get rBPP
     *
     * @return \Pericles3Bundle\Entity\BibliothequeAncreai
     */
    public function getRBPP()
    {
        return $this->RBPP;
    }

    public function getNbRBPP()
    {
        if ($this->RBPP) return (1);
    }
    
    
    
   

   


    /**
     * Set rbpppComment
     *
     * @param string $rbpppComment
     *
     * @return Referentiel
     */
    public function setRbpppComment($rbpppComment)
    {
        $this->rbppp_comment = $rbpppComment;

        return $this;
    }

    /**
     * Get rbpppComment
     *
     * @return string
     */
    public function getRbpppComment()
    {
        return $this->rbppp_comment;
    }

    /**
     * Set verifie
     *
     * @param boolean $verifie
     *
     * @return Referentiel
     */
    public function setVerifie($verifie)
    {
        $this->verifie = $verifie;

        return $this;
    }

    /**
     * Get verifie
     *
     * @return boolean
     */
    public function getVerifie()
    {
        return $this->verifie;
    }
    
    function getAllChildVerif()
    {
        if ($this->GetNbChildren())
        {
            $retour=true;
            foreach ($this->getChildren() as $child)
            {
                $retour=($retour && $child->getAllChildVerif());
            }
            return($retour);
        }
        else
        {
            return($this->getVerifie());
        }
    }
    
    /*
    function getAllChildNotVerif()
    {
        if ($this->GetNbChildren())
        {
            $retour=false;
            foreach ($this->getChildren() as $child)
            {
                $retour=($retour && $child->getAllChildVerif());
            }
            return($retour);
        }
        else
        {
            return($this->getVerifie());
        }
    }
    */
    
    public function getAVerifier()
    {
            if (! $this->getReferentielPublic()->getFini())
            {
            if ($this->getReferentielPublic()->getCopie())
            {
                if ($this->getVerifie())
                {
                    if ($this->getAllChildVerif()) 
                    {
                        return("done");
                    }
                    else
                    {
                        return("doing");
                    }
                }
                else
                {
                    return("todo");
                }
            }
            else
            {
                return("nothing");
            }
        }
        
/*            return($this->getReferentielPublic()->getCopie());
         */   
    } 
    

    /**
     * Add question
     *
     * @param \Pericles3Bundle\Entity\Question $question
     *
     * @return Referentiel
     */
    public function addQuestion(\Pericles3Bundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \Pericles3Bundle\Entity\Question $question
     */
    public function removeQuestion(\Pericles3Bundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set sourceParent
     *
     * @param \Pericles3Bundle\Entity\Referentiel $sourceParent
     *
     * @return Referentiel
     */
    public function setSourceParent(\Pericles3Bundle\Entity\Referentiel $sourceParent = null)
    {
        $this->sourceParent = $sourceParent;

        return $this;
    }

    /**
     * Get sourceParent
     *
     * @return \Pericles3Bundle\Entity\Referentiel
     */
    public function getSourceParent()
    {
        return $this->sourceParent;
    }

    /**
     * Add sourceChild
     *
     * @param \Pericles3Bundle\Entity\Referentiel $sourceChild
     *
     * @return Referentiel
     */
    public function addSourceChild(\Pericles3Bundle\Entity\Referentiel $sourceChild)
    {
        $this->sourceChildren[] = $sourceChild;

        return $this;
    }

    /**
     * Remove sourceChild
     *
     * @param \Pericles3Bundle\Entity\Referentiel $sourceChild
     */
    public function removeSourceChild(\Pericles3Bundle\Entity\Referentiel $sourceChild)
    {
        $this->sourceChildren->removeElement($sourceChild);
    }

    /**
     * Get sourceChildren
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSourceChildren()
    {
        return $this->sourceChildren;
    }
    
    
    
    public function getSourceChildrenByPublic(ReferentielPublic $ReferentielPublic )
    {
        foreach ($this->sourceChildren as $child)
        {
             if ($child->GetReferentielPublic()==$ReferentielPublic ) return($child);
        }
    }


    public function getAieul(ReferentielPublic $ReferentielPublic)
    {
        $tmp=$this;
        while ($tmp->getSourceParent())
        {
            if ($tmp->getSourceParent()->getReferentielPublic()==$ReferentielPublic) 
            {
                return($tmp->getSourceParent());
            }
            $tmp = $tmp->getSourceParent();
        }
    }
        

    
    public function getElementsApreciationsNouveauxByPublic(ReferentielPublic $ReferentielPublic )
    {
        $RefEnfant=$this->getSourceChildrenByPublic($ReferentielPublic);
        $nioux=new \Doctrine\Common\Collections\ArrayCollection();
        if ($this->TypeReferentiel()->getId()==3)
        {
            if ($RefEnfant)
            {
                foreach ($RefEnfant->GetChildren() as $quest)
                {
                    if (! $quest->GetSourceParent()==$this) $nioux[]=$quest;
                    //
                }
                return($nioux);
            }
        }
        else
        {
            return("Doit etre apellé sur des criteres uniquement");
        }
    }

    public function getElementsApreciationsCommunsByPublic(ReferentielPublic $ReferentielPublic )
    {
        $RefEnfant=$this->getSourceChildrenByPublic($ReferentielPublic);
        $nioux=new \Doctrine\Common\Collections\ArrayCollection();
        if ($this->TypeReferentiel()->getId()==3)
        {
            if ($RefEnfant)
            {
                foreach ($RefEnfant->GetChildren() as $quest)
                {
                    if ($quest->GetSourceParent())
                    {
                        if ($quest->GetParent()->GetSourceParent()==$this && $quest->GetSourceParent()->GetParent()==$this ) $nioux[]=$quest;
                    }
                }
                return($nioux);
            }
        }
        else
        {
            return("Doit etre apellé sur des criteres uniquement");
        }
    }
    
    public function getElementsApreciationsEnlevesByPublic(ReferentielPublic $ReferentielPublic )
    {
        
        if ($this->getSourceChildrenByPublic($ReferentielPublic))
        {
            
        $RefEnfantQuestions=$this->getSourceChildrenByPublic($ReferentielPublic)->GetChildren();
        $enfant_questions_parents=new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($RefEnfantQuestions as $quest)
        {
            if ($quest->GetSourceParent()) $enfant_questions_parents[]=$quest->GetSourceParent();
        }
        
//        return($enfant_questions_parents);
//        if ($this->getEtablissements()->Contains($etablissement))

        $nioux=new \Doctrine\Common\Collections\ArrayCollection();
        if ($this->TypeReferentiel()->getId()==3)
        {
            foreach ($this->GetChildren() as $quest)
            {
                if (! $enfant_questions_parents->contains($quest)) $nioux[]=$quest;
            }
            return($nioux);
        }
        else
        {
            return("Doit etre apellé sur des criteres uniquement");
        }
        }

    }
    
    public function getNbElementsApreciationsNouveauxByPublic(ReferentielPublic $ReferentielPublic )
    {
        return(count($this->getElementsApreciationsNouveauxByPublic($ReferentielPublic )));
    }    
    
    
    public function getNbElementsApreciationsCommunsByPublic(ReferentielPublic $ReferentielPublic )
    {
        return(count($this->getElementsApreciationsCommunsByPublic($ReferentielPublic )));
    }    
    
    public function getNbElementsApreciationsEnlevesByPublic(ReferentielPublic $ReferentielPublic )
    {
        return(count($this->getElementsApreciationsEnlevesByPublic($ReferentielPublic )));
    }    
    
    
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\QualiEvalReferentiel", mappedBy="referentielAdulte")
     */
    private $QEReferentielAdulte;
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\QualiEvalReferentiel", mappedBy="referentielEnfant")
     */
    private $QEReferentielEnfant;
    
    
    
    
    
    

    /**
     * Add patchSource
     *
     * @param \Pericles3Bundle\Entity\PatchReferentiel $patchSource
     *
     * @return Referentiel
     */
    public function addPatchSource(\Pericles3Bundle\Entity\PatchReferentiel $patchSource)
    {
        $this->patchSources[] = $patchSource;

        return $this;
    }

    /**
     * Remove patchSource
     *
     * @param \Pericles3Bundle\Entity\PatchReferentiel $patchSource
     */
    public function removePatchSource(\Pericles3Bundle\Entity\PatchReferentiel $patchSource)
    {
        $this->patchSources->removeElement($patchSource);
    }

    /**
     * Get patchSources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatchSources()
    {
        return $this->patchSources;
    }

    /**
     * Add patchCible
     *
     * @param \Pericles3Bundle\Entity\PatchReferentiel $patchCible
     *
     * @return Referentiel
     */
    public function addPatchCible(\Pericles3Bundle\Entity\PatchReferentiel $patchCible)
    {
        $this->patchCibles[] = $patchCible;

        return $this;
    }

    /**
     * Remove patchCible
     *
     * @param \Pericles3Bundle\Entity\PatchReferentiel $patchCible
     */
    public function removePatchCible(\Pericles3Bundle\Entity\PatchReferentiel $patchCible)
    {
        $this->patchCibles->removeElement($patchCible);
    }

    /**
     * Get patchCibles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatchCibles()
    {
        return $this->patchCibles;
    }

    /**
     * Add patchAieul
     *
     * @param \Pericles3Bundle\Entity\PatchReferentiel $patchAieul
     *
     * @return Referentiel
     */
    public function addPatchAieul(\Pericles3Bundle\Entity\PatchReferentiel $patchAieul)
    {
        $this->patchAieuls[] = $patchAieul;

        return $this;
    }

    /**
     * Remove patchAieul
     *
     * @param \Pericles3Bundle\Entity\PatchReferentiel $patchAieul
     */
    public function removePatchAieul(\Pericles3Bundle\Entity\PatchReferentiel $patchAieul)
    {
        $this->patchAieuls->removeElement($patchAieul);
    }

    /**
     * Get patchAieuls
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatchAieuls()
    {
        return $this->patchAieuls;
    }

    /**
     * Add qEReferentielAdulte
     *
     * @param \Pericles3Bundle\Entity\QualiEvalReferentiel $qEReferentielAdulte
     *
     * @return Referentiel
     */
    public function addQEReferentielAdulte(\Pericles3Bundle\Entity\QualiEvalReferentiel $qEReferentielAdulte)
    {
        $this->QEReferentielAdulte[] = $qEReferentielAdulte;

        return $this;
    }

    /**
     * Remove qEReferentielAdulte
     *
     * @param \Pericles3Bundle\Entity\QualiEvalReferentiel $qEReferentielAdulte
     */
    public function removeQEReferentielAdulte(\Pericles3Bundle\Entity\QualiEvalReferentiel $qEReferentielAdulte)
    {
        $this->QEReferentielAdulte->removeElement($qEReferentielAdulte);
    }

    /**
     * Get qEReferentielAdulte
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQEReferentielAdulte()
    {
        return $this->QEReferentielAdulte;
    }

    /**
     * Add qEReferentielEnfant
     *
     * @param \Pericles3Bundle\Entity\QualiEvalReferentiel $qEReferentielEnfant
     *
     * @return Referentiel
     */
    public function addQEReferentielEnfant(\Pericles3Bundle\Entity\QualiEvalReferentiel $qEReferentielEnfant)
    {
        $this->QEReferentielEnfant[] = $qEReferentielEnfant;

        return $this;
    }

    /**
     * Remove qEReferentielEnfant
     *
     * @param \Pericles3Bundle\Entity\QualiEvalReferentiel $qEReferentielEnfant
     */
    public function removeQEReferentielEnfant(\Pericles3Bundle\Entity\QualiEvalReferentiel $qEReferentielEnfant)
    {
        $this->QEReferentielEnfant->removeElement($qEReferentielEnfant);
    }

    /**
     * Get qEReferentielEnfant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQEReferentielEnfant()
    {
        return $this->QEReferentielEnfant;
    }
    
    
    /**
     * Get qEReferentielEnfant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQEReferentiels()
    {
        if ($this->getReferentielPublic()->getId()==35)return $this->QEReferentielAdulte;
        elseif ($this->getReferentielPublic()->getId()==34) return $this->QEReferentielEnfant;
    }
    
    

    /**
     * Add sauvegardeDomaine
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDomaine $sauvegardeDomaine
     *
     * @return Referentiel
     */
    public function addSauvegardeDomaine(\Pericles3Bundle\Entity\SauvegardeDomaine $sauvegardeDomaine)
    {
        $this->sauvegardeDomaine[] = $sauvegardeDomaine;

        return $this;
    }

    /**
     * Remove sauvegardeDomaine
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDomaine $sauvegardeDomaine
     */
    public function removeSauvegardeDomaine(\Pericles3Bundle\Entity\SauvegardeDomaine $sauvegardeDomaine)
    {
        $this->sauvegardeDomaine->removeElement($sauvegardeDomaine);
    }

    /**
     * Get sauvegardeDomaine
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSauvegardeDomaine()
    {
        return $this->sauvegardeDomaine;
    }
    
        
    
    
    
    
    

    /**
     * Add sauvegardeDimension
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDimension $sauvegardeDimension
     *
     * @return Referentiel
     */
    public function addSauvegardeDimension(\Pericles3Bundle\Entity\SauvegardeDimension $sauvegardeDimension)
    {
        $this->sauvegardeDimension[] = $sauvegardeDimension;

        return $this;
    }

    /**
     * Remove sauvegardeDimension
     *
     * @param \Pericles3Bundle\Entity\SauvegardeDimension $sauvegardeDimension
     */
    public function removeSauvegardeDimension(\Pericles3Bundle\Entity\SauvegardeDimension $sauvegardeDimension)
    {
        $this->sauvegardeDimension->removeElement($sauvegardeDimension);
    }

    /**
     * Get sauvegardeDimension
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSauvegardeDimension()
    {
        return $this->sauvegardeDimension;
    }

    /**
     * Add sauvegardeCritere
     *
     * @param \Pericles3Bundle\Entity\SauvegardeCritere $sauvegardeCritere
     *
     * @return Referentiel
     */
    public function addSauvegardeCritere(\Pericles3Bundle\Entity\SauvegardeCritere $sauvegardeCritere)
    {
        $this->sauvegardeCritere[] = $sauvegardeCritere;

        return $this;
    }

    /**
     * Remove sauvegardeCritere
     *
     * @param \Pericles3Bundle\Entity\SauvegardeCritere $sauvegardeCritere
     */
    public function removeSauvegardeCritere(\Pericles3Bundle\Entity\SauvegardeCritere $sauvegardeCritere)
    {
        $this->sauvegardeCritere->removeElement($sauvegardeCritere);
    }

    /**
     * Get sauvegardeCritere
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSauvegardeCritere()
    {
        return $this->sauvegardeCritere;
    }

    /**
     * Add sauvegardeQuestion
     *
     * @param \Pericles3Bundle\Entity\SauvegardeQuestion $sauvegardeQuestion
     *
     * @return Referentiel
     */
    public function addSauvegardeQuestion(\Pericles3Bundle\Entity\SauvegardeQuestion $sauvegardeQuestion)
    {
        $this->sauvegardeQuestion[] = $sauvegardeQuestion;

        return $this;
    }

    /**
     * Remove sauvegardeQuestion
     *
     * @param \Pericles3Bundle\Entity\SauvegardeQuestion $sauvegardeQuestion
     */
    public function removeSauvegardeQuestion(\Pericles3Bundle\Entity\SauvegardeQuestion $sauvegardeQuestion)
    {
        $this->sauvegardeQuestion->removeElement($sauvegardeQuestion);
    }

    /**
     * Get sauvegardeQuestion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSauvegardeQuestion()
    {
        return $this->sauvegardeQuestion;
    }
    

    public function getSauvegardeDomaineBySauvegarde(Sauvegarde $sauvegarde)
    {
        foreach ($this->sauvegardeDomaine as $sauvegardeDomaine)
        {
            if ($sauvegardeDomaine->getSauvegarde()->getId()==$sauvegarde->getId()) return($sauvegardeDomaine);
        }
    }
    
    public function getSauvegardeDimensionBySauvegarde(Sauvegarde $sauvegarde)
    {
        foreach ($this->sauvegardeDimension as $sauvegardeDimension)
        {
            if ($sauvegardeDimension->getSauvegarde()->getId()==$sauvegarde->getId()) return($sauvegardeDimension);
        }
    }
    
    public function getSauvegardeCritereBySauvegarde(Sauvegarde $sauvegarde)
    {
        foreach ($this->sauvegardeCritere as $sauvegardeCritere)
        {
            if ($sauvegardeCritere->getSauvegarde()->getId()==$sauvegarde->getId()) return($sauvegardeCritere);
        }
    }
    
    public function getSauvegardeQuestionBySauvegarde(Sauvegarde $sauvegarde)
    {
        foreach ($this->sauvegardeQuestion as $sauvegardeQuestion)
        {
            if ($sauvegardeQuestion->getSauvegarde()->getId()==$sauvegarde->getId()) return($sauvegardeQuestion);
        }
    }
    
    
    
    
}
