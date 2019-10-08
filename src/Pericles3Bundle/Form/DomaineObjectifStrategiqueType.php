<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Pericles3Bundle\Repository\DomaineRepository; 

use Symfony\Component\Form\Extension\Core\Type\TextType;



class DomaineObjectifStrategiqueType extends AbstractType
{
    
    private $etablissement_id;
    private $domaine_defined;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->etablissement_id = $options['etablissement_id'];
        $this->domaine_defined = $options['domaine_defined'];
        
        if (! ($this->domaine_defined==true))
        {
        $builder
            ->add('domaine', EntityType::class, array(
                    'class' => 'Pericles3Bundle\Entity\Domaine',
                    'query_builder' => function(DomaineRepository $repository) 
                    {
                        $qb = $repository->createQueryBuilder('domaines');
                        $qb->Join('domaines.etablissement', 'domaines_etablissement');
                        $qb->Join('domaines.referentiel', 'referentiel');
                        $qb->Join('referentiel.ReferentielPublic', 'domaineReferentielPublic');
                        $qb->Join('domaines_etablissement.referentielPublic', 'etablissementReferentielPublic');
                        $qb->Where('domaineReferentielPublic.id = etablissementReferentielPublic.id');
                        
                        
                       // the function returns a QueryBuilder object
                        return $qb->andWhere('domaines.etablissement = :etablissement_id')->setParameter('etablissement_id',$this->etablissement_id);
                        ;
                    }
                ));
        }
                
            $builder->add('commentaire',TextType::class,array('label'=>"Intitulé de l'objectif"));

            $builder->add('statut', ChoiceType::class, array('choices' => array('En cours'=>'1','Important'=>'2','Terminé'=>'3')))
            ->add('dateDebut', DateType::class, array(
                                                'widget' => 'single_text',
                                                'input' => 'datetime',
                                                'format' => 'dd-MM-yyyy',
                                                'attr' => array('class' => 'date')
                                                ))
            ->add('dateEcheance', DateType::class, array(
                                                'widget' => 'single_text',
                                                'input' => 'datetime',
                                                'format' => 'dd-MM-yyyy',
                                                'attr' => array('class' => 'date')
                                                ))
                ;
            //            ->add('save',      SubmitType::class, array('label' => 'Sauvegarder'))
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\DomaineObjectifStrategique',
            'etablissement_id' => null,
            'domaine_defined' => null
        ));
    }
}
