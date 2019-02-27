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



class CommentaireDomaineType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder->add('commentaire');
    }
    
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pericles3Bundle\Entity\CommentaireDomaine'
        ));
    }
}
