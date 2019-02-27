<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * DomaineObjectifStrategique
 *
 * @ORM\Table(name="domaine_objectifs_strategique")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DomaineObjectifStrategiqueRepository")
 */
class DomaineObjectifStrategique
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
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;

    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Domaine",inversedBy="objectifs_srategique")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domaine;
    
    

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User",inversedBy="objectifs_srategiques")
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
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", nullable=true)
     */
    private $statut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEcheance", type="datetime", nullable=true)
     */
    private $dateEcheance;

 
  
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement",inversedBy="objectifsStrategique")
     */
    private $etablissement;
    
    
 
    
    

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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return DomaineObjectifStrategique
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
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return DomaineObjectifStrategique
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
     * Set statut
     *
     * @param integer $statut
     *
     * @return DomaineObjectifStrategique
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set dateEcheance
     *
     * @param \DateTime $dateEcheance
     *
     * @return DomaineObjectifStrategique
     */
    public function setDateEcheance($dateEcheance)
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    /**
     * Get dateEcheance
     *
     * @return \DateTime
     */
    public function getDateEcheance()
    {
        return $this->dateEcheance;
    }

    /**
     * Set domaine
     *
     * @param \Pericles3Bundle\Entity\Domaine $domaine
     *
     * @return DomaineObjectifStrategique
     */
    public function setDomaine(\Pericles3Bundle\Entity\Domaine $domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * Get domaine
     *
     * @return \Pericles3Bundle\Entity\Domaine
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * Set user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return DomaineObjectifStrategique
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
    
    
    
    public function getCSSClasse()
    {
        if ($this->statut==3) {return("done");}
        elseif  ($this->statut==2) { return("toedit");}
        else {return("doing");}
    }
    
   
    public function getStatutLib()
    {
        if ($this->statut==1) {return("En cours");}
        elseif  ($this->statut==2) { return("Important");}
        else {return("Terminé");}
    }
    

           
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        return $this->getCommentaire();
    }
    
    

    /**
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return DomaineObjectifStrategique
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
}
