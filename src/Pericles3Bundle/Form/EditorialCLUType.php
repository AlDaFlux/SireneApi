<?php

namespace Pericles3Bundle\Form;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;



use Ivory\CKEditorBundle\Form\Type\CKEditorType;



class EditorialCLUType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fichier', FileType::class,  array('required' => false, 'label' => 'CGU (PDF file)','data_class' => null));
        
                    
        
        $builder->add('commentaire');

                    
        $builder->add('datePublication', DateType::class, array(
                                                        'label' => 'Date de publication',
                                                        'widget' => 'single_text',
                                                        'input' => 'datetime',
                                                        'format' => 'dd-MM-yyyy',
                                                        'attr' => array('class' => 'date')
                                                        ));

        
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\EditorialCLU'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pericles3bundle_editorialclu';
    }


}
