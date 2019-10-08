<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Bibliotheque
 *
 * @ORM\Table(name="bibliotheque")
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\BibliothequeRepository")
 */
class Bibliotheque
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
     * @ORM\Column(name="thematique", type="string", length=255)
     */
    private $thematique;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="type_message", type="string", length=255)
     */
    private $type_message;
    
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="href", type="string", length=255, nullable=true)
     */
    private $href;

    /**
     * @var string
     *
     * @ORM\Column(name="fichier", type="string", length=255, nullable=true)
     */
    private $fichier;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime")
     */
    private $dateUpdate;

 
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement", inversedBy="bibliotheques")
     *
     * @ORM\JoinColumn(name="etablissement_id", nullable=true)
     */
    private $etablissement;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Gestionnaire", inversedBy="bibliotheques")
     */
    private $gestionnaire;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User", inversedBy="bibliotheques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    
    
    /**
     * @ORM\OneToMany(targetEntity="Pericles3Bundle\Entity\Preuve",mappedBy="bibliotheque")
     */
    private $preuves;
    
    
    

    
    
    
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
     * Set thematique
     *
     * @param string $thematique
     *
     * @return Bibliotheque
     */
    public function setThematique($thematique)
    {
        $this->thematique = $thematique;

        return $this;
    }

    /**
     * Get thematique
     *
     * @return string
     */
    public function getThematique()
    {
        return $this->thematique;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Bibliotheque
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Set href
     *
     * @param string $href
     *
     * @return Bibliotheque
     */
    public function setHref($href)
    {
    	$this->href = $href;
    
    	return $this;
    }
    
    /**
     * Get href
     *
     * @return string
     */
    public function getHref()
    {
    	return $this->href;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Bibliotheque
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }
    
    public function setEtablissement(Etablissement $etablissement = null)
    {
    	$this->etablissement = $etablissement;
    
    	return $this;
    }
    
    public function getEtablissement()
    {
    	return $this->etablissement;
    }

   
    

    /**
     * Set gestionnaire
     *
     * @param \Pericles3Bundle\Entity\Gestionnaire $gestionnaire
     *
     * @return Bibliotheque
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
     * Set fichier
     *
     * @param string $fichier
     *
     * @return Bibliotheque
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * Get fichier
     *
     * @return string
     */
    public function getFichier()
    {
        return $this->fichier;
    }
    
    
    
   /**
     * Récupère le chemin relatif du fichier
     *
     * @return string
     */
    public function getRelativPath()
    {
        $reponse=$this->getRelativPathFolder()."/".$this->getFichier();
        return ($reponse);
    }
    
  
   /**
     * Récupère le chemin relatif du fichier
     *
     * @return string
     */
    public function getRelativPathFolder()
    {
        if ($this->etablissement) $reponse=$this->etablissement->GetUploadFolderPath();
        else $reponse=$this->gestionnaire->GetUploadFolderPath();
        $reponse.="/bibliotheque";
        return ($reponse);
    }
    

    public function getFileExist()
    {
      $path=getcwd()."/upload/".$this->getRelativPath();   
      return(file_exists($path));
    }
    
           
    
    

    /**
     * Set typeMessage
     *
     * @param string $typeMessage
     *
     * @return Bibliotheque
     */
    public function setTypeMessage($typeMessage)
    {
        $this->type_message = $typeMessage;

        return $this;
    }

    /**
     * Get typeMessage
     *
     * @return string
     */
    public function getTypeMessage()
    {
        return $this->type_message;
    }

    public function getLibOrder()
    {
        return strtolower(trim($this->type_message));
    }

    
    
    /**
     * Set user
     *
     * @param \Pericles3Bundle\Entity\User $user
     *
     * @return Bibliotheque
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
     * Constructor
     */
    public function __construct()
    {
        $this->preuves = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add preufe
     *
     * @param \Pericles3Bundle\Entity\Preuve $preufe
     *
     * @return Bibliotheque
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
    
    public function getNbPreuves()
    {
        return count($this->preuves);
    }
    

    public function __toString() 
    {
        if ($this->message) return $this->message;
        else return("-");
    }
    
    
    
}
