<?php

namespace Pericles3Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * DemandeGestionnaire
 *
 * @ORM\Table(name="demande_gestionnaire")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\DemandeGestionnaireRepository")
 */
class DemandeGestionnaire
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
     * @ORM\Column(name="gestionnaire_nom", type="string", length=255)
     */
    private $gestionnaire_nom;

    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $demandeur_nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $demandeur_prenom;


    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="codePostal", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="commentaire_ancreai", type="text", nullable=true)
     */
    private $commentaireAncreai;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire_creai", type="text", nullable=true)
     */
    private $commentaireCreai;

    
        
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Creai", inversedBy="demandesGestionnaire")
    */
    private $creai;


     
    /**
    * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\DemandeEtat", inversedBy="demandesGestionnaire")
    */
    private $etat;

    
    
     
    /**
    * @ORM\OneToOne(targetEntity="Pericles3Bundle\Entity\Gestionnaire", inversedBy="demandeGestionnaire")
    */
    private $Gestionnaire;

    
       

    
    
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
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\DemandeEtablissement",mappedBy="demandeGestionnaire")
     */
    private $demandesEtablissement;
    

        
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDemande", type="datetime")
     */
    private $dateDemande;

    

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\FinessGestionnaire", inversedBy="demandesGestionnaire")
     * @ORM\JoinColumn(name="finess", referencedColumnName="code_finess", nullable=true) 
     */
    private $finess;
    
    

    
    
    
    
    function __toString() 
    {
        return($this->demandeur_prenom.".".$this->demandeur_nom);
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->demandesEtablissement = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return DemandeGestionnaire
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
     * @return DemandeGestionnaire
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
     * @return DemandeGestionnaire
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
     * Add demandesEtablissement
     *
     * @param \Pericles3Bundle\Entity\DemandeEtablissement $demandesEtablissement
     *
     * @return DemandeGestionnaire
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
    
    
    
    public function getNbEtablissements()
    {
        return count($this->demandesEtablissement);
    }
    
    
    

    /**
     * Set gestionnaireNom
     *
     * @param string $gestionnaireNom
     *
     * @return DemandeGestionnaire
     */
    public function setGestionnaireNom($gestionnaireNom)
    {
        $this->gestionnaire_nom = $gestionnaireNom;

        return $this;
    }

    /**
     * Get gestionnaireNom
     *
     * @return string
     */
    public function getGestionnaireNom()
    {
        return $this->gestionnaire_nom;
    }

    /**
     * Set commentaireAncreai
     *
     * @param string $commentaireAncreai
     *
     * @return DemandeGestionnaire
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
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     *
     * @return DemandeGestionnaire
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
     * Set commentaireCreai
     *
     * @param string $commentaireCreai
     *
     * @return DemandeGestionnaire
     */
    public function setCommentaireCreai($commentaireCreai)
    {
        $this->commentaireCreai = $commentaireCreai;

        return $this;
    }

    /**
     * Get commentaireCreai
     *
     * @return string
     */
    public function getCommentaireCreai()
    {
        return $this->commentaireCreai;
    }

    /**
     * Set creai
     *
     * @param \Pericles3Bundle\Entity\Creai $creai
     *
     * @return DemandeGestionnaire
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
     * @return DemandeGestionnaire
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
    
    
    public function getEtatIdEtablissements()
    {
        $etat=3;
        foreach ($this->getDemandesEtablissement()  as $Demande)
        {
            $etat=min($Demande->getEtat()->GetId(),$etat);
        }
        return $etat;
    }
    
    

    /**
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return DemandeGestionnaire
     */
    public function setGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire = null)
    {
        $this->Gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \Pericles3Bundle\Entity\Gestionnaire
     */
    public function getGestionnaire()
    {
        return $this->Gestionnaire;
    }
    
    public function hasGestionnaire()
    {
        return $this->Gestionnaire;
    }
    
    
    

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return DemandeGestionnaire
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
     * @return DemandeGestionnaire
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
     * @return DemandeGestionnaire
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
     * Set finess
     *
     * @param \Pericles3Bundle\Entity\FinessGestionnaire $finess
     *
     * @return DemandeGestionnaire
     */
    public function setFiness(\Pericles3Bundle\Entity\FinessGestionnaire $finess = null)
    {
        $this->finess = $finess;

        return $this;
    }

    /**
     * Get finess
     *
     * @return \Pericles3Bundle\Entity\FinessGestionnaire
     */
    public function getFiness()
    {
        return $this->finess;
    }
}
