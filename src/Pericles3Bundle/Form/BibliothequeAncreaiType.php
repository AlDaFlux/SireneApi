<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Pericles3Bundle\Repository\ReferentielPublicRepository; 

use Symfony\Component\Form\Extension\Core\Type\FileType;
#use Symfony\Component\HttpFoundation\File\File;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class BibliothequeAncreaiType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         if ($options['onlyfile'])
         {
                $builder->add('cache', FileType::class,  array('required' => false, 'label' => 'Cache (PDF file)','data_class' => null));
         }
         else
         {
                $builder
                    ->add('titre')
                    ->add('href',UrlType::class, array('label' => 'URL (http://...)') )
                    ->add('typeSourceBA')
                    ->add('datePublication', DateType::class, array(
                                                        'label' => 'Date de publication',
                                                        'widget' => 'single_text',
                                                        'input' => 'datetime',
                                                        'format' => 'dd-MM-yyyy',
                                                        'attr' => array('class' => 'date')
                                                        ));
        //        $builder->add('cache', FileType::class,  array('required' => false, 'label' => 'Cache (PDF file)','data_class' => null));

                if ($options['avec_public'])
                {
                     $builder->add('referentielPublics', EntityType::class, array(
                        'class' => 'Pericles3Bundle:referentielPublic',
                        'multiple' => true,
                        'expanded' => true,
                            'query_builder' => function(ReferentielPublicRepository $repository) 
                            {  $qb = $repository->createQueryBuilder('referentiel');
                                return $qb;
                            }  
                        ));
                }
            }

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\BibliothequeAncreai',
            'avec_public' => true,
            'onlyfile' => false,
        ));
    }
}
