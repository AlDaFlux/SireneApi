<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Component\Form\Extension\Core\Type\DateType;


class EditorialMentionsLegalesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
                    
        $builder->add('datePublication', DateType::class, array(
                                                        'label' => 'Date de publication',
                                                        'widget' => 'single_text',
                                                        'input' => 'datetime',
                                                        'format' => 'dd-MM-yyyy',
                                                        'attr' => array('class' => 'date')
                                                        ));

        
        
            $builder->add('contenu', CKEditorType::class, array(
             'config' => array('toolbar' => 'full')));
    }
    
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\EditorialMentionsLegales'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pericles3bundle_editorialmentionslegales';
    }


}
