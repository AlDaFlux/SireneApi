<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Pericles3Bundle\Repository\CreaiRepository; 


use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use Symfony\Component\Form\Extension\Core\Type\TextType;



class DemandeInfosType extends AbstractType
{
    
     private $creai;
     
     
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->creai=$options['creai'];
        
        if ($options['ancreai'])
        {
            $builder
                ->add('etat', EntityType::class, array(
                        'attr' => array('class' => 'alert alert-danger'),
                        'class' => 'Pericles3Bundle:DemandeEtat',
                        'expanded' => true
                        )) 
                ->add('commentaire');
        }
        else
        {
        $builder
            ->add('demandeurNomPrenom',TextType::class,array('label'=>'Nom / Prénom'))
            ->add('etablissementService',TextType::class,array('label'=>'Etablissement / Service'));
        
        if ($this->creai)  $builder->add('creai', EntityType::class, array(
                    'label' => "Creai",
                    'class' => 'Pericles3Bundle:Creai',
                    'expanded' => false,
                    'required'    => false,
                        'query_builder' => function(CreaiRepository $repository) 
                        {  $qb = $repository->createQueryBuilder('creai');
                            return $qb
                                ->orderBy('creai.nom','ASC');}  
                    ));
            $builder->add('email')
            ->add('tel')
            ->add('remarques')
        ;
        }
        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\DemandeInfos',
            'ancreai' => false,
            'creai' => true,
        ));
    }
}
