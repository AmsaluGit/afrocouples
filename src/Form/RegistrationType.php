<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Religion;
use App\Entity\City;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fname', null, [
                'label' => 'First Name',
                'attr' => ['class'=>'form-control col-3']
            ])
            ->add('username', null, [
               'label' => 'Username Name',
               'attr' => ['class'=>'form-control col-3']
           ])
            ->add('phone', null, [
               'label' => 'Phone number',
               'required' => false,
               'attr' => ['class'=>'form-control col-3']
           ])
           ->add('email', EmailType::class, [
            'label' => 'Email',
            'required' => false,
            'attr' => ['class'=>'form-control']
        ])
            ->add('sex' ,ChoiceType::class,[
                'required' => true,
                'attr'=>['class'=>'form-control'],
                'choices'  => [
                    'Male' => "m",
                    'Female' => "f",
                 ],
            ])

            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                // 'html5' => false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'form-control'],
            ])
            // ->add('email', EmailType::class, [
            //     'attr' => ['class'=>'form-control']
            // ])

            ->add('city', EntityType::class,[
                'class' => City::class,
                'attr' => ['class'=>'form-control']
            ])
            ->add('religion', EntityType::class,[
                'class' => Religion::class,
                'attr' => ['class'=>'form-control']
            ])
            ->add('plainPassword', RepeatedType::class, [
               'type' => PasswordType::class,
               'invalid_message' => 'The password fields must match.',
               'options' => ['attr' => ['class' => 'password-field form-control']],
               'required' => true,
               'mapped' => false,
               'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control py-1'],
               'constraints' =>  [
                   new NotBlank([
                       'message' => 'Please enter a new password',
                   ]),
                   new Length([
                       'min' => 5,
                       'minMessage' => 'Your password should be at least {{ limit }} characters',
                       // max length allowed by Symfony for security reasons
                       'max' => 4096,
                   ]),
               ],
               'first_options'  => ['label' => 'Password'],
               'second_options' => ['label' => 'Repeat Password'],
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
