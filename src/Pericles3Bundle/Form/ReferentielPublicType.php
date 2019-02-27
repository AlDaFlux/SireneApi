<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ReferentielPublicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['edit'])
        {
            $builder
                ->add('public')
                ->add('short')
                ->add('fini', ChoiceType::class, array('choices' => array("Le référentiel est fini"=>'1',"Le référentiel est en cours de développement"=>'0',"Le référentiel est en dev mais ouvert au RDDP"=>'-1',"Le référentiel est obsolete"=>'-2')))
                ->add('referentielExterne') 
                ->add('versionningParent')
                ->add('versionningChildren')
            ;
        }
        else 
        {
            $builder
                ->add('public')
                ->add('short')
                ->add('referentielExterne');
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\ReferentielPublic',
            'edit' => false,

            
        ));
    }
}
