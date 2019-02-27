<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;



class PericlesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('gestionnaireNom')->add('nom')->add('typeStructure')->add('adresse')->add('tel')->add('email')->add('finessText');
        
        $builder->add('etablissement', AutocompleteType::class, ['required'    => false,'class' => 'Pericles3Bundle:Etablissement']);
        $builder->add('gestionnaire', AutocompleteType::class, ['required'    => false,'class' => 'Pericles3Bundle:Gestionnaire']);

        
        //$builder->add('finessEtablissement')->add('finessGestionnaire');
    }
    
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\Pericles'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pericles3bundle_pericles';
    }


}
