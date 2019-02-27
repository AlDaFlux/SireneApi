<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Pericles3Bundle\Repository\EtablissementRepository; 



class UserType extends AbstractType
{
 
     
    private $id_gestionnaire;
    
    
    public function __construct($options = null) {
        $this->options = $options;
    }
    
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        if (isset($options['id_gestionnaire'])) $this->id_gestionnaire = $options['id_gestionnaire'];

        if ($options["id_gestionnaire"])
        {
                $builder->add('EtablissementsPole', EntityType::class, array(
                'class' => 'Pericles3Bundle:Etablissement',
                'multiple' => true,
                'expanded' => true,
                    'query_builder' => function(EtablissementRepository $repository) 
                    {  
                        $qb = $repository->createQueryBuilder('etablissements');
                        return $qb->where('etablissements.gestionnaire='.$this->id_gestionnaire);
                    }  
                ));
        }
        else
        {
            
        
        
        
        $builder->add('username', TextType::class, ['required' => true,'label' => "Nom d'utilsateur"]);
//        if ($options["edit_password"])   $builder->add('password', TextType::class, ['required' => false,'label' => "Mot de passe (laissé vide pour ne pas le changer)"]);
        
        if ($options["edit_password"] && false) $builder->add('password', TextType::class, ['required' => true,'label' => "Mot de passe (vous pouvez le changer)"]);
        $builder->add('email', TextType::class, ['required' => false,'label' => "Adresse mail"]);

        if (! $options["new"]) 
        {
            $builder->add('desactive', ChoiceType::class, array(
                            'label' => "Etat", 
                            'multiple' => false,
                            'expanded' => true,
                            'choices' => array(
                               /* 'MEGA' => 'ROLE_MEGA_ADMIN',*/
                                'Utilisateur Actif' => 0,
                                'Utilisateur désactivé' => 1,
                                "Suppression de l'utilisateur" => -1,
                            ),
                        ));
        }
        if ($options["show_roles"])
        {
            if ($options["niveau"]=="ROLE_SUPER_ADMIN")
            {
                  $builder->add('creai', EntityType::class, array(
                'class' => 'Pericles3Bundle:Creai',
                'required' => false,
                'multiple' => false,
                'expanded' => false
                ));
                  
                  $role_prop=['Superviseur' => 'ROLE_ADMIN_SUPERVISOR',
                            'Redaction editorial' => 'ROLE_EDITORIAL_REDACTEUR',
                            'Redaction Validation' => 'ROLE_EDITORIAL_VALIDATEUR',
                            'Surveillance Référentiels' => 'ROLE_REFERENTIEL_WATCH',
                            "Traitement des demandes (création d'établissements)" => 'ROLE_SUPER_ADMIN_TRAITEMENT_DEMANDE',
                            "Compte (suivi)" => 'ROLE_SUPER_ADMIN_COMPTA_VIEW',
                            "Compte (saisie)" => 'ROLE_SUPER_ADMIN_COMPTA_EDIT',
                            'Administrateur Gestionnaire' => 'ROLE_SUPER_ADMIN_GESTIONNAIRE',
                            "Administrateur Etablissement" => 'ROLE_SUPER_ADMIN_ETABLISSEMENT',
                            'Administrateur Utilisateur' => 'ROLE_SUPER_ADMIN_UTILISATEUR',
                            'Administrateur Biblioteque ARSENE' => 'ROLE_RW_BIBLIO_ARSENE'
                      ];
                  
                    if ($options["iammegaadmin"])
                    {
                             $role_prop['MEGA  ADMIN']='ROLE_MEGA_ADMIN';
                    }
                  
                $builder->add('roles', ChoiceType::class, array(
                        'multiple' => true,
                        'expanded' => true,
                        'choices' => array($role_prop
                           /* 'MEGA' => 'ROLE_MEGA_ADMIN',*/
                            
                        ),
                    ));
                
                if ($options["iammegaadmin"])
                {
                        $builder->add('ReferentielsPublic', EntityType::class, array(
                           'attr' => array('class' => 'inline'),
                           'class' => 'Pericles3Bundle:ReferentielPublic',
                           'multiple' => true,
                           'expanded' => true
                           ));
                }

                
            }
            elseif ($options["niveau"]=="ROLE_GESTIONNAIRE")
            {
                $builder->add('roles', ChoiceType::class, array(
                        'multiple' => true,
                        'expanded' => true,
                'choices' => array(
                            'Administrateur (peut créer des utilisateurs gestionnaires et établissements)' => 'ROLE_ADMIN',
                            'Peut saisir dans la bibliotheque gestionnaire' => 'ROLE_RW_BIBLIO_GESTIONNAIRE',
                            "Bloquer  sur des établissements spécifiques (POLE)" => 'ROLE_ADMIN_POLE',
                            "Peut saisir dans l'évaluation des etablissement" => 'ROLE_RW_EVAL',
                            'Peut saisir dans la bibliotheque des etablissement' => 'ROLE_RW_BIBLIO',
                            'Peut saisir dans le PAQ des etablissement' => 'ROLE_RW_PAQ'
                    )));
            }
            elseif ($options["niveau"]=="ROLE_USER")
            {
                $builder->add('roles', ChoiceType::class, array(
                        'multiple' => true,
                        'expanded' => true,
                        'choices' => array(
                            'Administrateur (peut créer des utilisateurs dans son établissement)' => 'ROLE_ADMIN',
                            "Peut saisir dans l'évaluation" => 'ROLE_RW_EVAL',
                            'Peut saisir dans la bibliotheque' => 'ROLE_RW_BIBLIO',
                            'Peut saisir dans le PAQ' => 'ROLE_RW_PAQ'
                        ),
                    ));
                } 
            }
        }
        
    }
    
    
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\User',
            'edit_password' => false,
            'niveau' => 'a',
            'iammegaadmin' => false,
            'new' => false,
            'show_roles' => true,
            'id_gestionnaire' => 0,
            'allow_extra_fields' => true
        ));
    }
}


