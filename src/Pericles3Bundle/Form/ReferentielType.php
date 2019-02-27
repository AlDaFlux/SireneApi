<?php

namespace Pericles3Bundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Pericles3Bundle\Repository\ReferentielExterneNiv1Repository; 
use Pericles3Bundle\Repository\BibliothequeAncreaiRepository; 

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;



class ReferentielType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    
    private $id_referentielExterne;
    private $averifier;
    private $id_referentiel_pricipal;
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['id_referentielExterne'])) $this->id_referentielExterne = $options['id_referentielExterne'];
        if (isset($options['id_referentiel_pricipal'])) $this->id_referentiel_pricipal = $options['id_referentiel_pricipal'];
        if (isset($options['averifier'])) $this->averifier = $options['averifier'];

        if ($options['rererentiel_type']==4)
        {
            $builder
                ->add('nom',TextType::class,array('label'=>'Question'))
                ->add('reponse_oui')
                ->add('reponse_non')
//                ->add('nonconcerne', ChoiceType::class, array('choices' => array("L'établissement est obligé de répondre"=>'0',"L'établissement peut ne pas répondre à la question"=>'1')))
            ;
        }
        elseif ($options['rererentiel_type']==1)
        {
            $builder
                ->add('nom',TextType::class,array('label'=>'domaine'))
                ->add('nom_court')
            ;
        }
        elseif ($options['rererentiel_type']==3)
        {
            $builder->add('nom');
            $entity = $builder->getData();
            if ($this->id_referentielExterne)
            {
               // $builder->add('ReferentielExterneNiv1', AutocompleteType::class, ['required'    => false,'class' => 'Pericles3Bundle:ReferentielExterneNiv1']);
                
                                
                $builder->add('ReferentielExterneNiv1', EntityType::class, array(
                'class' => 'Pericles3Bundle:ReferentielExterneNiv1',
                'multiple' => false,
                'required' => false, 
                'expanded' => true,
                    'query_builder' => function(ReferentielExterneNiv1Repository $repository) 
                    {  $qb = $repository->createQueryBuilder('ReferentielExterneNiv1');
                        $qb->where('ReferentielExterneNiv1.referentielExterne='.$this->id_referentielExterne);
                        return ($qb);
                    }  
                ));
                
            }
            
            
                $builder->add('RBPP', EntityType::class, array(
                'class' => 'Pericles3Bundle:BibliothequeAncreai',
                'multiple' => false,
                'required' => false, 
                'expanded' => false,
                    'query_builder' => function(BibliothequeAncreaiRepository $repository) 
                    {  
                        $qb = $repository->createQueryBuilder('BibliothequeAncreai');
        		$qb->Join('BibliothequeAncreai.referentielPublics', 'referentielPublic');
                        $qb->orderBy('BibliothequeAncreai.titre','ASC');  
                        return $qb->where('referentielPublic.id='.$this->id_referentiel_pricipal);
                    }
                ));
                $builder->add('rbppp_comment');
        }
        else
        {
            $builder
            ->add('nom')
        ;
        }
        if ($this->averifier)  $builder->add('verifie');

        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\Referentiel',
            'rererentiel_type' => 0,
            'averifier' => false,
            'id_referentielExterne' => 0,
            'id_referentiel_pricipal' => 0,
        ));
    }
}
