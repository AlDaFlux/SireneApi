<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Pericles3Bundle\Repository\ReferentielPublicRepository; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;


use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;



class DemandeGestionnaireType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['finess'])
        {
            $builder->add('finess', AutocompleteType::class, [
                'required'    => false ,
                'attr' => array('placeholder' => "Numéro finess (012345678) ou nom du gestiopnnaire (ASSOCIATION DE REGROUPEMENT D'ETABLISSEMENTS REGIONALE..) "),
                'label' => 'Gestionnaire',
                'class' => 'Pericles3Bundle:FinessGestionnaire'
                ]);
        }
        elseif ($options['ancreai'])
        {
        $builder
            ->add('etat', EntityType::class, array(
                    'attr' => array('class' => 'alert alert-danger'),
                    'class' => 'Pericles3Bundle:DemandeEtat',
                    'expanded' => true
                    )) 
            ->add('commentaireAncreai');
        }
        else
        {
            $builder
                ->add('GestionnaireNom', TextType::class, ['required' => true])
                ->add('DemandeurNom', TextType::class, ['required' => true])
                ->add('DemandeurPrenom', TextType::class, ['required' => true])
                ->add('email', EmailType::class, ['required' => true])
                ->add('adresse')
                ->add('codePostal')
                ->add('ville')
                ->add('CommentaireCreai', TextareaType::class, ['required' => false]);
        }
    }
    
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\DemandeGestionnaire',
            'ancreai' => false,
            'finess' => false,
        ));
    }
}
