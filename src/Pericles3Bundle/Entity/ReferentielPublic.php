<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



/**
 * ReferentielPublic
 *
 * @ORM\Table(name="referentiel_public")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\ReferentielPublicRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ReferentielPublic
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    use TimestampableEntity;
    use SoftDeleteableEntity;
      
      
    

    
    /**
     * @var string
     *
     * @ORM\Column(name="public", type="string", length=510)
     */
    private $public;

     

    /**
     * @var string
     *
     * @ORM\Column(name="short", type="string", length=100)
     */
    private $short;

    
     
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Etablissement", mappedBy="referentielPublic")
     */
    private $etablissements;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Referentiel", mappedBy="ReferentielPublic")
     */
    private $referentiels;


    
    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\BibliothequeAncreai", mappedBy="referentielPublics")
     * @ORM\JoinTable(name="bibliotheque_ancreai_referentiel_public")
     */
    private $bibliothequesAncreai;
    
  
         
 
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielExterne",inversedBy="referentielPublic")
     */
    private $referentielExterne;

    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="sourceChildren")
     */
    private $sourceParent;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", mappedBy="sourceParent")
     */
    private $sourceChildren;
    
    
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="versionningChildren")
     * @ORM\JoinColumn(nullable=true)     
     */
    private $versionningParent;

    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", mappedBy="versionningParent")
     */
    private $versionningChildren;
    
    
    
    
    
    /**
     * @var fini
     * @ORM\Column(type="integer")
     */
    protected $fini;


    
    /**
     * @var copie
     * @ORM\Column(type="boolean")
     */
    protected $copie;
    


    
    /**
     * @var copie
     * @ORM\Column(type="integer")
     */
    protected $versionning;
    
    
     
    


    
    
        
    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\User", mappedBy="ReferentielsPublic")
     */
    private $users;
    
    

    
    
    
     
    /**
     * @ORM\OnetoMany(targetEntity="Pericles3Bundle\Entity\DemandeEtablissement", mappedBy="referentielPublic")
     */
    private $demandesEtablissement;

    

  
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeInfos", mappedBy="public")
     */
    private $demandesinfos;

    
    /**
     * @var int
     *
     * @ORM\Column(name="nb_criteres_cache", type="integer")
     */
    private $nbCriteresCache;
     
    /**
     * @var int
     *
     * @ORM\Column(name="nb_questions_cache", type="integer")
     */
    private $nbQuestionsCache;
     

     
    
    /**
     * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\Editorial",mappedBy="referentielPublics")
     */
    private $news;
 
    
    
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Patch", mappedBy="patcheRefPublicSource")
     */
    private $patchSources;
    
     /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Patch", mappedBy="patcheRefPublicCible")
     */
    private $patchCibles;
    


    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\FinessCategorie", mappedBy="referentielPublicDefault")
     */
    private $finessCategories;
    
    
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Creai", inversedBy="publicsFacturation")
     */
    private $creai;
    
 
    

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
     * Set public
     *
     * @param string $public
     *
     * @return ReferentielPublic
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etablissements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->referentiels = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nbCriteresCache=0;
        $this->nbQuestionsCache=0;
    }
    
        

    /**
     * Add etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return ReferentielPublic
     */
    public function addEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->etablissements[] = $etablissement;

        return $this;
    }

    /**
     * Remove etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     */
    public function removeEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
    {
        $this->etablissements->removeElement($etablissement);
    }

    /**
     * Get etablissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtablissements()
    {
        return $this->etablissements;
    }

    /**
     * Add referentiel
     *
     * @param \Pericles3Bundle\Entity\Referentiel $referentiel
     *
     * @return ReferentielPublic
     */
    public function addReferentiel(\Pericles3Bundle\Entity\Referentiel $referentiel)
    {
        $this->referentiels[] = $referentiel;

        return $this;
    }

    /**
     * Remove referentiel
     *
     * @param \Pericles3Bundle\Entity\Referentiel $referentiel
     */
    public function removeReferentiel(\Pericles3Bundle\Entity\Referentiel $referentiel)
    {
        $this->referentiels->removeElement($referentiel);
    }

    /**
     * Get referentiels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentiels()
    {
        return $this->referentiels;
    }
    
    public function getNbReferentiels()
    {
        return count($this->referentiels);
    }
    

    private function getReferentielNiveau($niveau)
    {
        $referencielNiveau = new ArrayCollection();
        foreach ($this->getReferentiels() as $Referenciel) 
        {
            if ($Referenciel->getTypeReferentiel()->GetId()==$niveau)
            {
                $referencielNiveau[]=$Referenciel;
            }
        }
        return($referencielNiveau);
    }

    
    public function getReferentielDomaines()
    {
        return (self::getReferentielNiveau(1));
    }

    
    public function getReferentielDimensions()
    {
        return (self::getReferentielNiveau(2));
    }
    
    public function getReferentielCriteres()
    {
        return (self::getReferentielNiveau(3));
    }
    public function getReferentielQuestions()
    {
        return (self::getReferentielNiveau(4));
    }
    
    
    public function getNbDomaines()
    {
        return (count(self::getReferentielDomaines()));
    }
    public function getNbDimensions()
    {
        return (count(self::getReferentielDimensions()));
    }
    public function getNbCriteres()
    {
        return (count(self::getReferentielCriteres()));
    }
    public function getNbQuestions()
    {
        return (count(self::getReferentielQuestions()));
    }

    public function GetNbEtablissements()
    {
        return (count(self::getEtablissements()));
    }

    
    
    
    
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getPublic()." (".$this->GetCreatedAt()->format('Y').")";
    }
    
    public function getYear()
    {
        return($this->GetCreatedAt()->format('Y'));
    }
    
    

 
    

    /**
     * Add bibliothequesAncreai
     *
     * @param \Pericles3Bundle\Entity\BibliothequeAncreai $bibliothequesAncreai
     *
     * @return ReferentielPublic
     */
    public function addBibliothequesAncreai(\Pericles3Bundle\Entity\BibliothequeAncreai $bibliothequesAncreai)
    {
        $this->bibliothequesAncreai[] = $bibliothequesAncreai;

        return $this;
    }

    /**
     * Remove bibliothequesAncreai
     *
     * @param \Pericles3Bundle\Entity\BibliothequeAncreai $bibliothequesAncreai
     */
    public function removeBibliothequesAncreai(\Pericles3Bundle\Entity\BibliothequeAncreai $bibliothequesAncreai)
    {
        $this->bibliothequesAncreai->removeElement($bibliothequesAncreai);
    }

    /**
     * Get bibliothequesAncreai
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBibliothequesAncreai()
    {
        return $this->bibliothequesAncreai;
    }
    
    

    public function getNbBibliothequesAncreai()
    {
        return count($this->bibliothequesAncreai);
    }

    public function getBibliothequesAncreaiLinked()
    {
        $biblios = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->bibliothequesAncreai as $biblioAncreai)
        {
            if ($biblioAncreai->NbCriteresByRefPublic($this))
            {
                $biblios->Add($biblioAncreai);
            }
        }
        return($biblios);
    }
    
    
    
    
    
    /**
     * Set referentielExterne
     *
     * @param \Pericles3Bundle\Entity\ReferentielExterne $referentielExterne
     *
     * @return ReferentielPublic
     */
    public function setReferentielExterne(\Pericles3Bundle\Entity\ReferentielExterne $referentielExterne = null)
    {
        $this->referentielExterne = $referentielExterne;

        return $this;
    }

    /**
     * Get referentielExterne
     *
     * @return \Pericles3Bundle\Entity\ReferentielExterne
     */
    public function getReferentielExterne()
    {
        return $this->referentielExterne;
    }
    
    
    public function getNbCritereRefExterneByPublic()
    {
        if ($this->referentielExterne)
        {
            return $this->referentielExterne->getNbCritereRefExterneByPublic($this);
        }
    }
    
     
    public function getNbN1ExterneByPublic()
    {
        if ($this->referentielExterne)
        {
            return ($this->referentielExterne->getNbN1ExterneByPublic($this));
        }
    }
  

    /**
     * Set fini
     *
     * @param boolean $fini
     *
     * @return ReferentielPublic
     */
    public function setFini($fini)
    {
        $this->fini = $fini;

        return $this;
    }

    /**
     * Get fini
     *
     * @return integer
     */
    public function getFini()
    {
        return $this->fini;
    }
    
    public function getObsolete()
    {
        return ($this->fini==-2);
    }
    public function getFiniAndLast()
    {
        return ($this->getFini()==1);
    }
    
    public function getTheLastGood()
    {
        
        if ($this->getFiniAndLast())
        {
            return($this);
        }
        elseif (! $this->IsVersionningFeuille ())
        {
            return($this->getVersionningChildrenFirst()->getTheLastGood());
        }
    }
    
    
    
    
    
    
    public function IsObsolete()
    {
        return ($this->getObsolete());
    }

    
    public function getEnDev()
    {
        return ($this->fini==0 or $this->fini==-1);
    }
         
    
        
    public function getDeadEnd()
    {
        return ($this->fini==-2 && ! $this->getNbSourceChildren());
    }
    
    
    
    
    


    /**
     * Add user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return ReferentielPublic
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
     * Add demandesEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement
     *
     * @return ReferentielPublic
     */
    public function addDemandesEtablissement(\Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement)
    {
        $this->demandesEtablissement[] = $demandesEtablissement;

        return $this;
    }

    /**
     * Remove demandesEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement
     */
    public function removeDemandesEtablissement(\Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement)
    {
        $this->demandesEtablissement->removeElement($demandesEtablissement);
    }

    /**
     * Get demandesEtablissement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandesEtablissement()
    {
        return $this->demandesEtablissement;
    }
    
    public function getNbDemandesEtablissement()
    {
        return count($this->demandesEtablissement);
    }
    
    
    

    /**
     * Add demandesinfo
     *
     * @param \Pericles3Bundle\Entity\DemandeInfos $demandesinfo
     *
     * @return ReferentielPublic
     */
    public function addDemandesinfo(\Pericles3Bundle\Entity\DemandeInfos $demandesinfo)
    {
        $this->demandesinfos[] = $demandesinfo;

        return $this;
    }

    /**
     * Remove demandesinfo
     *
     * @param \Pericles3Bundle\Entity\DemandeInfos $demandesinfo
     */
    public function removeDemandesinfo(\Pericles3Bundle\Entity\DemandeInfos $demandesinfo)
    {
        $this->demandesinfos->removeElement($demandesinfo);
    }

    /**
     * Get demandesinfos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandesinfos()
    {
        return $this->demandesinfos;
    }

    /**
     * Set short
     *
     * @param string $short
     *
     * @return ReferentielPublic
     */
    public function setShort($short)
    {
        $this->short = $short;

        return $this;
    }

    /**
     * Get short
     *
     * @return string
     */
    public function getShort()
    {
        if ($this->short) return($this->short);
        else return (substr($this->getPublic(),0,10)."...");
    }
    
 

    /**
     * Set copie
     *
     * @param boolean $copie
     *
     * @return ReferentielPublic
     */
    public function setCopie($copie)
    {
        $this->copie = $copie;

        return $this;
    }

    /**
     * Get copie
     *
     * @return boolean
     */
    public function getCopie()
    {
        return ($this->copie>0);
    }
    
     
        
        
    
    

    /**
     * Set nbCriteresCache
     *
     * @param integer $nbCriteresCache
     *
     * @return ReferentielPublic
     */
    public function setNbCriteresCache($nbCriteresCache)
    {
        $this->nbCriteresCache = $nbCriteresCache;

        return $this;
    }

    /**
     * Get nbCriteresCache
     *
     * @return integer
     */
    public function getNbCriteresCache()
    {
        return $this->nbCriteresCache;
    }

    
    public function GenereCache()
    {
        $this->setNbCriteresCache($this->getNbCriteres());
        $this->setNbQuestionsCache($this->getNbQuestions());
    }
    
    
    
    
    /**
     * Set nbQuestionsCache
     *
     * @param integer $nbQuestionsCache
     *
     * @return ReferentielPublic
     */
    public function setNbQuestionsCache($nbQuestionsCache)
    {
        $this->nbQuestionsCache = $nbQuestionsCache;

        return $this;
    }

    /**
     * Get nbQuestionsCache
     *
     * @return integer
     */
    public function getNbQuestionsCache()
    {
        return $this->nbQuestionsCache;
    }
    
    
    public function getNbRBPP()
    {
        $nb = 0;
        $criteres = $this->getReferentielCriteres();
        foreach ($criteres  as $critere)
        {
            $nb+=$critere->getNbRBPP();
        }
        return ($nb);
    }

       
    public function getNbCritereSansRefAnnexe()
    {
        $nb = 0;
        $criteres = $this->getReferentielCriteres();
        foreach ($criteres  as $critere)
        {
            if (! $critere->getReferentielExterneNiv1()) $nb+=1;
        }
        return ($nb);
    }
    
    
    public function getNbRBPPSansCritere()
    {
        $nb = 0;
        $criteres = $this->getReferentielCriteres();
        foreach ($criteres  as $critere)
        {
            if (! $critere->GetRbpp()) $nb+=1;
        }
        return ($nb);
    }
    
    
      
    public function getNbReponsesOuiNon()
    {
        $nb = 0;
        $questions = $this->getReferentielQuestions();
        foreach ($questions   as $question )
        {
            if ( $question->getReponseOuiNonSaisi()) $nb+=1;
        }
        return ($nb);
    }
    
    
    
    
    

    /**
     * Set sourceParent
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $sourceParent
     *
     * @return ReferentielPublic
     */
    public function setSourceParent(\Pericles3Bundle\Entity\ReferentielPublic $sourceParent = null)
    {
        $this->sourceParent = $sourceParent;

        return $this;
    }

    
    
    
    /**
     * Get sourceParent
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getSourceParent()
    {
        return $this->sourceParent;
    }
    
    public function getAieuls()
    {
        $aieux = new \Doctrine\Common\Collections\ArrayCollection();
        $tmp=$this;
        while ($tmp->getSourceParent())
        {
            $aieux->Add($tmp->getSourceParent());
            $tmp = $tmp->getSourceParent();
        }
        return($aieux);
    }
    
    
    

    /**
     * Add sourceChild
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $sourceChild
     *
     * @return ReferentielPublic
     */
    public function addSourceChild(\Pericles3Bundle\Entity\ReferentielPublic $sourceChild)
    {
        $this->sourceChildren[] = $sourceChild;

        return $this;
    }

    /**
     * Remove sourceChild
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $sourceChild
     */
    public function removeSourceChild(\Pericles3Bundle\Entity\ReferentielPublic $sourceChild)
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

    public function getNbSourceChildren()
    {
        return count($this->sourceChildren);
    }

    /**
     * Add news
     *
     * @param \Pericles3Bundle\Entity\Editorial $news
     *
     * @return ReferentielPublic
     */
    public function addNews(\Pericles3Bundle\Entity\Editorial $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news
     *
     * @param \Pericles3Bundle\Entity\Editorial $news
     */
    public function removeNews(\Pericles3Bundle\Entity\Editorial $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }
    
    

    /**
     * Set versionning
     *
     * @param integer $versionning
     *
     * @return ReferentielPublic
     */
    public function setVersionning($versionning)
    {
        $this->versionning = $versionning;

        return $this;
    }

    /**
     * Get versionning
     *
     * @return integer
     */
    public function getVersionning()
    {
        return $this->versionning;
    }
    
    
    public function getEtablissementsTests()
    {
        $etablissements =  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->etablissements as $etablissement ) 
        {
            if (! $etablissement->IsReel())
            {
                $etablissements->Add($etablissement);
            }
        }
        return $etablissements;
    }
    
    
    public function getEtablissementsReels()
    {
        $etablissements =  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->etablissements as $etablissement ) 
        {
            if ($etablissement->IsReel())
            {
                $etablissements->Add($etablissement);
            }
        }
        return $etablissements;
    }
    
    public function getNbEtablissementsReels()
    {
        return count($this->getEtablissementsReels());
    }
    
        
    public function getNbEtablissementsTest()
    {
        return count($this->getEtablissementsTests());
    }
    
    
    
    public function isVersionAlpha()
    {
//        return $this->versionningParent == null)$this->versionning==0;
        return ($this->getVersionningParent() == null);
    }
    
    
    
    public function getVersionAlpha()
    {
        
        if ($this->isVersionAlpha())
        {
            return($this);
        }
        else
        {
            return($this->getVersionningParent()->getVersionAlpha());
        }
    }
    
    
    public function getAllBranche()
    {
        $referentielsPublics = new \Doctrine\Common\Collections\ArrayCollection();
        $public_i=$this->getVersionAlpha();
        $referentielsPublics->add($public_i);
        while ($public_i->getVersionningChildrenFirst())
        {
            $public_i= $public_i->getVersionningChildrenFirst();
            $referentielsPublics->add($public_i);
        }
        return($referentielsPublics);
        
    }
    
     
    
    
    
    public function getNbEtablissementsReelsCascade()
    {
        
        
        
        if ($this->isVersionAlpha())
        {
            return $this->getNbEtablissementsReels();
        }
        else
        {
         
            if (! $this->getVersionningParent()) throw new NotFoundHttpException("Exeption par la : ".$this->__toString());
            return ($this->getNbEtablissementsReels()+$this->getVersionningParent()->getNbEtablissementsReelsCascade());
        }
    }
    
    

    /**
     * Set versionningParent
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $versionningParent
     *
     * @return ReferentielPublic
     */
    public function setVersionningParent(\Pericles3Bundle\Entity\ReferentielPublic $versionningParent = null)
    {
        $this->versionningParent = $versionningParent;

        return $this;
    }

    /**
     * Get versionningParent
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getVersionningParent()
    {
        return $this->versionningParent;
    }

    /**
     * Add versionningChild
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $versionningChild
     *
     * @return ReferentielPublic
     */
    public function addVersionningChild(\Pericles3Bundle\Entity\ReferentielPublic $versionningChild)
    {
        $this->versionningChildren[] = $versionningChild;

        return $this;
    }

    /**
     * Remove versionningChild
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $versionningChild
     */
    public function removeVersionningChild(\Pericles3Bundle\Entity\ReferentielPublic $versionningChild)
    {
        $this->versionningChildren->removeElement($versionningChild);
    }

    /**
     * Get versionningChildren
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVersionningChildren()
    {
        return $this->versionningChildren;
    }

    public function getVersionningChildrenFirst()
    {
        return $this->versionningChildren[0];
    }
    
    public function IsVersionningFeuille()
    {
        return (! count($this->versionningChildren));
    }
    
     
    
    
    
    
    
    
    public function getVersionNum()
    {
         if ($this->fini==0) {  $nb=0; }
        else { $nb=1;}
           
        if ($this->getVersionningParent())
        {
            return($nb+$this->getVersionningParent()->getVersionNum());
        }
        else
        {
             return($nb);
        }
    }
    
    
    public function getVersionNum2()
    {
        if ($this->fini==1 or $this->fini==-2) {  return(0); }
        else { return(5);}
        
    }
    
    
    public function getClass()
    {
        if ($this->getDeadEnd())
        {
            return("danger");
        }
        elseif ($this->getObsolete() or $this->getVersionNum2())
        {
            return("warning");
        }
        else
        {
            return("success");
            
        }
    }
    
    
    
    
    
    
    public function getVersion()
    {
        $reponse=$this->getVersionNum();
        $reponse.=".";
        
        /*
        $reponse.="->";
        $reponse.=$this->fini;
        $reponse.="<-";
         * 
         */
        $reponse.=$this->getVersionNum2();
        return($reponse);
    }

    
    
    

    /**
     * Add patchSource
     *
     * @param \Pericles3Bundle\Entity\Patch $patchSource
     *
     * @return ReferentielPublic
     */
    public function addPatchSource(\Pericles3Bundle\Entity\Patch $patchSource)
    {
        $this->patchSources[] = $patchSource;

        return $this;
    }

    /**
     * Remove patchSource
     *
     * @param \Pericles3Bundle\Entity\Patch $patchSource
     */
    public function removePatchSource(\Pericles3Bundle\Entity\Patch $patchSource)
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

    
    public function getPatchAll()
    {
        return new ArrayCollection((array)$this->patchSources->toArray() + $this->patchCibles->toArray());
    }

    
    
    /**
     * Add patchCible
     *
     * @param \Pericles3Bundle\Entity\Patch $patchCible
     *
     * @return ReferentielPublic
     */
    public function addPatchCible(\Pericles3Bundle\Entity\Patch $patchCible)
    {
        $this->patchCibles[] = $patchCible;

        return $this;
    }

    /**
     * Remove patchCible
     *
     * @param \Pericles3Bundle\Entity\Patch $patchCible
     */
    public function removePatchCible(\Pericles3Bundle\Entity\Patch $patchCible)
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
    
    public function getReferentielPublicCiblePatch()
    {
        $refCibles = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->patchSources as $patch)
        {
            $refCibles->add($patch->getCible());
        }
        return($refCibles);
    }

    
    public function getPatchCiblePatch(ReferentielPublic $publicCible)
    {
        $refCibles = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->patchSources as $patch)
        {
            if ($patch->getCible() ==$publicCible ) return $patch;
        }
    }

    
    
    public function hasPatch()
    {
        return (count($this->getPatchSources()) + count($this->getPatchCibles()));
    }
           
            
    
     
    
    
    

    /**
     * Add finessCategory
     *
     * @param \Pericles3Bundle\Entity\FinessCategorie $finessCategory
     *
     * @return ReferentielPublic
     */
    public function addFinessCategory(\Pericles3Bundle\Entity\FinessCategorie $finessCategory)
    {
        $this->finessCategories[] = $finessCategory;

        return $this;
    }

    /**
     * Remove finessCategory
     *
     * @param \Pericles3Bundle\Entity\FinessCategorie $finessCategory
     */
    public function removeFinessCategory(\Pericles3Bundle\Entity\FinessCategorie $finessCategory)
    {
        $this->finessCategories->removeElement($finessCategory);
    }

    /**
     * Get finessCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFinessCategories()
    {
        return $this->finessCategories;
    }

    /**
     * Set creai
     *
     * @param \Pericles3Bundle\Entity\Creai $creai
     *
     * @return ReferentielPublic
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
}
