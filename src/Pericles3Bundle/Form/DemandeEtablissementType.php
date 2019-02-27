<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Pericles3Bundle\Repository\ReferentielPublicRepository; 
use Pericles3Bundle\Repository\ModeCotisationRepository; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class DemandeEtablissementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['gestionnaire'])
        {
            $builder->add('etablissementNom')
            //->add('typeClient', ChoiceType::class, array('attr' => array('class' => 'inline'), 'expanded' => true,'choices' => array('Contribuant CREAI  '=>'1','Ancien utilisateur PERICLES  '=>'2','Autre  '=>'3')))
   
            ->add('ModeCotisation', EntityType::class, array(
                    'attr' => array('class' => 'alert alert-danger'),
                    'class' => 'Pericles3Bundle:ModeCotisation',
                    'expanded' => true,
                        'query_builder' => function(ModeCotisationRepository $repository) 
                        {
                            $qb = $repository->createQueryBuilder('cotis');
                            return $qb->where('cotis.id>0 and cotis.id<5 ');
                        }   
                    ))   
            ->add('FinessCode')
            ->add('referentielPublic', EntityType::class, array(
                    'attr' => array('class' => 'alert alert-danger'),
                    'class' => 'Pericles3Bundle:referentielPublic',
                    'expanded' => true,
                        'query_builder' => function(ReferentielPublicRepository $repository) 
                        {  $qb = $repository->createQueryBuilder('referentiel');
                            return $qb
                                ->where('referentiel.fini=1');}  
                    ));              
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
            ->add('demandeur_nom', TextType::class, ['required' => true])
            ->add('demandeur_prenom', TextType::class, ['required' => true])
            ->add('etablissementNom', TextType::class, ['required' => true])
//            ->add('typeClient', ChoiceType::class, array('attr' => array('class' => 'inline'), 'expanded' => true,'choices' => array('Contribuant CREAI  '=>'1','Ancien utilisateur PERICLES  '=>'2','Autre  '=>'3')))
          
      
            ->add('ModeCotisation', EntityType::class, array(
                    'attr' => array('class' => 'alert alert-danger'),
                    'class' => 'Pericles3Bundle:ModeCotisation',
                    'expanded' => true,
                        'query_builder' => function(ModeCotisationRepository $repository) 
                        {
                            $qb = $repository->createQueryBuilder('cotis');
                            return $qb->where('cotis.id>0 and cotis.id<5 ');
                        }   
                    ))   
                
            ->add('email', TextType::class, ['required' => true])
            ->add('FinessCode')
            ->add('referentielPublic', EntityType::class, array(
                    'attr' => array('class' => 'alert alert-danger'),
                    'class' => 'Pericles3Bundle:referentielPublic',
                    'expanded' => true,
                        'query_builder' => function(ReferentielPublicRepository $repository) 
                        {  $qb = $repository->createQueryBuilder('referentiel');
                            return $qb
                                ->where('referentiel.fini=1');}  
                    ));  
        }

    }
//    ->add('finess')
    
    
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\DemandeEtablissement',
            'ancreai' => false,
            'gestionnaire' => false,
        ));
    }
}
