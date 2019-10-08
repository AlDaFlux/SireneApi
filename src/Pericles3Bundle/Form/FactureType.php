<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;

use Symfony\Component\Form\Extension\Core\Type\DateType;


class FactureType extends AbstractType
{
    
       private $contact_facturation;

        
        
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//        $builder->add('numFacture');
    
        
        if ( $options['contact_facturation'])
        {
            $builder->add('contactFacturationNom');
            $builder->add('contactFacturationEmail');
            $builder->add('contactFacturationTelephone');
        }
        else
        {
        $builder->add('dateEmission', DateType::class, array(
                                                'label' => "Date d'émission" ,
                                                'widget' => 'single_text',
                                                'input' => 'datetime',
                                                'format' => 'dd-MM-yyyy',
                                                'attr' => array('class' => 'date')
                                                ));
            $builder->add('concerneGestionnaire', ChoiceType::class, array(
                        'multiple' => false,
                        'expanded' => true,
                        'choices' => array(
                           /* 'MEGA' => 'ROLE_MEGA_ADMIN',*/
                            'Gestionnaire' => '1',
                            "Etablissement" => '0'
                        ),
                    ));

        $builder->add('etablissement', AutocompleteType::class, ['required'    => false,'class' => 'Pericles3Bundle:Etablissement']);
        $builder->add('gestionnaire', AutocompleteType::class, ['required'    => false,'class' => 'Pericles3Bundle:Gestionnaire']);
        
        $builder->add('remise')->add('remise_libelle');
        $builder->add('nonRenouvelable');
        //$builder->add('montant')->add('libelle')->add('neufcent')->add('cinccentcinquante')->add('ren300')->add('ren150');
//
        $builder->add('payele', DateType::class, array(
                                                'label' => "Payé le" ,
                                                'widget' => 'single_text',
                                                'input' => 'datetime',
                                                'format' => 'dd-MM-yyyy',
                                                'required'    => false,
                                                'attr' => array('class' => 'date')
                                                ));
        $builder->add('MoyenPaiement');
        $builder->add('commentaire');
        $builder->add('contactFacturationNom');
        $builder->add('contactFacturationEmail');
        $builder->add('contactFacturationTelephone');
        }
        
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\Facture',
            'contact_facturation' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pericles3bundle_facture';
    }


}
