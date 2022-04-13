<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 
use Symfony\Component\Validator\Constraints\File;


class AudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('audio', FileType::class, [

            // unmapped means that this field is not associated to any entity property
            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,
            'data_class' => null,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '100M',
                    'mimeTypes' => [
                        'audio/mpeg',
                        'audio/mp3',
                    ],
                    // 'mimeTypesMessage' => 'Please upload a valid PDF',
                ])
            ],
        ])
            
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save btn btn-primary', 'style'=>'width: 200px'],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}