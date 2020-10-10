<?php
 
namespace Aldaflux\SireneApiBundle\Service;
 
#use  Aldaflux\YoutubeUtilsBundle\Utils\ApiYoutube;
#use  Aldaflux\YoutubeUtilsBundle\Utils\ApiYoutubeVideo;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

 
class SireneApiService 
{
    protected static  $logs = array();
    protected static  $calls = array();
    protected static  $errorlogs = array();

    
    private $params;
    protected static $token;
    protected static $used;
    private $key;
    private $secret;
    private $url;
    


    public function __construct(ContainerInterface $container)
    {
        $this->key=$container->getParameter("sirene_key");
        $this->secret=$container->getParameter("sirene_secret");
        $this->url="https://api.insee.fr/entreprises/sirene/V3/";
        
    }
    
    
    public function getLogs()
    {
        return self::$logs;
    }
    
    public function getErrorLogs()
    {
        return self::$errorlogs;
    }
    
    
    function GetAuthorization64()
    {
        return(base64_encode($this->key.":".$this->secret));
    }
    
      
    function GetUsed()
    {
        return(self::$used);
    }
    
    
        
    function GetToken()
    {
        
        self::$used=true;
        
        if (self::$token)
        {
            return(self::$token);
        }
        else
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.insee.fr/token");
            $authorization = "Authorization: Basic ".$this->GetAuthorization64(); 
            $headers = array( 'Accept' => 'application/json',$authorization);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);        
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $server_output = curl_exec($ch);
            $jsonToken=json_decode($server_output);
            self::$token = $jsonToken->access_token;
            self::$logs[]=$jsonToken;
            return(self::$token);
            
        }
        

    }
    
    
    function GetJson($url)
    {
        return($this->GetUrlJson($this->url.$url));
    }
    
    function GetUrlJson($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $authorization = "Authorization: Bearer ".$this->GetToken(); // Prepare the authorisation token
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
        $server_output = curl_exec ($ch);
        
        $result=json_decode($server_output);
        
        if ($result)
        {
            $log=new \stdClass();
            $log->url=$url;
            $log->result=$result;
            self::$logs[]=$log;
            return($result);            
        }
        else
        {
            $log=new \stdClass();
            $log->url=$url;
            $log->message="L'appel n' rien donné !!";
            self::$errorlogs[]=$log;
        }
        

    }
    
         
    function GetSiretInfo($siret)
    {
        $url="siret/".$siret."?masquerValeursNulles=true";
        //        $url="https://api.insee.fr/entreprises/sirene/V3/siret/".$siret."?masquerValeursNulles=true";

        return($this->GetJson($url)->etablissement);
    }
    function GetSirenInfo($siren)
    {
        $url="siren/".$siren."?masquerValeursNulles=true";
        return($this->GetJson($url));
    }
    
    function Search($occurence)
    {
        $url='siren?q=periode(denominationUniteLegale:Mission)&nombre=1919';
        //        $url="https://api.insee.fr/entreprises/sirene/V3/siret/".$siret."?masquerValeursNulles=true";

        return($this->GetJson($url));
    }
    
    
    
    
    
}


        
        




