<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinessType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeFiness')
            ->add('raisonSociale')
            ->add('complementAdresse')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('departement')
            ->add('tel')
            ->add('fax')
            ->add('codeCategorie')
            ->add('capacite_totale1')   ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\Finess'
        ));
    }
}
