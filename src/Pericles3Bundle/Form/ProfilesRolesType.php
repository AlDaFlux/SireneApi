<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ProfilesRolesType extends AbstractType
{
    
       private $tpe_user;
       
       
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          if (isset($options['tpe_user'])) $this->tpe_user = $options['tpe_user'];
          
          
        $builder->add('NameProfil', TextType::class, ['required' => true]);
        $builder->add('Description');
       

        
            if ($options["tpe_user"]=="GESTIONNAIRE")
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
            elseif ($options["tpe_user"]=="ETABLISSEMENT" or true)
            {
                $builder->add('roles', ChoiceType::class, array(
                        'multiple' => true,
                        'expanded' => true,
                        'choices' => array(
                            'Administrateur (peut créer des utilisateurs dans son établissement)' => 'ROLE_ADMIN',
                            "Peut saisir dans l'évaluation" => 'ROLE_RW_EVAL',
                            'Peut saisir dans la bibliotheque' => 'ROLE_RW_BIBLIO',
                            'Peut saisir dans le PAQ :: '.$this->tpe_user => 'ROLE_RW_PAQ'
                        ),
                    ));
            } 
            
            /*
            $builder->add('TypeUser', ChoiceType::class, array(
                'multiple' => false,
                'expanded' => true,
                'choices' => array(
                    'Etablissement' => 'ETABLISSEMENT',
                    'Gestionnaire' => 'GESTIONNAIRE'
                ),
            ));
        */
           $builder->add('Icon')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\ProfilesRoles',
            'tpe_user' => "AUCUN",
        ));
    }
}
