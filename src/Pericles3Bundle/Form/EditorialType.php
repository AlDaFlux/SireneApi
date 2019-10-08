<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Pericles3Bundle\Repository\EditorialPublicationRepository; 



class EditorialType extends AbstractType
{
    
    private $validator;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['validator'])) $this->validator = $options['validator'];
        
        if ($options['simple'])
        {
            $builder->add('titre');
            $builder->add('commentaire');
        }
        else
        {
            $builder->add('titre');
            $builder->add('commentaire', CKEditorType::class, array(
                'config' => array(
                'uiColor' => '#ffffff')));
            
            
             $builder->add('datePublication', DateType::class, array(
                                                'label' => 'Date de publication',
                                                'widget' => 'single_text',
                                                'input' => 'datetime',
                                                'format' => 'dd-MM-yyyy',
                                                'attr' => array('class' => 'date')
                                                ));
            
            $builder->add('referentielPublics', EntityType::class, array(
                    'attr' => array('class' => 'inline alert alert-warning'),
                    'class' => 'Pericles3Bundle:ReferentielPublic',
                    'multiple' => true,
                    'expanded' => true
                    ));
            
                $builder->add('etablissementGestionnaire', ChoiceType::class, array(
                            'label' => "Etat", 
                            'multiple' => false,
                            'expanded' => true,
                            'choices' => array(
                               /* 'MEGA' => 'ROLE_MEGA_ADMIN',*/
                                'tous' => 0,
                                'ARSENE (Etab et Gest)' => 1,
                                'Etablissements' => 2,
                                "Gestionnaire" => 3,
                                "CT" => 4,
                            ),
                        ));

                
            $builder->add('etatPublication', EntityType::class, array(
                    'attr' => array('class' => 'inline alert alert-warning'),
                    'class' => 'Pericles3Bundle:EditorialPublication',
                    'multiple' => false,
                    'expanded' => true,
                    'query_builder' => function(EditorialPublicationRepository $repository) 
                    {
                        $qb = $repository->createQueryBuilder('publi');
                        if ($this->validator) return $qb;
                        else return $qb->where('publi.id <5');
                    }
                    ));
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\Editorial',
            'simple'=> false, 
            'validator'=> false, 
            
        ));
    }
}

