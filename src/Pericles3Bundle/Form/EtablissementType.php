<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use Pericles3Bundle\Repository\FinessRepository; 
use Pericles3Bundle\Repository\ReferentielPublicRepository; 
use Pericles3Bundle\Repository\ModeCotisationRepository; 



use Symfony\Component\Validator\Constraints as Assert;


class EtablissementType extends AbstractType
{
    
    private $code_finess;
        
        
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['code_finess'])) $this->code_finess = $options['code_finess'];
        if (isset($options['gestionnaire'])) $this->gestionnaire = $options['gestionnaire'];
        
         
        
            $builder->add('nom');

            if ($this->code_finess)
            {
                $builder->add('finess', EntityType::class, [
                    'class' => 'Pericles3Bundle\Entity\Finess',
                    'query_builder' => function(FinessRepository $repository) 
                    {
                        $qb = $repository->createQueryBuilder('finess');
                        // the function returns a QueryBuilder object
                        return $qb
                            ->where('finess.codeFiness = :code_finess')->setParameter('code_finess',$this->code_finess);
                        ;
                        }                    
                ]);
            }
                     
             if (! $options['gestionnaire'])
             {
                $builder->add('referentielPublic', EntityType::class, array(
                    'attr' => array('class' => 'alert alert-danger'),
                    'class' => 'Pericles3Bundle:referentielPublic',
                    'expanded' => true,
                        'query_builder' => function(ReferentielPublicRepository $repository) 
                        {  $qb = $repository->createQueryBuilder('referentiel');
                            return $qb
                                ->where('referentiel.fini=1');}  
                    ));
             }
            
           
            if ($this->gestionnaire) $builder->add('gestionnaire');
            
            $builder->add('creai');

            $builder->add('ModeCotisation', EntityType::class, array(
                    'attr' => array('class' => 'alert alert-danger'),
                    'class' => 'Pericles3Bundle:ModeCotisation',
                    'expanded' => true,
                        'query_builder' => function(modeCotisationRepository $repository) 
                        {
                            $qb = $repository->createQueryBuilder('cotis');
                            return $qb->where('cotis.id>=0');
                        }   
                    ));
            
               
                $builder->add('delegationCreai', ChoiceType::class, array(
                        'multiple' => false,
                        'expanded' => true,
                        'choices' => array(
                           /* 'MEGA' => 'ROLE_MEGA_ADMIN',*/
                            'Aucune' => '0',
                            "les conseillers techniques peuvent consulter l'évaluation de l'établissement" => '1'
                        ),
                    ));
                
                
                $builder->add('StockageEtablissement', EntityType::class, [
                    'class' => 'Pericles3Bundle\Entity\StockageEtablissement'         
                ]);
                
                
            
            $builder->add('category', EntityType::class, 
                    ['class' => 'Pericles3Bundle\Entity\EtablissementCategory']
                    );
            
            $builder->add('departement');
            $builder->add('adresse');
            $builder->add('codePostal');
            $builder->add('ville');
            $builder->add('tel');
            $builder->add('fax');
            $builder->add('capacite_acceuil');
            $builder->add('qualiEval');
    }
    
    
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\Etablissement',
            'code_finess' => null,       
            'allow_extra_fields' => true,
            'gestionnaire' => true,
            'edit' => false,
            'mode_etablissement' => false,
        ));
    }
    
    
    
}
