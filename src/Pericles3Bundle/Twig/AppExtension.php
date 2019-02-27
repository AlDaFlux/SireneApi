<?php

// src/AppBundle/Twig/AppExtension.php
namespace Pericles3Bundle\Twig;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints as Assert;
//Twig_Extension_GlobalsInterface

//use Twig_Extension_GlobalsInterface;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

    

//use Pericles3Bundle\Service\PericlesService;



//class AppExtension extends \Twig_Extension implements Twig_Extension_GlobalsInterface
class AppExtension extends \Twig_Extension 
{   
    
    protected $em;
    protected $container;
    protected $service;

    public function __construct($em,$container,$context)
    {
        $this->em = $em;
        $this->container = $container;
        $this->context = $context;
    }
    
    public function getName()
    {
        return 'app_extension';
    }
    
/*
    public function getAbcData()
    {
        $token = $this->context->getToken();

        if (!is_object($token) || !is_object($token->getUser())) {
            return null;
        }

        return array(
            'data_array'   => $token->getUser(),
        );
    }
*/

    public function getGlobals()
    {
        $BibliothequeAncreaiTypeSources=$this->em->getRepository('Pericles3Bundle:BibliothequeAncreaiTypeSource')->findAll();
        $BibliothequeTypesDoc=$this->em->getRepository('Pericles3Bundle:BibiolthequeTypeDoc')->findAll();
        $ReferentielsPublic=$this->em->getRepository('Pericles3Bundle:ReferentielPublic')->findAll();
        $ReferentielsPublicNonfini=$this->em->getRepository('Pericles3Bundle:ReferentielPublic')->findNonfini();
        
        $ReferentielsPublicAll=$this->em->getRepository('Pericles3Bundle:ReferentielPublic')->findVeryAll();
        

        $Departements=$this->em->getRepository('Pericles3Bundle:Departement')->findAll();
        $Creais=$this->em->getRepository('Pericles3Bundle:Creai')->findAll();
        $referentielPublics=$this->em->getRepository('Pericles3Bundle:ReferentielPublic')->findAll();
        
        
        $NbDemandesEtablissementATraiter=$this->em->getRepository('Pericles3Bundle:DemandeEtablissement')->findATraiterNb();
        $NbDemandesEtablissementNonFinies=$this->em->getRepository('Pericles3Bundle:DemandeEtablissement')->findNonFiniNb();
        $DemandesEtablissementNonFinies=$this->em->getRepository('Pericles3Bundle:DemandeEtablissement')->findNonFini();
        
        $NbDemandesGestionnaireATraiter=$this->em->getRepository('Pericles3Bundle:DemandeGestionnaire')->findATraiterNb();
        $NbDemandesGestionnaireNonFinies=$this->em->getRepository('Pericles3Bundle:DemandeGestionnaire')->findNonFiniNb();
        $DemandesGestionnaireNonFinies=$this->em->getRepository('Pericles3Bundle:DemandeGestionnaire')->findNonFini();
        
        $GestionnaireReels=$this->em->getRepository('Pericles3Bundle:Gestionnaire')->findReels();
        
        
        $NbDemandeInfosSansCreaiNonFinies = $this->em->getRepository('Pericles3Bundle:DemandeInfos')->findNbNonFiniSansCreai();
        $NbDemandeInfosSansCreaiATraiter = $this->em->getRepository('Pericles3Bundle:DemandeInfos')->findATraiterNbSansCreai();
        
        
        
        $EtabCategories = $this->em->getRepository('Pericles3Bundle:EtablissementCategory')->findAll();
        $EtabModesCotisation = $this->em->getRepository('Pericles3Bundle:ModeCotisation')->findAll();
        $EtabStockages = $this->em->getRepository('Pericles3Bundle:StockageEtablissement')->findAll();

        $Factures= $this->em->getRepository('Pericles3Bundle:Facture')->findAll();
        
        $SommeFacturee= $this->em->getRepository('Pericles3Bundle:FacturePresta')->SommeFacturee()["total"];
        
        
        $facturees_ren_non_payees=$this->em->getRepository('Pericles3Bundle:FacturePresta')->findSomme(false,true)['total'];
        $facturees_ren_payees=$this->em->getRepository('Pericles3Bundle:FacturePresta')->findSomme(true,true)['total'];
        $facturees_non_payees=$this->em->getRepository('Pericles3Bundle:FacturePresta')->findSomme(false,false)['total'];
        $facturees_payees=$this->em->getRepository('Pericles3Bundle:FacturePresta')->findSomme(true,false)['total'];

        
        
        
        $FacturesNumARenewObj= $this->em->getRepository('Pericles3Bundle:Facture')->findFactureARenouvellerNum();
        $FacturesNumARenew=array();
        
        foreach ($FacturesNumARenewObj as $fact)
        {
            $FacturesNumARenew[]=$fact['numFacture'];
//            $FacturesNumARenew
        }
        
        $FacturesARenouveller=  new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($Factures as $facture)
        { 
            if (in_array($facture->GetNumFacture(), $FacturesNumARenew)) $FacturesARenouveller->Add($facture);
        }
        
        
        

        
        
        for ($year=2017;$year<=(int)date("Y"); $year++)
        {
            $ActivityYears[]=$year;           
        }
        
        
        return array(
            'BibliothequeAncreaiTypeSources' => $BibliothequeAncreaiTypeSources,
            'BibliothequeTypesDoc' => $BibliothequeTypesDoc,
            'ReferentielsPublic' => $ReferentielsPublic,
            'Departements' => $Departements,
            'Creais' => $Creais,
            'referentielPublics' => $referentielPublics,
            'ReferentielsPublicAll' => $ReferentielsPublicAll,
            'ReferentielsPublicNonfini' => $ReferentielsPublicNonfini,
            'NbDemandesEtablissementNonFinies' =>$NbDemandesEtablissementNonFinies,
            'DemandesEtablissementNonFinies' =>$DemandesEtablissementNonFinies,
            'NbDemandesGestionnaireNonFinies' =>$NbDemandesGestionnaireNonFinies,
            'NbDemandesEtablissementATraiter' =>$NbDemandesEtablissementATraiter,
            'NbDemandesGestionnaireATraiter' =>$NbDemandesGestionnaireATraiter,
            'DemandesGestionnaireNonFinies' =>$DemandesGestionnaireNonFinies,
            'NbDemandeInfosSansCreaiNonFinies' => $NbDemandeInfosSansCreaiNonFinies,
            'NbDemandeInfosSansCreaiATraiter' => $NbDemandeInfosSansCreaiATraiter,
            'EtabCategories' => $EtabCategories ,
            'EtabStockages' => $EtabStockages ,
            'EtabModesCotisation' => $EtabModesCotisation ,
            'ActivityYears' => $ActivityYears,
            'FacturesARenouveller' => $FacturesARenouveller,
            'NbFacturesARenouveller' => count($FacturesARenouveller),
            'GestionnaireReels' => $GestionnaireReels,
            'SommeFacturee' => $SommeFacturee,
            
            'factureesRenNonPayees' => $facturees_ren_non_payees,
            'factureesRenPayees' => $facturees_ren_payees,
            'factureesNonPayees' => $facturees_non_payees,
            'factureesPayees' => $facturees_payees,
        

        );
    }


    
    
    
    /*
    public function getGlobals()
    {
        return array(
            'domaines_referentiel' => array("0" => "tous")
        );
    }      

        
    public function getGlobals()
    {
        $domaines=$this->getDimensions();
        return (array('domaines' => $domaines));
        return array(
            'domaines' => $this->getDomaines(),
            'user' => $this->getUser()
        );
         * 
    }      
     */
    
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('PAQstatut', array($this, 'PAQstatutFilter')),
            new \Twig_SimpleFilter('critereStateCss', array($this, 'critereStateCss')),
            new \Twig_SimpleFilter('critereStateLib', array($this, 'critereStateLib')),
            new \Twig_SimpleFilter('StatePuce', array($this, 'StatePuce')),
            new \Twig_SimpleFilter('order', array($this, 'order')),
            new \Twig_SimpleFilter('typePreuveLib', array($this, 'typePreuveLib')),
            new \Twig_SimpleFilter('getImageExport', array($this, 'getImageExport')),
            new \Twig_SimpleFilter('graph', array($this, 'graph')),
            new \Twig_SimpleFilter('graph2', array($this, 'graph2')),
            new \Twig_SimpleFilter('iconeBibliotheque', array($this, 'iconeBibliotheque')),
            new \Twig_SimpleFilter('formatNoteSimple', array($this, 'formatNoteSimple')),
            new \Twig_SimpleFilter('formatNoteBar', array($this, 'formatNoteBar')),
            new \Twig_SimpleFilter('formatNoteNuage', array($this, 'formatNoteNuage')),
            new \Twig_SimpleFilter('reponseIcone', array($this, 'reponseIcone')),
            new \Twig_SimpleFilter('reponseGlyphIcone', array($this, 'reponseGlyphIcone')),
            new \Twig_SimpleFilter('role_icon', array($this, 'role_icon')),
            new \Twig_SimpleFilter('role_principal_lib', array($this, 'role_principal_lib')),
            new \Twig_SimpleFilter('get_icon', array($this, 'get_icon')),
            new \Twig_SimpleFilter('get_icon_evol', array($this, 'get_icon_evol')),
            new \Twig_SimpleFilter('get_icon_evol_img', array($this, 'get_icon_evol_img')),
            new \Twig_SimpleFilter('completeCss', array($this, 'completeCss')),
            new \Twig_SimpleFilter('sizeFileUpladBar', array($this, 'sizeFileUpladBar')),
            new \Twig_SimpleFilter('sizeTotalFileUpload', array($this, 'sizeTotalFileUpload')),
            new \Twig_SimpleFilter('canUpload', array($this, 'canUpload')),
            new \Twig_SimpleFilter('sizeTotalFileUploadGestionnaire', array($this, 'sizeTotalFileUploadGestionnaire')),
            new \Twig_SimpleFilter('canUploadGestionnaire', array($this, 'canUploadGestionnaire')),
            new \Twig_SimpleFilter('popover_help', array($this, 'popover_help')),
            new \Twig_SimpleFilter('tile_hover', array($this, 'tile_hover')),
            new \Twig_SimpleFilter('afficheDemandeEtat', array($this, 'afficheDemandeEtat')),
            new \Twig_SimpleFilter('afficheDemandeType', array($this, 'afficheDemandeType')),
            new \Twig_SimpleFilter('delegationCreaiIcone', array($this, 'delegationCreaiIcone')),
            new \Twig_SimpleFilter('NbCriteresreferentiel', array($this, 'NbCriteresreferentiel')),
            new \Twig_SimpleFilter('NotifDemandes', array($this, 'NotifDemandes')),
            new \Twig_SimpleFilter('afficheDemandeEtatAlert', array($this, 'afficheDemandeEtatAlert')),
            new \Twig_SimpleFilter('order_alpha', array($this, 'order_alpha')),
            new \Twig_SimpleFilter('graphMultiDataSet', array($this, 'graphMultiDataSet')),
            new \Twig_SimpleFilter('telFormat', array($this, 'telFormat')),
            new \Twig_SimpleFilter('TdDuration', array($this, 'TdDuration')), 
            new \Twig_SimpleFilter('TdBytes', array($this, 'TdBytes')) ,
            new \Twig_SimpleFilter('TdCurrency', array($this, 'TdCurrency')),
            new \Twig_SimpleFilter('ThCurrency', array($this, 'ThCurrency')),            
            new \Twig_SimpleFilter('currency', array($this, 'currency')),            
            );
    }


    public function TdBytes($bytes ) 
    { 
         return("<td data-order='".$bytes."'>".$this->formatBytes($bytes)."</td>");
    }
    
    
    public function currency($number,$currency="€") 
    { 
         return(number_format($number, 2, ',', ' ')." ".$currency);
    }
    
    
    
    public function TdCurrency($number, $class='' ,$currency="€") 
    { 
         return("<td class='text-right $class' data-order='".$number."'>".number_format($number, 2, ',', ' ')." ".$currency."</td>");
    }
    
    public function ThCurrency($number, $class='' ,$currency="€") 
    { 
         return("<th class='text-right $class' data-order='".$number."'>".number_format($number, 2, ',', ' ')." ".$currency."</th>");
    }
    
    
     
    
    public function TdDuration($duration ) 
    { 
        $duree="";
        
        $seconds = ($duration->s)
         + ($duration->i * 60)
         + ($duration->h * 60 * 60)
         + ($duration->d * 60 * 60 * 24)
         + ($duration->m * 60 * 60 * 24 * 30)
         + ($duration->y * 60 * 60 * 24 * 365);
        
        if ($duration)
        {
            $duree="<td data-order='".$seconds."'>";
            if ($duration->format('%i')>0)
            {
                $duree.=$duration->format('%i minutes  %S secondes');        
            }
            else
            {
                $duree.=$duration->format('%S secondes');        
            }
            $duree.="</td>";
            return($duree);
        }
        return("<td class='alert-danger'></td>");
    }
    
    
    public function formatBytes($size, $precision = 1) { 
        if ($size >= 1073741824) 
            {
                $fileSize = round($size / 1024 / 1024 / 1024,$precision) . 'GB';
            }
            elseif ($size >= 1048576) 
            {
                $fileSize = round($size / 1024 / 1024,$precision) . 'MB';
            } 
            elseif($size >= 1024) {
                $fileSize = round($size / 1024,$precision) . 'KB';
        } 
        else 
        {
            $fileSize = $size . 'B';
        }
        return $fileSize;
    }
    
    
    
    public function  telFormat($tel)
    {
        $tel= str_replace(".", "", $tel);
        $tel= str_replace(" ", "", $tel);
        $tel= str_replace("-", "", $tel);
        
        if (strlen($tel)==10)
        {
            $tel=substr($tel, 0,2)."-".substr($tel, 2,2)."-".substr($tel, 4,2)."-".substr($tel, 6,2)."-".substr($tel, 8,2);
        }
        return($tel);
        
    }

    public function  NotifDemandes($NbDemandes,$nbAtraiter=0,$lib="")
    {
        if ($NbDemandes==0) 
        {
            $title='Toutes les demandes '.$lib.' ont été traitées';
            $class='notif success';
        }
        else
        {
            $title=$NbDemandes." ".$lib.' demandes non finies';
            if ($nbAtraiter==0)
            {
                $class='notif warning';
            }
            else 
            {
                $class='notif';
                $title.=" dont ".$nbAtraiter.' demandes '.$lib.' à traiter';
            }
        }
        return("<span title='".$title."' class='".$class."'>".$NbDemandes."</span>");
    }

    public function  afficheDemandeEtat($demandeEtat)
    {
        if ($demandeEtat->GetId()==3) $css=("done");
        elseif ($demandeEtat->GetId()==1) $css=("toedit");
        elseif ($demandeEtat->GetId()==2) $css=("doing");
        return("<span class='".$css."'>".$demandeEtat."</span>");
    }

    public function  afficheDemandeEtatAlert($demandeEtat)
    {
        if ($demandeEtat->GetId()==3) $css="success";
        elseif ($demandeEtat->GetId()==1) $css="danger";
        elseif ($demandeEtat->GetId()==2) $css="warning";
        return("alert alert-".$css);
    }

    
    
    public function  afficheDemandeType($demandeType)
    {
        if ($demandeType==1) $retour=("Contribuant CREAI");
        elseif ($demandeType==2) $retour=("Ancien utilisateur ARSENE");
        else $retour=("Autre");
        return($retour);
    }
         

    
    public function  popover_help($help)
    {
        return('<a class="help-button"  data-toggle="popover"  data-content="'.$help.'"><i class="glyphicon glyphicon-info-sign"></i></a>');
    }    
            
    
    
    
    
    
    public function sizeTotalFileUpload($etablissement)
    {
        return($this->container->get('Utils')->sizeTotalFileUpload($etablissement));
    }
    
    public function canUpload($etablissement)
    {
        return($this->container->get('Utils')->canUpload($etablissement));
    }
    

    
    public function sizeTotalFileUploadGestionnaire($gestionnaire)
    {
        return($this->container->get('Utils')->sizeTotalFileUploadGestionnaire($gestionnaire));
    }
    
    public function canUploadGestionnaire($gestionnaire)
    {
        return($this->container->get('Utils')->canUploadGestionnaire($gestionnaire));
    }
            
    
    
    
    
    public function get_icon_evol($evol)
    {
        switch ($evol) {
            case "nouveau":
                  return("<i class='glyphicon glyphicon glyphicon-asterisk'></i>");
            case "stable":
                  return("<i class='glyphicon glyphicon-arrow-right'></i>");
            case "baisse":
                  return("<i class='down glyphicon glyphicon-arrow-down'></i>");
            case "hausse":
                  return("<i class='up glyphicon glyphicon-arrow-up'></i>");
        }
    }
    
       
    public function get_icon_evol_img($evol,$small=false)
    {
        switch ($evol) {
            case "stable":
                  $img="stable.png";
                break;
            case "baisse":
                  $img="down.png";
                break;
            case "hausse":
                  $img="up.png";
                break;
          case "nouveau":
                  $img="new.png";
                break;        }
            if ($small)  return("/img/notes/evol_small/".$img);
            else return("/img/notes/evol/".$img);
        }
    
    
    
    public function get_icon($icon)
    {
        switch ($icon) {
            case "etablissement":
                    $icon_class="glyphicon-queen";
                break;
            case "gestionnaire":
                    $icon_class="glyphicon-king";
                break;
            case "administrateur":
                    $icon_class="glyphicon-wrench";
                break;
            case "user":
                    $icon_class="icon-user";
                break;
            case "users":
                    $icon_class="icon-users";
                break;
            case "evaluation":
                    $icon_class="glyphicon-thumbs-up";
                break;
            case "paq":
                    $icon_class="glyphicon-export";
                break;  
            case "biblio":
                    $icon_class="glyphicon-book";
                break;
            case "super_admin":
                    $icon_class="glyphicon-asterisk";
                break;
            case "finess":
                    $icon_class="icon-tags";
                break;
            case "creai":
                    $icon_class="glyphicon glyphicon-map-marker";
                break;
         

            default:
                    $icon_class="glyphicon-unchecked";
        }
        if (substr($icon_class,0,9)=='glyphicon')  $icon_class="glyphicon ".$icon_class;
        return("<i class='".$icon_class."'></i>");
                        
    }
    
    
    public function role_principal_lib($role)
    {
        switch ($role) {
            case "ROLE_USER":
                return("<i title='Utilisateur Etablissement' class='role_resume glyphicon  glyphicon-queen'></i>Utilisateur Etablissement");
                break;
            case "ROLE_GESTIONNAIRE":
                return("<i title='Utilisateur Gestionnaire' class='role_resume glyphicon  glyphicon-king'></i>Utilisateur Gestionnaire");
                break;
            case "ROLE_SUPER_ADMIN":
                return("<i title='Utilisateur Administrateur' class='role_resume glyphicon glyphicon-wrench'></i>Administrateur ARSENE");
                break;
        }
    }
    
    public function role_icon($role)
    {
        switch ($role) {
            case "ROLE_USER":
                return("<i title='Utilisateur Etablissement' class='role_resume glyphicon  glyphicon-queen'></i>");
                break;
            case "ROLE_GESTIONNAIRE":
                return("<i title='Utilisateur Gestionnaire' class='role_resume glyphicon  glyphicon-king'></i>");
                break;
            case "ROLE_ADMIN":
                return("<i title='Utilisateur Administrateur' class='role_resume glyphicon glyphicon-wrench'></i>");
                break;
            case "ROLE_RW_EVAL":
                return("<i title='Peut saisir dans l évaluation' class='role_resume glyphicon glyphicon-thumbs-up'></i>");
                break;
            case "ROLE_RW_PAQ":
                return("<i title='Peut saisir dans le PAQ' class='role_resume glyphicon glyphicon-export'></i>");
                break;  
            case "ROLE_RW_BIBLIO":
                return("<i title='Peut saisir dans la bibliothèque' class='role_resume  glyphicon glyphicon-book'></i>");
                break;
            case "ROLE_RW_BIBLIO_GESTIONNAIRE":
                return("<i title='Peut saisir dans la bibliothèque gestionnaire' class='role_resume  glyphicon glyphicon-book'></i>");
                break;
            case "ROLE_ADMIN_POLE":
                return("<i title='Bloquer  sur des établissements spécifiques (POLE)' class='role_resume redAlertText  glyphicon glyphicon-tent'></i>");
                break;
            case "ROLE_SUPER_ADMIN":
                return("<i title='' class='role_resume  glyphicon glyphicon-cog'></i>");
                break;
            case "ROLE_MEGA_ADMIN":
                return("<i title='Peut tout faire' class='role_resume glyphicon glyphicon-cloud'></i>");
                break;
            case "ROLE_SUPER_ADMIN_GESTIONNAIRE":
                return("<i title='Peut créer des gestionnaires' class='role_resume glyphicon glyphicon-king'></i>");
                break;
            case "ROLE_SUPER_ADMIN_ETABLISSEMENT":
                return("<i title='Peut créer des établissements' class='role_resume glyphicon glyphicon-queen'></i>");
                break;
            case "ROLE_RW_BIBLIO_ARSENE":
                return("<i title='Peut modifier la bibliotheque ARSENE' class='role_resume glyphicon glyphicon-book'></i>");
                break;
            case "ROLE_SUPER_ADMIN_UTILISATEUR":
                return("<i title='Peut créer/modifier des utilisateurs' class='role_resume glyphicon glyphicon-user'></i>");
                break;
            case "ROLE_EDITORIAL_REDACTEUR":
                return("<i title='Peut rédiger des contenus éditoriaux' class='role_resume glyphicon glyphicon-paperclip'></i>");
                break;
            case "ROLE_EDITORIAL_VALIDATEUR":
                return("<i title='Peut rédiger des contenus éditoriaux et les valider' class='role_resume glyphicon glyphicon-paperclip'></i>");
                break;
            case "ROLE_REFERENTIEL_WATCH":
                return("<i title='Peut surveiller les référentiels en développement' class='role_resume glyphicon glyphicon-tree-conifer'></i>");
                break;
            case "ROLE_SUPER_ADMIN_COMPTA_EDIT":
                return("<i title='Peut accéder editer les facures et acceder aux données de comptabilité' class='role_resume glyphicon glyphicon-euro'></i>");
                break;
            case "ROLE_SUPER_ADMIN_COMPTA_VIEW":
                return("<i title='Peut accéder acceder aux données de comptabilité' class='role_resume glyphicon glyphicon-euro'></i>");
                break;            
            case "ROLE_SUPER_ADMIN_TRAITEMENT_DEMANDE":
                return("<i title='Gère la demande de création d établissements et de gestionnaires' class='role_resume glyphicon glyphicon-info-sign'></i>");
                break;            
            case "ROLE_ADMIN_SUPERVISOR":
                return("<i title='Accés en lecture aux stats, au factures...' class='role_resume glyphicon glyphicon-scale'></i>");
                break;            
            
            
            default:
                return("<i class='glyphicon glyphicon-unchecked'></i>+++".$role);
        }
    }
    
    
    public function reponseIcone($reponse,$geturl=false)
    {
        if ($reponse->getNonConcerne())
        {
                $icone="case_ntd.png";
        }
        elseif ($reponse->getRepondu())
        {
           
            if ($reponse->getReponse())
            {
                 $icone="case_ok.png";
            }
            else
            {
                 $icone="case_nok.png";
            }
        }
        else
        {
            $icone="case_todo.png";
            
        }
        $url="/img/icons/".$icone;
        return($url);
    }
    public function reponseGlyphIcone($reponse,$geturl=false)
    {
        if ($reponse) return("<i class='glyphicon glyphicon-ok greenText'></i>");
        else return("<i class='glyphicon glyphicon glyphicon-remove redText'></i>");
    }
    
    public function delegationCreaiIcone($reponse)
    {
        if ($reponse) return("<i title='Le creai peut consulter les saisies de l’établissement' class='help glyphicon glyphicon-ok greenText'></i>");
        else return("<i title='Le creai ne peut pas consulter les saisies de l’établissement' class='help glyphicon glyphicon glyphicon-remove redText'></i>");
    }
    
   
   
    
    public function getImageExport($url,$type,$width=0,$height=0)
    { 
        if ($type=='PDF')
        {
            if (null !== WEB_DIR) $url_image=WEB_DIR.$url;
            else  $url_image="web".$url;
            $imageData = base64_encode(file_get_contents($url_image));
            $src = 'data:'.mime_content_type($url_image).';base64,'.$imageData;
        }
        elseif ($type=='DOC')
        {
            $src="".$url;
        }

        $param="";
        if ($width)
        {
            $param.=" width='$width'";
        }
        if ($height)
        {
            $param.=" height='$height'";
        }        
        return('<img '.$param.' src="'.$src.'">');
        

    }
            
    public function formatNoteSimple($note)
    {
        $note= round($note,1);
        if ($note==-1) return ("NC");
        elseif ($note==10) return($note."/10");
        elseif ($note>0) return("0".$note."/10");
        else return("");
    }
    
    public function formatNoteNuage($note,$small=false)
    {
        if ($note==-1) $file="Non.png";
        elseif ($note>=1 && $note<=3) $file="Orage.png";
        elseif ($note>=4 && $note<=5) $file="Nuage.png";
        elseif ($note>=6 && $note<=8) $file="SoleilNuage.png";
        elseif ($note>=9 && $note<=10) $file="Soleil.png";
        else $file="NonFait.png";
        if ($small)  return("/img/notes/nuages_small/".$file);
        else return("/img/notes/nuages/".$file);
    }
    
    
    
    public function formatNoteBar($note)
    {
        if ($note>0) 
        {
            $success=$note*10;
            $notsuccess=100-$success;
            $retour='
                <div class="progress">
                    <div class="progress-bar progress-bar-success" style="width: '.$success.'%">
                    </div>';
            
            if ($note>=4)
            {
                $retour.='<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: '.$notsuccess.'%">
                    </div>';
            }
            else
            {
                $retour.='<div class="progress-bar progress-bar-danger" style="width: '.$notsuccess.'%">
                    </div>';
            }
            $retour.='</div>';
                return($retour);
        }
        else
        {
            
        return('
            <div class="progress">
                
              </div>');
        }


    }
    
    
    public function sizeFileUpladBar($size,$total)
    {
        $retour ="<span class='hidden_date'>".str_pad($size, 20, '0', STR_PAD_LEFT)."</span>";
        if ($size>0) 
        {
            $success=100/$total*$size;
            $notsuccess=$total-$success;
            $retour.='
                <div class="progress">
                   ';
            if ($size>(0.8*$total))
            {
                $retour.='<div class="progress-bar progress-bar-danger" style="width: '.$success.'%">
                    </div>';
            }
            elseif ($size>(0.6*$total))
            {
                $retour.='<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: '.$success.'%">
                    </div>';
            }
            else   
            {
                 $retour.='<div class="progress-bar progress-bar-success" style="width: '.$success.'%">
                    </div>';
            }
            $retour.='</div>';
        }
        else
        {
            $retour.='<div class="progress"></div>';
        }
        return($retour);

    }
    
    
    public function graphMARCHEPASAVEC20($elements,$div_cible,$byetablissement=false,$options="options")
    { 
        $legends= array();
        $datas= array();
        $nb_items=0;
        $graph_name="Graph_".$div_cible;
        
        $reponse='var ctxDomaine = document.getElementById("'.$div_cible.'").getContext("2d");';
            
        foreach ($elements as $element) 
        {
            if ($byetablissement)$legends[]='"'.$element->getEtablissement().'"';
            else $legends[]=$element->getGraphLegend();
            $datas[]=$element->getGraphData();
            $nb_items++;
        }
        
        $reponse.="
        var ".$graph_name." =  new Chart(ctxDomaine,{ ";
         if ($nb_items > 2)
         {
            $reponse.="type: 'radar',";
         }
         else
         {
            $reponse.="type: 'bar',";
         }
             
             

        $reponse.="data: {
            labels:";
        $reponse.="[".implode(",",$legends)."],";
            
        $reponse.='
            datasets: [
                {
                    backgroundColor: "rgba(151,187,205,0.4)",
                    borderColor: "rgba(151,187,205,1)",
                    borderColor: "rgba(151,187,205,1)",
                    pointBorderColor: "#e00",
                    pointHoverBorderColor: "#0ee",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: ';
                $reponse.="[".implode(",",$datas)."]";
        $reponse.='                
                }
            ]
        }
        ,
        options: { '.$options.'}
        });
        ';
        
        
        //'.$graph_name.'
            
        return($reponse);
    }
    
    public function graph($elements,$div_cible,$byetablissement=false,$options="options")
    { 
        $legends= array();
        $datas= array();
        $nb_items=0;
        $graph_name="Graph_".$div_cible;
        foreach ($elements as $element) 
        {
            if ($byetablissement)$legends[]='"'.addslashes($element->getEtablissement()).'"';
            else $legends[]=addslashes($element->getGraphLegend());
            $datas[]=$element->getGraphData();
            $nb_items++;
        }
        
        $reponse="
        var ".$graph_name." = {
            labels:";
        $reponse.="[".implode(",",$legends)."],";
            
        $reponse.='
            datasets: [
                {
                    fillColor: "rgba(151,187,205,0.4)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#e00",
                    pointHighlightFill: "#0ee",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: ';
                $reponse.="[".implode(",",$datas)."]";
        $reponse.='                
                }
            ]
        };
        ';
        
        $reponse.='var ctxDomaine = document.getElementById("'.$div_cible.'").getContext("2d");';
        
        //'.$graph_name.'
        if ($nb_items > 2)
          $reponse.=' new Chart(ctxDomaine).Radar('.$graph_name.', '.$options.'); ';
        else
            $reponse.=' new Chart(ctxDomaine).Bar('.$graph_name.', '.$options.'); ';
        return($reponse);
    }
    
    public function graphGetColorSetByColor($i,$nb)
    {
     
        
        $step=255/$nb;
        
        $r=0;
        $g=round($step*$i);
        $b=round(255-($step*$i));
        
        
        
            $colorSet='fillColor: "rgba('.$r.','.$g.','.$b.',0.1)",
                            strokeColor: "rgba('.$r.','.$g.','.$b.',1)",
                            pointColor: "rgba('.$r.','.$g.','.$b.',1)",
                            pointStrokeColor: "#e00",
                            pointHighlightFill: "#0ee",
                            pointHighlightStroke: "rgba('.$r.','.$g.','.$b.',1)",
                            strokeColor: "rgba('.$r.','.$g.','.$b.',1)",
                            pointColor: "rgba('.$r.','.$g.','.$b.',1)",
                            pointStrokeColor: "#0e0",
                            pointHighlightFill: "#e00",
                            pointHighlightStroke: "rgba('.$r.','.$g.','.$b.',0.8)",';
        
            return($colorSet);
        
        
    }
    
    
    
    
    public function graphMultiDataSet($elements,$div_cible,$options="options_responsive")
    { 
        $legends= array();
        $datas= array();
        $nb_items=0;
        $graph_name="Graph_".$div_cible;
        
        
        $legends=$elements[0]->getGraphSubLegend();
        $nb_items=$elements[0]->getGraphSubNb();
        
        $reponse="
        var ".$graph_name." = {
            labels:";
        $reponse.="[".$legends."],";
            
        $reponse.='
            datasets: [';
        
            $i=0;
        foreach ($elements as $element) 
        {
            $dataset='
                        {
                            label:"'.addslashes($element->GetEtablissement()).'",';
            $dataset.=$this->graphGetColorSetByColor($i,count($elements));
            $dataset.='
                            data: ';
                        $dataset.="[".$element->getGraphSubData()."]";
                $dataset.='                
                        }
                    '; 
            $datasets[]=$dataset;
            $i++;
        }
        
        
            
        $reponse.=implode(",",$datasets);              
            
        $reponse.=']               
        };
        ';
        
        $reponse.='var ctxDomaine = document.getElementById("'.$div_cible.'").getContext("2d");';
        
        //'.$graph_name.'
        if ($nb_items > 2)
          $reponse.=' new Chart(ctxDomaine).Radar('.$graph_name.', '.$options.'); ';
        else
            $reponse.=' new Chart(ctxDomaine).Bar('.$graph_name.', '.$options.'); ';
        return($reponse);
    }
    
    
    
    public function graph2($elements,$elements_sauvegarde,$legende,$div_cible)
    { 
        $legends= array();
        $datas= array();
        $nb_items=0;
        $graph_name="Graph_".$div_cible;
        foreach ($elements as $element) 
        {
            $legends[]=$element->getGraphLegend();
            $datas[]=$element->getGraphData();
            $nb_items++;
        }
        foreach ($elements_sauvegarde as $element) 
        {
            $datas2[]=$element->getGraphData();
        }
        
        $reponse="
        var ".$graph_name." = {
            labels:";
        $reponse.="[".implode(",",$legends)."],";
            
        $reponse.='
            datasets: [
                {
                    label: "'.$legende.'",
                    fillColor: "rgba(151,22,22,0.4)",
                    strokeColor: "rgba(151,22,22,1)",
                    pointColor: "rgba(151,22,22,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,22,22,1)",
                    data: ';
                $reponse.="[".implode(",",$datas2)."]";
        $reponse.='                
                },
                {
                    label: "En cours",
                    fillColor: "rgba(151,187,205,0.4)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: ';
                $reponse.="[".implode(",",$datas)."]";
        $reponse.='                
                }
        
            ]
        };
        ';
        
        $reponse.='var ctxDomaine = document.getElementById("'.$div_cible.'").getContext("2d");';
        
        //'.$graph_name.'
        if ($nb_items > 2)
          $reponse.=' new Chart(ctxDomaine).Radar('.$graph_name.', options); ';
        else
            $reponse.=' new Chart(ctxDomaine).Bar('.$graph_name.', options); ';
        return($reponse);
    }
    
    
    
    
    
    
    
    public function iconeBibliotheque($type) 
    { 
        switch ($type) {
            case "lien":
                return("<i class='glyphicon glyphicon-globe'></i>");
                break;
            case "fichier":
                return("<i class='glyphicon glyphicon-save-file'></i>");
                break;
            case "texte":
                return("<i class='glyphicon glyphicon-font'></i>");
                break;
            default:
                return("<i class='glyphicon glyphicon-unchecked'></i>");
        }
    }
            
    
    
    public function typePreuveLib($typePreuve) { 
        if ($typePreuve =="critere") {return("Evaluation");}
        elseif ($typePreuve =="objectif_operationnel") {return("PAQ");}
        elseif ($typePreuve =="pdv") {return("Point de vue de l'usager");}
        else {return("????");}
    }
   
    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }
    
    public function critereStateCss($critere_curent_state)
    {
        if ($critere_curent_state==3) return("done");
        elseif ($critere_curent_state==4)return("toedit");
        elseif ($critere_curent_state==5)return("modifie");
        elseif ($critere_curent_state==6)return("nouveau");
        elseif ($critere_curent_state==2)return("doing");
        else return("todo");
    }
    
    public function critereStateLib($critere_curent_state,$bracket=true)
    {
        if ($critere_curent_state==3) $lib="Terminé";
        elseif ($critere_curent_state==5) $lib="Modifié";
        elseif ($critere_curent_state==6) $lib="Nouveau";
        elseif ($critere_curent_state==4) $lib="A revoir";
        elseif ($critere_curent_state==0) $lib="Non concerné";
        elseif ($critere_curent_state==2) $lib="En cours";
        else $lib="Non démarré";
        if ($bracket) return("[".$lib)."]";
        else return($lib);
    }
    
    public function StatePuce($status)
    {
        $reponse=" <i title='".$this->critereStateLib($status)."' class='icon-circle ".$this->critereStateCss($status)."'></i>";
        return($reponse);
        
    }    
    
    
    
    
    
    
    
    
    
             
       
    public function PAQstatutFilter($statut, $icon = true)
    {
        $icon_lbl="";
        if ($statut == 2)
        {
            $rank=0;
            $statut_rep="Important";
            $icon_lbl="glyphicon-warning-sign orangeText";
        }
        elseif ($statut == 3)
        {
            $rank=2;
            $statut_rep="Terminé";
            $icon_lbl="glyphicon-ok greenText";
        }
        else
        {
            $rank=1;
            $statut_rep="En&nbsp;cours";
            $icon_lbl="glyphicon-time blueText";
        }
        
        if ($icon)
        {
            $statut_rep="<span class=\"glyphicon ".$icon_lbl." \"  aria-hidden=\"true\">$statut_rep</span>";
        }
        return ("<span class='hidden'>".$rank."</span>".$statut_rep);
        
    }
    
    
    public function tile_hover($image,$titre,$description)
    {
        
        $statut_rep= '
            <div class="photo-contrast figure">
           &nbsp;
               <img class="tilter__image" src="'.$image.'" >
                    <div class="tilter_caption" >
                        <h3 class="tilter__title">'.$titre.'</h3>
                        <p class="tilter__description">'.$description.'</p>
                    </div>
                </div>
                            ';

  //      $statut_rep.= '<div class="photo-contrast">&nbsp;</div>';
                        
        return ($statut_rep);
        
    }
    
     
      
    public function completeCss($progress)
    {
        if ($progress==100) $progress_class="done";
        elseif ($progress==0) $progress_class="todo";
        else $progress_class="doing";
        return (' <span class="'.$progress_class.'">'.$progress.'%</span>');
    }
     
    public function order($tableau)
    {
        $tableau_rep =array();
        $tmp_tab =array();
        foreach($tableau as $tab) { $tmp_tab[$tab->GetOrdre()]=$tab; }
        for ($i=1;$i<=count($tmp_tab);$i++) {$tableau_rep[$i]=$tmp_tab[$i];}
        return ($tableau_rep);
    }
            
    
            
    public function order_alpha($collection)
    {
        $array = $collection->getValues();
        usort($array, function($a, $b){
            return (trim(strtolower($a->__toString())) < trim(strtolower($b->__toString()))) ? -1 : 1 ;
        });
        $collection->clear();
        foreach ($array as $item) {
            $collection->add($item);
        }
        return($collection);
    }
            
    
     private static function isSortable($item, $field) {
        if (is_array($item))
            return array_key_exists($field, $item);
        elseif (is_object($item))
            return isset($item->$field) || property_exists($item, $field);
        else
            return false;
    }
    
    
     
/*
    
    public function getDimensions()
    {
        return 'app_extension';
    }
    */
 
    
    
}
