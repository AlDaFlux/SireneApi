<?php
 
namespace Aldaflux\SireneApiBundle\Service;




use Aldaflux\SireneApiBundle\Service\SireneApiService;
 

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

 
class SireneSiretService 
{
    private $sireneService;
    private $codeSiret;
    private $siretInfo;

    public function __construct(SireneApiService $sireneService)
    {
        $this->sireneService=$sireneService;
    }
    
    

    public function setCodeSiret($codeSiret)
    {
        $this->codeSiret=$codeSiret;
        $this->siretInfo=$this->sireneService->GetSiretInfo($this->codeSiret);
        dump($this->siretInfo);
        
        
    }
    
    
    public function notFound()
    {
        return(! $this->siretInfo);
    }
    
    public function getCodePostal()
    {
        if (isset($this->siretInfo->adresseEtablissement))
        {
            if (isset($this->siretInfo->adresseEtablissement->codePostalEtablissement))
            {
                return($this->siretInfo->adresseEtablissement->codePostalEtablissement);
            }
        }
    }
    
    public function getEtablissementNom()
    {
        if (isset($this->siretInfo->uniteLegale))
        {
            if (isset($this->siretInfo->uniteLegale->denominationUniteLegale))
            {
                return($this->siretInfo->uniteLegale->denominationUniteLegale);
            }
        }
    }
    
    public function getVille()
    {
        if (isset($this->siretInfo->adresseEtablissement))
        {
            if (isset($this->siretInfo->adresseEtablissement->libelleCommuneEtablissement))
            {
                return($this->siretInfo->adresseEtablissement->libelleCommuneEtablissement);
            }
        }
    }
    
    function getAdresse()
    {
        $adresse="";
        if (isset($this->siretInfo->adresseEtablissement))
        {
            if (isset($this->siretInfo->adresseEtablissement->numeroVoieEtablissement))
            {
                $adresse.=" ".$this->siretInfo->adresseEtablissement->numeroVoieEtablissement;
            }
            if (isset($this->siretInfo->adresseEtablissement->typeVoieEtablissement))
            {
                $adresse.=" ".$this->siretInfo->adresseEtablissement->typeVoieEtablissement;
            }
            if (isset($this->siretInfo->adresseEtablissement->libelleVoieEtablissement))
            {
                $adresse.=" ".$this->siretInfo->adresseEtablissement->libelleVoieEtablissement;
            }
            
        }
        return(trim($adresse));

//        $demandeEtablissement->setAdresse($sirenInfo->adresseEtablissement->numeroVoieEtablissement." ".$sirenInfo->adresseEtablissement->typeVoieEtablissement." ".$sirenInfo->adresseEtablissement->libelleVoieEtablissement);
        
    }
    
    
    

    
    
}


        
        




