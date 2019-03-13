<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use DateTime;

/**
 * ObjectifOperationnel
 *
 * @ORM\Table(name="objectif_perationnel")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\ObjectifOperationnelRepository")
 */
class ObjectifOperationnel
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime", nullable=true)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text",  nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="moyen", type="text", nullable=true)
     */
    private $moyen;

    /**
     * @var string
     *
     * @ORM\Column(name="indicateurs", type="text", nullable=true)
     */
    private $indicateurs;

    /**
     * @var int
     *
     * @ORM\Column(name="complete", type="integer", nullable=true)
     */
    private $complete;
    
    


    /**
    * @ORM\ManyToMany(targetEntity="Pericles3Bundle\Entity\Critere", mappedBy="objectifs")
    */
    private $criteres;

    
    

    /**
    * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Preuve", mappedBy="objectifOperationnel")
    */
    private $preuves;

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement",inversedBy="objectifsOperationnel")
     */
    private $etablissement;
    
    
    
    
 

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User",inversedBy="objectifsOperationnel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;

 
    
    /**
     * @var string
     *
     * @ORM\Column(name="pilote_par", type="string", length=255,  nullable=true)
     */
    private $pilotéPar;

    
    /**
     * @var int
     *
     * @ORM\Column(name="priorite", type="integer", nullable=true)
     */
    private $priorite;
    
    
    
    
      
    public function getCSSClasse()
    {
        if ($this->complete==100) {return("done");}
        elseif  ($this->complete>0) { return("doing");}
        else return("todo");
    }
    
    public function getCompleteLib()
    {
        if ($this->complete==100) {return("terminé");}
        elseif  ($this->complete>0) { return("en cours");}
        else return("non commencé");
    }
    
    
    
    public function getCSSGantClasse()
    {
        
        
        if ($this->complete==100) {$class="ganttGreen";}
        elseif  ($this->complete>0) {$class="ganttOrange";} 
        else  {$class="ganttRed";}  
        if ($this->getEnRetard()) $class.=" enRetard";
        return($class);
    }
    
    public function getEnRetard()
    {
        if ($this->getDateFin())
        {
            return($this->complete<100 && $this->getDateFin()<=new DateTime('now'));
        }
    }
    
    
    
    
    
    
        /**
     * Récupere le nombre de preuves associées à la fiche action
     *
     * @return int
     */
    public function getNbPreuves()
    {
        return (count($this->preuves));
    }
    
            

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->criteres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preuves = new \Doctrine\Common\Collections\ArrayCollection();
        $this->priorite=2;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return ObjectifOperationnel
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return ObjectifOperationnel
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return ObjectifOperationnel
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ObjectifOperationnel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set moyen
     *
     * @param string $moyen
     *
     * @return ObjectifOperationnel
     */
    public function setMoyen($moyen)
    {
        $this->moyen = $moyen;

        return $this;
    }

    /**
     * Get moyen
     *
     * @return string
     */
    public function getMoyen()
    {
        return $this->moyen;
    }

    /**
     * Set indicateurs
     *
     * @param string $indicateurs
     *
     * @return ObjectifOperationnel
     */
    public function setIndicateurs($indicateurs)
    {
        $this->indicateurs = $indicateurs;

        return $this;
    }

    /**
     * Get indicateurs
     *
     * @return string
     */
    public function getIndicateurs()
    {
        return $this->indicateurs;
    }

    /**
     * Set complete
     *
     * @param integer $complete
     *
     * @return ObjectifOperationnel
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;

        return $this;
    }

    /**
     * Get complete
     *
     * @return integer
     */
    public function getComplete()
    {
        return $this->complete;
    }
    
    
    public function getUnComplete()
    {
        return (100-$this->complete);
    }
    
    public function getCompleteTrue()
    {
        return ($this->complete==100);
    }
    
    public function getNonCommence()
    {
        return ($this->complete==0);
    }
    

    

    /**
     * Add critere
     *
     * @param \Pericles3Bundle\Entity\Critere $critere
     *
     * @return ObjectifOperationnel
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
    
    /**
     * Get nb criteres
     *
     * @return int
     */
    public function getNbCriteres()
    {
        return count($this->criteres);
    }
    
    public function getLonelyCritere()
    {
        if ($this->getNbCriteres())
        {
            return($this->criteres[0]);
        }
    }
    
    
    
    
    
    
    
    

    /**
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return ObjectifOperationnel
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
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getTitre();
    }
    

    /**
     * Add preuve
     *
     * @param \Pericles3Bundle\Entity\Preuve $preuve
     *
     * @return ObjectifOperationnel
     */
    public function addPreuve(\Pericles3Bundle\Entity\Preuve $preuve)
    {
        $this->preuves[] = $preuve;
        $preuve->setObjectifOperationnel($this);
        return $this;
    }

    /**
     * Remove preuve
     *
     * @param \Pericles3Bundle\Entity\Preuve $preuve
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

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return ObjectifOperationnel
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Add preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     *
     * @return ObjectifOperationnel
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
     * Set user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return ObjectifOperationnel
     */
    public function setUser(\Pericles3Bundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Pericles3Bundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set pilotéPar
     *
     * @param string $pilotéPar
     *
     * @return ObjectifOperationnel
     */
    public function setPilotéPar($pilotéPar)
    {
        $this->pilotéPar = $pilotéPar;

        return $this;
    }

    /**
     * Get pilotéPar
     *
     * @return string
     */
    public function getPilotéPar()
    {
        return $this->pilotéPar;
    }

    /**
     * Set priorité
     *
     * @param integer $priorité
     *
     * @return ObjectifOperationnel
     */
    public function setPriorité($priorite)
    {
        $this->priorite = $priorite;

        return $this;
    }

    /**
     * Get priorité
     *
     * @return integer
     */
    public function getPriorité()
    {
        return $this->priorite;
    }
}