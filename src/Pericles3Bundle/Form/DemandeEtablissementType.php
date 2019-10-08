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


use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;



class DemandeEtablissementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['finess'])
        {
            $builder->add('finess', AutocompleteType::class, 
                    [
                'required'    => false ,
                'attr' => array('placeholder' => "Numéro finess (012345678) ou nom de l'établissement (IME DU BERGERACOIS,..) "),
                'label' => 'Etablissement',
                'class' => 'Pericles3Bundle:Finess'
                ]);
                    

        }
        elseif ($options['gestionnaire'])
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
            ->add('demandeur_nom', TextType::class, ['required' => $options['required']])
            ->add('demandeur_prenom', TextType::class, ['required' => $options['required']])
            ->add('etablissementNom', TextType::class, ['required' => $options['required']])
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
                ->add('adresse')
                ->add('codePostal')
                ->add('ville')
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
            'finess' => false,
            'required' => false,
        ));
    }
}
