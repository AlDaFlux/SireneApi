<?php

namespace Pericles3Bundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * DemandeEtablissement
 *
 * @ORM\Table(name="demande_etablissement")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DemandeEtablissementRepository")
 */
class DemandeEtablissement
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $demandeur_nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $demandeur_prenom;

    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="etablissement_nom", type="string", length=255, nullable=true)
     */
    private $etablissementNom;


    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;



    /**
     * @var string
     *
     * @ORM\Column(name="finess_code", type="string", length=255, unique=true, nullable=true)
     * @Assert\Length(
     *      min = 9,
     *      max = 9,
     *      minMessage = "Le code Finess doit être composé de 9 chiffres",
     *      maxMessage = "Le code Finess doit être composé de 9 chiffres"
     * )
     */
    private $finess_code;



    
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Creai", inversedBy="demandesEtablissement")
    */
    private $creai;

    
    
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\DemandeEtat", inversedBy="demandesEtablissement")
    */
    private $etat;

    


        
     
    /**
    * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="demandeEtablissement")
    * @ORM\JoinColumn(name="etablissement_id", referencedColumnName="id", nullable=true) 
    */
    private $Etablissement;

    
    
    
       

    
    
    


    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\DemandeGestionnaire",inversedBy="demandesEtablissement")
     */
    private $demandeGestionnaire;
    

    
    /**
     * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\Finess", inversedBy="demandesEtablissement")
     * @ORM\JoinColumn(name="finess", referencedColumnName="code_finess", nullable=true) 
     */
    private $finess;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDemande", type="datetime")
     */
    private $dateDemande;


    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ReferentielPublic", inversedBy="demandesEtablissement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $referentielPublic;



    /**
     * @var string
     *
     * @ORM\Column(name="commentaire_ancreai", type="text", nullable=true)
     */
    private $commentaireAncreai;

    

    /**
     * @var string
     *
     * @ORM\Column(name="type_client", type="string", length=255,  nullable=true)
     */
    private $typeClient;


    
         
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ModeCotisation", inversedBy="etablissements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modeCotisation;


    
    
    
    function __toString() 
    {
        if ($this->demandeur_nom.$this->demandeur_prenom) return($this->demandeur_nom . " " .$this->demandeur_prenom);
        else return($this->etablissementNom);
    }
    


    
    public function getEtablissementByFiness()
    {
        if ($this->finess) return($this->finess->GetEtablissement());
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
     * Set demandeurNom
     *
     * @param string $demandeurNom
     *
     * @return DemandeEtablissement
     */
    public function setDemandeurNom($demandeurNom)
    {
        $this->demandeur_nom = $demandeurNom;

        return $this;
    }

    /**
     * Get demandeurNom
     *
     * @return string
     */
    public function getDemandeurNom()
    {
        return $this->demandeur_nom;
    }

    /**
     * Set demandeurPrenom
     *
     * @param string $demandeurPrenom
     *
     * @return DemandeEtablissement
     */
    public function setDemandeurPrenom($demandeurPrenom)
    {
        $this->demandeur_prenom = $demandeurPrenom;

        return $this;
    }

    /**
     * Get demandeurPrenom
     *
     * @return string
     */
    public function getDemandeurPrenom()
    {
        return $this->demandeur_prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return DemandeEtablissement
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
     * Set finessCode
     *
     * @param string $finessCode
     *
     * @return DemandeEtablissement
     */
    public function setFinessCode($finessCode)
    {
        $this->finess_code = $finessCode;

        return $this;
    }

    /**
     * Get finessCode
     *
     * @return string
     */
    public function getFinessCode()
    {
        return $this->finess_code;
    }

    /**
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     *
     * @return DemandeEtablissement
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
     * Set commentaireAncreai
     *
     * @param string $commentaireAncreai
     *
     * @return DemandeEtablissement
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
     * Set demandeGestionnaire
     *
     * @param \Pericles3Bundle\Entity\DemandeGestionnaire $demandeGestionnaire
     *
     * @return DemandeEtablissement
     */
    public function setDemandeGestionnaire(\Pericles3Bundle\Entity\DemandeGestionnaire $demandeGestionnaire = null)
    {
        $this->demandeGestionnaire = $demandeGestionnaire;

        return $this;
    }

    /**
     * Get demandeGestionnaire
     *
     * @return \Pericles3Bundle\Entity\DemandeGestionnaire
     */
    public function getDemandeGestionnaire()
    {
        return $this->demandeGestionnaire;
    }

    /**
     * Set finess
     *
     * @param \Pericles3Bundle\Entity\Finess $finess
     *
     * @return DemandeEtablissement
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

    /**
     * Set referentielPublic
     *
     * @param \Pericles3Bundle\Entity\ReferentielPublic $referentielPublic
     *
     * @return DemandeEtablissement
     */
    public function setReferentielPublic(\Pericles3Bundle\Entity\ReferentielPublic $referentielPublic)
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

    /**
     * Set creai
     *
     * @param \Pericles3Bundle\Entity\Creai $creai
     *
     * @return DemandeEtablissement
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
     * Set etat
     *
     * @param \Pericles3Bundle\Entity\DemandeEtat $etat
     *
     * @return DemandeEtablissement
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

    /**
     * Set etablissementNom
     *
     * @param string $etablissementNom
     *
     * @return DemandeEtablissement
     */
    public function setEtablissementNom($etablissementNom)
    {
        $this->etablissementNom = $etablissementNom;

        return $this;
    }

    /**
     * Get etablissementNom
     *
     * @return string
     */
    public function getEtablissementNom()
    {
        return $this->etablissementNom;
    }

    /**
     * Set typeClient
     *
     * @param string $typeClient
     *
     * @return DemandeEtablissement
     */
    public function setTypeClient($typeClient)
    {
        $this->typeClient = $typeClient;

        return $this;
    }

    /**
     * Get typeClient
     *
     * @return string
     */
    public function getTypeClient()
    {
        return $this->typeClient;
    }

    /**
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return DemandeEtablissement
     */
    public function setEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement = null)
    {
        $this->Etablissement = $etablissement;
        return $this;
    }   

    /**
     * Get etablissement
     *
     * @return \Pericles3Bundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->Etablissement;
    }

    /**
     * Set modeCotisation
     *
     * @param \Pericles3Bundle\Entity\ModeCotisation $modeCotisation
     *
     * @return DemandeEtablissement
     */
    public function setModeCotisation(\Pericles3Bundle\Entity\ModeCotisation $modeCotisation)
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
}