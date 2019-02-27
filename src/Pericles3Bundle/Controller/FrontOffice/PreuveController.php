<?php

namespace Pericles3Bundle\Controller\FrontOffice;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use \stdClass;


use Pericles3Bundle\Entity\Preuve;
use Pericles3Bundle\Entity\Bibliotheque;
use Pericles3Bundle\Entity\ObjectifOperationnel;
use Pericles3Bundle\Entity\Critere;
use Pericles3Bundle\Entity\Domaine;



/**
 * DomaineObjectifStrategique controller.
 *
 * @Route("/preuves")
 */
class PreuveController extends Controller
{
    
    /**
     * Liste les preuves
     *
     * @Route("/minlist/domaine_{id}", name="pericles3_preuves_domaine_minlist")
     * @Method({"GET", "POST"})
     */
    public function minlistDomaineAction(Domaine $Domaine)
    {
        $em = $this->getDoctrine()->getManager();
        $preuves =  $em->getRepository('Pericles3Bundle:Preuve')->findBy(['domaine' => $Domaine] );
        return ($this->render('Preuve/list_min_preuve.html.twig', ['preuves' => $preuves]));
    }
    
        
    /**
     * Liste les preuves
     *
     * @Route("/minlist/ooa_{id}", name="pericles3_preuves_ooa_minlist")
     * @Method({"GET", "POST"})
     */
    public function minlistOOAction(ObjectifOperationnel $ObjectifOperationnel)
    {
        $em = $this->getDoctrine()->getManager();
        $preuves =  $em->getRepository('Pericles3Bundle:Preuve')->findBy(['objectifOperationnel' => $ObjectifOperationnel] );
        return ($this->render('Preuve/list_min_preuve.html.twig', ['preuves' => $preuves]));
    }
    
    
    /**
     * Liste les preuves
     *
     * @Route("/minlist/critere_{id}", name="pericles3_preuves_critere_minlist")
     * @Method({"GET", "POST"})
     */
    public function minlistCritereAction(Critere $Critere)
    {
        $em = $this->getDoctrine()->getManager();
        $preuves =  $em->getRepository('Pericles3Bundle:Preuve')->findBy(['critere' => $Critere] );
        return ($this->render('Preuve/list_min_preuve.html.twig', ['preuves' => $preuves]));
    }
    
    
    /**
     * Rajoute une preuve
     *
     * @Route("/upload", name="pericles3_preuve_upload")
     * @Method({"GET", "POST"})
     */
    public function uploadPreuveAction(Request $request){
        
        
        $uploadPath = WEB_DIR.'/upload';
        $em = $this->getDoctrine()->getManager();

        $preuve = new Preuve();
            
        if ($request->get('domaine_id'))
        {
            $type="pdv";
            $domaine = $em->getRepository('Pericles3Bundle:Domaine')->find($request->get('domaine_id'));
            $redirect_to="pericles3_preuves_domaine_minlist";
            $id_to_redirect=$request->get('domaine_id');
            $etablissement=$domaine->GetEtablissement();
            
        }
        elseif ($request->get('hdnCritereIdUpload'))
        {
            $type="critere";
            $critere = $em->getRepository('Pericles3Bundle:Critere')->find($request->get('hdnCritereIdUpload'));
            $redirect_to="pericles3_preuves_critere_minlist";
            $id_to_redirect=$request->get('hdnCritereIdUpload');
            $etablissement=$critere->GetEtablissement();
        }
        else //PAQ - Objecti Opérationnel
        {
            $type="objectif_operationnel";
            $ObjectifOperationnel = $em->getRepository('Pericles3Bundle:ObjectifOperationnel')->find($request->get('objectif_operationnel_id'));
            $redirect_to="pericles3_preuves_ooa_minlist";
            $id_to_redirect=$request->get('objectif_operationnel_id');
            $etablissement=$ObjectifOperationnel->GetEtablissement();
        }
        $UploadFolderPath= $etablissement->GetUploadFolderPath();
        $UploadDirectory = $UploadFolderPath."/preuves";
            
            
        $File_Name=""; 
        
        
        switch ($request->get('type_fichier_preuve'))
        {
            case 1: 
                if (! $request->get('existing_file_preuve'))
                {
                    $this->addFlash('error', "Vous devez selectionner un fichier ");
                    goto preuve_list_end_redirect;
                }
                $File_Name=$request->get('existing_file_preuve'); 
                break;
            case 2: 
                if (!isset($_FILES["FileInput"]))
                {
                    $this->addFlash('error', "Vous devez selectionner un fichier ");
                    goto preuve_list_end_redirect;
                }
                
                if($_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
                {
                        if (! $this->get('Utils')->FolderUploadExisteCreate($UploadDirectory))
                        {
                            $this->addFlash('error', "Une erreur est survenue lors de la création du dossier");
                            goto preuve_list_end_redirect;
                        }
                        //check if this is an ajax request
                        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
                            die();
                        }
                        if ($_FILES["FileInput"]["size"] > $this->container->getParameter('upload.max_file_size')) { die(self::getBootstrapAlert('Erreur!',"Le Fichier est trop gros !!", 'alert-danger'));}


                        if (! in_array(strtolower($_FILES['FileInput']['type']),$this->container->getParameter('upload.filetype'))) 
                        {
                            $this->addFlash('error', "Format de fichier <b>" . $_FILES['FileInput']['type']. "</b> non supporté <br><b>Rappel : </b> les fichiers supportés sont : ".implode(' - ',$this->container->getParameter('upload.extensions')));
                      //      $this->addFlash('error', "Format de fichier <b>" . $_FILES['FileInput']['type']. "</b> non supporté <br><b>Rappel : </b> les fichiers supportés sont : ".implode(' - ',$this->container->getParameter('upload.filetype')));

                            goto preuve_list_end_redirect;
                        }

                        $File_Name= strtolower($_FILES['FileInput']['name']);

                        $dest_file=$uploadPath."/".$UploadDirectory."/".$File_Name;
                        if (file_exists($dest_file))
                        {
                            $this->addFlash('success', "Le fichier existait déjà");
                        }
                        else
                        {
                            if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $dest_file))
                            {

                                $this->addFlash('success', "Le fichier a été envoyé!");
                                $etablissement->SetSizeTotalFileUploadCache($this->container->get('Utils')->sizeTotalFileUpload($etablissement));
                                $em->persist($etablissement);
                                $em->flush();
                            }
                            else
                            {
                            $this->addFlash('error', "Erreur lors de l'envoi du fichier!!");
                            }
                        }
                    }       
                break;
            case 3:
                if (! $request->get('bibliotheque_etablissement'))
                {
                    $this->addFlash('error', "Vous devez selectionner une bibliotheque etablissement");
                    goto preuve_list_end_redirect;
                }

                $id_biblio=$request->get('bibliotheque_etablissement');
                if ($id_biblio)
                {
                $biblio=$em->getRepository('Pericles3Bundle:Bibliotheque')->findOneBy(['id' => $id_biblio]);
                    $preuve->setBibliotheque($biblio);
                }
                break;
            case 4: 
                if (! $request->get('bibliotheque_gestionnaire'))
                {
                    $this->addFlash('error', "Vous devez selectionner une bibliotheque etablissement");
                    goto preuve_list_end_redirect;
                }
                $id_biblio=$request->get('bibliotheque_gestionnaire');
                if ($id_biblio)
                {
                    $biblio=$em->getRepository('Pericles3Bundle:Bibliotheque')->findOneBy(['id' => $id_biblio]);
                    $preuve->setBibliotheque($biblio);
                }
                break;
        }

        
        $preuve->setCommentaire($request->get('inputCommentairePreuve'));
        $preuve->setDateCreate(new \DateTime());
        $preuve->setFichier($File_Name);
        $preuve->setUser($this->getUser());
        
        $preuve->setEtablissement($etablissement);

        $preuve->setTypePreuve($type);
        if ($type=="critere")
        {
            $critere->addPreuve($preuve);
        }
        elseif ($type=="pdv")
        {
            $domaine->addPreuve($preuve);
        }
        elseif ($type=="objectif_operationnel")
        {
            $ObjectifOperationnel->addPreuve($preuve);
        }
        else
        {
            $this->addFlash('error', "Type de preuve inconnu !!");
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($preuve);
        $em->flush();
        preuve_list_end_redirect:
        return $this->redirectToRoute($redirect_to,['id' =>$id_to_redirect]);
    }



    /**
     * Supprime une preuve
     *
     * @Route("/delete_{id}", name="pericles3_preuve_delete_id")
     * @Method({"GET", "POST"})
     */
    public function deletePreuveIdAction(Preuve $preuve)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($preuve);
            $em->flush();
            $this->addFlash('success', "La preuve a bien été supprimée");
            return new JsonResponse(true);
    }



    private function getBootstrapAlert($titre, $corps, $class){
        return "<div class='alert ".$class." alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Fermer'><span aria-hidden='true'>&times;</span></button>
                <strong>".$titre."</strong> ".$corps."</div>";
    }
    
        

}
    
    
    