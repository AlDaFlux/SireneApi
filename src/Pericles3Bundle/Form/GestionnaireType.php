<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;


use Symfony\Component\Form\Extension\Core\Type\TextType;


class GestionnaireType extends AbstractType
{
    private $mega_admin;


    
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['mega_admin'])) $this->mega_admin = $options['mega_admin'];

    
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('tel')
            ->add('creai');
/*             ->add('finess_num', TextType::class, ['required' => false,'mapped' => false,]) */

        if ($this->mega_admin)
        {
            $builder->add('finess', AutocompleteType::class, ['required'=> false,'class' => 'Pericles3Bundle:FinessGestionnaire']);
            $builder->add('newFonctionnaliteGestionnaire');
        }
        
            
        $builder->add('category', EntityType::class, 
                    ['class' => 'Pericles3Bundle\Entity\GestionnaireCategory']
                    );
        
                $builder->add('StockageGestionnaire', EntityType::class, [
                    'class' => 'Pericles3Bundle\Entity\StockageGestionnaire'         
                ]);
                
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\Gestionnaire',
            'allow_extra_fields' => true,
            'mega_admin' => false,

        ));
    }
}
