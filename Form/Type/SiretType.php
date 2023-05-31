<?php

namespace  Aldaflux\SireneApiBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Exception\TransformationFailedException;

use Symfony\Component\Form\CallbackTransformer;


use Symfony\Component\Form\FormBuilderInterface;


class SiretType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['empty_data' => '','paceholder' => '12345678911234','required'=>false, 'invalid_message'=>"Le SIRET n'est pas valide" ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
      
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->addModelTransformer(new CallbackTransformer(
                function ($data) {
                    if ($data)
                    {
                        $data=str_replace(" ","", $data);
                        return($data[0].$data[1].$data[2]." ".$data[3].$data[4].$data[5]." ".$data[6].$data[7].$data[8]." ".$data[9].$data[10].$data[11].$data[12].$data[13]);
                    }
                    else {return(null);}
                },
                function ($data) {
                    if ($data)
                    {
                        $data=str_replace(" ","", $data);
                        if (is_numeric($data))
                        {
                            if (strlen($data)==14)
                            {
                                return($data);
                            }
                            else
                            {
                                    $privateErrorMessage = "Le Siret doit être composé de 14 chiffres";
                                    $publicErrorMessage = "Le Siret doit être composé de 14 chiffres";
                                    $failure = new TransformationFailedException($privateErrorMessage);
                                    $failure->setInvalidMessage($publicErrorMessage, ['{{ value }}' => 6543243,]);
                                    throw $failure;
                            }
                        }
                        else
                        {
                                    $privateErrorMessage = "Le SIRET doit être composé de chiffre";
                                    $publicErrorMessage = "Le SIRET doit être composé de chiffre";
                                    $failure = new TransformationFailedException($privateErrorMessage);
                                    $failure->setInvalidMessage($publicErrorMessage, ['{{ value }}' => 564564,]);
                                    throw $failure;
                        }
                            
                    }
                    else
                    {
                        return null;
                    }
                }
            ));
    } 
}