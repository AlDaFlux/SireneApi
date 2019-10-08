<?php

use Symfony\Component\DependencyInjection\ContainerInterface;


namespace Pericles3Bundle\Utils;

class Utils 
{
      
    protected $em;
    protected $container;
    protected $token_storage;

    public function __construct($em,$container,\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $token_storage)
    {
        $this->em = $em;
        $this->container = $container;
        $this->token_storage = $token_storage;  
    }
    
    
//FodlerExisteCreate
    public function FolderUploadExisteCreate($folder_cible)
    {
        
        $uploadPath = WEB_DIR.'/upload';
        
        
        if (! file_exists($uploadPath."/".$folder_cible))
        {
            $retour='';
            $folders=explode("/",$folder_cible);
            $foldertotest=$uploadPath;
            foreach ($folders as $folder)
            {
                $foldertotest.="/".$folder;
                if (! file_exists($foldertotest)) 
                {
                    mkdir($foldertotest, 0777, true);
                }
            }
        }
        return (true);
    }
    
    
    
    public function canUpload(\Pericles3Bundle\Entity\Etablissement $etablissement) 
    {
       if ( $etablissement->sizeMaxUpload() <  $this->sizeTotalFileUpload($etablissement)) return(false);
       return(true);
    }
    
    public function canUploadGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire) 
    {
       if ( $gestionnaire->sizeMaxUpload() < $this->sizeTotalFileUploadGestionnaire($gestionnaire))  return(false);
        return(true);
    }
    
    public function sizeTotalFileUploadFolder($folder_base) 
    {
        $dossiers_name[]="preuves";
        $dossiers_name[]="bibliotheque";
        $total_size=0;
        foreach ($dossiers_name as $dossier_name)
        {
            $folder=$folder_base.$dossier_name;
            
            
            if (is_dir($folder)) 
            {
                  $dir = opendir($folder);
        
                while ($file = readdir($dir)) 
                {
                    if ($file != "." && $file != "..") {
                        $total_size+= filesize($folder ."/". $file);
                    }
                }
            }
        }
        return($total_size);
    }
    
    
    
    public function sizeTotalFileUpload(\Pericles3Bundle\Entity\Etablissement $etablissement) 
    {
          return($this->sizeTotalFileUploadFolder(WEB_DIR."/upload/".$etablissement->GetUploadFolderPath()."/")); 
    }
    
    public function sizeTotalFileUploadGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire) 
    {
        return($this->sizeTotalFileUploadFolder(WEB_DIR."/upload/".$gestionnaire->GetUploadFolderPath()."/"));
    }
    
    
    
    
    public function returnOK($test=null) 
    {
        return("OK");
    }
    
    public function formatBytes($size) { 
        if ($size >= 1073741824) {
          $fileSize = round($size / 1024 / 1024 / 1024,1) . 'GB';
            } elseif ($size >= 1048576) {
            $fileSize = round($size / 1024 / 1024,1) . 'MB';
            } elseif($size >= 1024) {
                $fileSize = round($size / 1024,1) . 'KB';
        } else 
        {
            $fileSize = $size . ' octets';
        }
        return $fileSize;
    }
    
    public function CheckFile($fichier) 
    {
        if (! $fichier) { return(['statut' => false, 'message' => "-- Vous devez selectionner un ficher"]); }
        if ($fichier->GetSize() > $this->container->getParameter('upload.max_file_size')) { return(['statut' => false, 'message' => "Le fichier est tros gros ! "]); }
        if (! in_array(strtolower($fichier->getMimeType()),$this->container->getParameter('upload.filetype'))) 
        {
            return(['statut' => false, 'message' => "Format de fichier ".$fichier->getMimeType()." non supporté <br><b>Rappel : </b> les fichiers supportés sont : ".implode(' - ',$this->container->getParameter('upload.extensions'))]);
        }
        return(['statut' => true, 'message' => "Fichier OK:"]);

    }
    
    public function ChaineAleatoire($nb_car=12, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789')
    {
        //* obolete
        $nb_lettres = strlen($chaine) - 1;
        $generation = '';
        for($i=0; $i < $nb_car; $i++)
        {
            $pos = mt_rand(0, $nb_lettres);
            $car = $chaine[$pos];
            $generation .= $car;
        }
        return $generation;
    }

    
    
    
    public function SizeFiles($files)
    {
        $size=0;
        if ($files)
        {
            foreach ($files as $file)
            {
               $size+=$file['size'];
            }
        }
        return($size);
    }
    
    
    
    public function scanDirectory($folder,$dossier_name, $etablissement=null, $gestionnaire=null)
    {
        if (is_dir($folder))
        {
            $dir = opendir($folder);

            while ($file = readdir($dir)) {
                if ($file != "." && $file != "..") {
                        $tmpfile=array();
                        
                            $tmpfile['size']=filesize($folder ."/". $file);
                            if ($dossier_name=='preuves')
                            {
                                $tmpfile['nb_used']=$this->NbByPreuveFile($etablissement,$file,$dossier_name);
                            }
                            else
                            {
                                if ($etablissement) $tmpfile['id']=$this->IdBiblioByFileEtablissement($etablissement,$file);
                                else $tmpfile['id']=$this->IdBiblioByFileGestionnaire($gestionnaire,$file);
                            }
                            $debugthis[$file]= $tmpfile;

                }
            }
            return($debugthis);
        }
    }
    
    
    
    public function NbByPreuveFile(\Pericles3Bundle\Entity\Etablissement $etablissement,$filename)
    {
        return($this->em->getRepository("Pericles3Bundle:Preuve")->FindNbFileNamebyEtablissement($etablissement,$filename));
      
    }
    
    
    public function IdBiblioByFileEtablissement(\Pericles3Bundle\Entity\Etablissement $etablissement,$filename)
    {
        return($this->em->getRepository("Pericles3Bundle:Bibliotheque")->FindIdBiblioByFile($etablissement,$filename));
    }
    
    public function IdBiblioByFileGestionnaire(\Pericles3Bundle\Entity\Gestionnaire $gestionnaire,$filename)
    {
        return($this->em->getRepository("Pericles3Bundle:Bibliotheque")->FindIdBiblioGestionnaireByFile($gestionnaire,$filename));
    }

        
    
    
}


 