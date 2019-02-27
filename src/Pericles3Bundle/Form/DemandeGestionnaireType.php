<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Pericles3Bundle\Repository\ReferentielPublicRepository; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class DemandeGestionnaireType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['ancreai'])
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
                ->add('email')
                ->add('adresse')
                ->add('codePostal')
                ->add('ville')
                ->add('CommentaireCreai', TextareaType::class);
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
        ));
    }
}
