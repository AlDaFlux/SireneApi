<?php

namespace Pericles3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Preuve
 *
 * @ORM\Table(name="preuve")
 * @ORM\Entity(repositoryClass="Pericles3Bundle\Repository\PreuveRepository")
 */
class Preuve
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
     * @ORM\Column(name="type_preuve", type="text", nullable=true)
     */
    private $type_preuve;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fichier", type="string", length=255)
     */
    private $fichier;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Critere",inversedBy="preuves")
     */
    private $critere;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\ObjectifOperationnel",inversedBy="preuves")
     */
    private $objectifOperationnel;

    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Domaine",inversedBy="preuves")
     */
    private $domaine;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\User",inversedBy="preuves")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Etablissement",inversedBy="preuves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etablissement;    
    
        
    /**
     * @ORM\ManyToOne(targetEntity="Pericles3Bundle\Entity\Bibliotheque",inversedBy="preuves")
     */
    private $bibliotheque;
    
    
    
    
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;


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
     * Set fichier
     *
     * @param string $fichier
     *
     * @return Preuve
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
        if ($this->getBibliotheque())
        {
            return($this->getBibliotheque()->getFichier());
        }
        else
        {
            return $this->fichier;
        }
    }
    
    
    public function getHref()
    {
        if ($this->getBibliotheque())
        {
            return($this->getBibliotheque()->getHref());
        }
    }
    
    
    

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Preuve
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

    public function setCritere(Critere $critere)
    {
        $this->critere = $critere;

        return $this;
    }

    public function getCritere()
    {
        return $this->critere;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Preuve
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
     * Set typePreuve
     *
     * @param string $typePreuve
     *
     * @return Preuve
     */
    public function setTypePreuve($typePreuve)
    {
        $this->type_preuve = $typePreuve;

        return $this;
    }

    /**
     * Get typePreuve
     *
     * @return string
     */
    public function getTypePreuve()
    {
        return $this->type_preuve;
    }

    public function getTypePreuveLib()
    {
        if ($this->type_preuve=='critere') {return("Evaluation");}
        elseif  ($this->type_preuve=='objectif_operationnel') {return("Paq");}
        elseif  ($this->type_preuve=='pdv') {return("Usager");}
        else {return("????");}
    }

    /**
     * Set objectifOperationnel
     *
     * @param \Pericles3Bundle\Entity\ObjectifOperationnel $objectifOperationnel
     *
     * @return Preuve
     */
    public function setObjectifOperationnel(\Pericles3Bundle\Entity\ObjectifOperationnel $objectifOperationnel = null)
    {
        $this->objectifOperationnel = $objectifOperationnel;

        return $this;
    }

    /**
     * Get objectifOperationnel
     *
     * @return \Pericles3Bundle\Entity\ObjectifOperationnel
     */
    public function getObjectifOperationnel()
    {
        return $this->objectifOperationnel;
    }

    /**
     * Set etablissement
     *
     * @param \Pericles3Bundle\Entity\Etablissement $etablissement
     *
     * @return Preuve
     */
    public function setEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement)
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
     * Récupère le chemin relatif du fichier
     *
     * @return string
     */
    public function getRelativPath()
    {
        if ($this->getBibliotheque())
        {
            return($this->getBibliotheque()->getRelativPath());
        }
        else
        {
            $reponse=$this->etablissement->GetUploadFolderPath();
            $reponse.="/preuves";
            $reponse.="/".$this->getFichier();
            return ($reponse);
        }
    }
    
     
    
    public function getFileExist()
    {
      $path=getcwd()."/upload/".$this->getRelativPath();   
      return(file_exists($path));
    }
    
    public function getFileExistInBilioEtab()
    {
        
        $path=getcwd()."/upload/";   
        $path.=$this->etablissement->GetUploadFolderPath();
        $path.="/bibliotheque";
        $path.="/".$this->getFichier();
        if (file_exists($path))
        {
            return($path);
        }
    }
    
    public function getFileExistInBilioGestionnaire()
    {
        if ($this->etablissement->GetGestionnaire())
        {
            $path=getcwd()."/upload/";   
            $path.=$this->etablissement->GetGestionnaire()->GetUploadFolderPath();
            $path.="/bibliotheque";
            $path.="/".$this->getFichier();
            if (file_exists($path))
            {
                return($path);
            }
        }
    }
    
    
   /**
     * Récupère le chemin relatif du fichier
     *
     * @return string
     */
    public function getNbPreuvesFichier()
    {
         // utilisé ?  // DESUET ? 
        $reponse=0;
        if ($this->getEtablissement()->getPreuves())
        {
            foreach ($this->getEtablissement()->getPreuves() as $tmp_preuve)
            {
                //$reponse.="-->".$tmp_preuve->getFichier();
                if ($tmp_preuve->getFichier() == $this->getFichier()) { $reponse++;}
            }
        }
        return($reponse);
    }
    

    
           
    /**
     * toString
     * @return string
     */
    public function __toString() 
    {
        $reponse="--";
        if ($this->getBibliotheque()) { $reponse=$this->getBibliotheque()->getMessage(); }
        elseif ($this->getFichier() ) { $reponse=$this->getFichier();}
        if ($this->getcommentaire())  $reponse.= " (".$this->getcommentaire().")";
        
        return $reponse;
        


        /*
        
        elseif ($this->getFichier() $this->getFichier());
        else ( $this->getFichier());
         * 
         */
        
    }
    
    
    

    /**
     * Set domaine
     *
     * @param \Pericles3Bundle\Entity\Domaine $domaine
     *
     * @return Preuve
     */
    public function setDomaine(\Pericles3Bundle\Entity\Domaine $domaine = null)
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
     * Set bibliotheque
     *
     * @param \Pericles3Bundle\Entity\Bibliotheque $bibliotheque
     *
     * @return Preuve
     */
    public function setBibliotheque(\Pericles3Bundle\Entity\Bibliotheque $bibliotheque = null)
    {
        $this->bibliotheque = $bibliotheque;

        return $this;
    }

    /**
     * Get bibliotheque
     *
     * @return \Pericles3Bundle\Entity\Bibliotheque
     */
    public function getBibliotheque()
    {
        return $this->bibliotheque;
    }
}
