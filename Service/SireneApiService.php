<?php
 
namespace Aldaflux\SireneApiBundle\Service;
 
#use  Aldaflux\YoutubeUtilsBundle\Utils\ApiYoutube;
#use  Aldaflux\YoutubeUtilsBundle\Utils\ApiYoutubeVideo;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

 
class SireneApiService 
{
    
    private $params;
    private $token;
    private $key;
    private $secret;

    public function __construct(ParameterBagInterface $params)
    {
        $this->key=$params->get("sirene_key");
        $this->secret=$params->get("sirene_secret");
    }

    function GetAuthorization64()
    {
        return(base64_encode($this->key.":".$this->secret));
    }
    
    
    function GetToken()
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
        return($jsonToken->access_token);
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
        return(json_decode($server_output));
    }
    
         
    function GetSiretInfo($siret)
    {
        $url="https://api.insee.fr/entreprises/sirene/V3/siret/".$siret."?masquerValeursNulles=true";
        return($this->GetUrlJson($url)->etablissement);
    }
    
    
    
    
    
}


        
        




