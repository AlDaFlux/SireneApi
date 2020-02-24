<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Sauvegarde controller.
 *
 * @Route("backoffice/sirene")
 */
class SireneController extends Controller
{
    
     /**
     * Lists all sauvegarde entities.
     *
     * @Route("/", name="backoffice_sauvegarde_index")
     * @Method("GET")
     */
    public function indexAction()
    {
       
    $ch = curl_init();
    
    $token="be38fe53-67fc-393f-b47f-a67d46344fcf";
    $url="https://api.insee.fr/entreprises/sirene/V3/siret/44827548700032?masquerValeursNulles=true";
    
    curl_setopt($ch, CURLOPT_URL, $url);
    $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects

    
    
    $server_output = curl_exec ($ch);
    $siren=json_decode($server_output);
    dump($siren);
    
    return $this->render('BackOffice/Sirene/sirene.html.twig', array('siren' => $siren));
    

/*    curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
            $head = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch); 
            
        /*curl -X GET --header 'Accept: application/json' --header 'Authorization: Bearer be38fe53-67fc-393f-b47f-a67d46344fcf' 'https://api.insee.fr/entreprises/sirene/V3/siret/44827548700032?masquerValeursNulles=true'*/
        
    }
     
    
     function jwt_request($token, $post) 
     {

       header('Content-Type: application/json'); // Specify the type of data
       $ch = curl_init('https://APPURL.com/api/json.php'); // Initialise cURL
       $post = json_encode($post); // Encode the data array into a JSON string
       $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
       $result = curl_exec($ch); // Execute the cURL statement
       curl_close($ch); // Close the cURL connection
       return json_decode($result); // Return the received data

    }
    
    
    
}
