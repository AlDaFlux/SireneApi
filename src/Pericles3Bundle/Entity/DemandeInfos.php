<?php

namespace Pericles3Bundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;


use Doctrine\ORM\Mapping as ORM;

/**
 * DemandeInfos
 *
 * @ORM\Table(name="demande_infos")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DemandeInfosRepository")
 */
class DemandeInfos
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
     * @ORM\Column(name="demandeur_nom_prenom", type="string", length=255)
     */
    private $demandeurNomPrenom;


    
    /**
     * @var string
     *
     * @ORM\Column(name="etablissement_service", type="string", length=255)
     */
    private $etablissementService;


           
     /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Creai", inversedBy="demandesinfos")
     */
    private $creai;


    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="demandesinfos")
     * @ORM\JoinColumn(nullable=true)
     */
    private $public;

    


    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=false, nullable=false)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;
   
    
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $tel;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDemande", type="datetime")
     */
    private $dateDemande;


     
    

     

        
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text",nullable=true)
     */
    private $commentaire;


    /**
     * @var string
     *
     * @ORM\Column(name="remarques", type="text",nullable=true)
     */
    private $remarques;


    
    
    
    
    
    
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\DemandeEtat", inversedBy="demandesInfos")
    */
    private $etat;

    

    
    
    function __toString() 
    {
        return($this->demandeurNomPrenom);
    }
    
    
     /**
     * Constructor
     */
    public function __construct()
    {
        $this->referentielPublic = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set demandeurNomPrenom
     *
     * @param string $demandeurNomPrenom
     *
     * @return DemandeInfos
     */
    public function setDemandeurNomPrenom($demandeurNomPrenom)
    {
        $this->demandeurNomPrenom = $demandeurNomPrenom;

        return $this;
    }

    /**
     * Get demandeurNomPrenom
     *
     * @return string
     */
    public function getDemandeurNomPrenom()
    {
        return $this->demandeurNomPrenom;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return DemandeInfos
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return DemandeInfos
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
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     *
     * @return DemandeInfos
     */
    public function setDateDemande($dateDemande)
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    /**
     * Get dateDemande
     *
     * @return \DateTime
     */
    public function getDateDemande()
    {
        return $this->dateDemande;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     *
     * @return DemandeInfos
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set commentaireAncreai
     *
     * @param string $commentaireAncreai
     *
     * @return DemandeInfos
     */
    public function setCommentaireAncreai($commentaireAncreai)
    {
        $this->commentaireAncreai = $commentaireAncreai;

        return $this;
    }

    /**
     * Get commentaireAncreai
     *
     * @return string
     */
    public function getCommentaireAncreai()
    {
        return $this->commentaireAncreai;
    }

    /**
     * Add referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     *
     * @return DemandeInfos
     */
    public function addReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        $this->referentielPublic[] = $referentielPublic;

        return $this;
    }

    /**
     * Remove referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     */
    public function removeReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
    {
        $this->referentielPublic->removeElement($referentielPublic);
    }

    /**
     * Get referentielPublic
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferentielPublic()
    {
        return $this->referentielPublic;
    }

    /**
     * Set etablissementService
     *
     * @param string $etablissementService
     *
     * @return DemandeInfos
     */
    public function setEtablissementService($etablissementService)
    {
        $this->etablissementService = $etablissementService;

        return $this;
    }

    /**
     * Get etablissementService
     *
     * @return string
     */
    public function getEtablissementService()
    {
        return $this->etablissementService;
    }

    /**
     * Set creai
     *
     * @param \Pericles3Bundle\Entity\Creai $creai
     *
     * @return DemandeInfos
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

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return DemandeInfos
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
     * Set etat
     *
     * @param \Pericles3Bundle\Entity\DemandeEtat $etat
     *
     * @return DemandeInfos
     */
    public function setEtat(\Pericles3Bundle\Entity\DemandeEtat $etat = null)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return \Pericles3Bundle\Entity\DemandeEtat
     */
    public function getEtat()
    {
        return $this->etat;
    }
    
    public function IsFini()
    {
        return $this->etat->IsFini();
    }
     
    public function ATraiter()
    {
        return $this->etat->ATraiter();
    }
     
    


    /**
     * Set remarques
     *
     * @param string $remarques
     *
     * @return DemandeInfos
     */
    public function setRemarques($remarques)
    {
        $this->remarques = $remarques;

        return $this;
    }

    /**
     * Get remarques
     *
     * @return string
     */
    public function getRemarques()
    {
        return $this->remarques;
    }

    /**
     * Set public
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $public
     *
     * @return DemandeInfos
     */
    public function setPublic(\Pericles3Bundle\Entity\ReferentielPublic $public = null)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return \Pericles3Bundle\Entity\ReferentielPublic
     */
    public function getPublic()
    {
        return $this->public;
    }
}
