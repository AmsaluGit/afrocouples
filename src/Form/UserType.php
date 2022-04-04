<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Religion;
use App\Entity\Nationality;
use App\Entity\Education;
use App\Entity\Occupation;
use App\Entity\City;
use App\Entity\MaritalStatus;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fname', null, [
                'label' => 'First Name',
                'attr' => ['class'=>'form-control col-3']
            ])
            ->add('mname', null, [
                'label' => 'Middle Name',
                'required' => false,
                'attr' => ['class'=>'form-control']
            ])
            ->add('lname', null, [
                'required' => false,
                'label' => 'Last Name (optional)',
                'attr' => ['class'=>'form-control']
            ])
            ->add('message', null, [
                'label' => 'Message',
                'attr' => ['class' => 'form-control']
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
                
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'form-control'],
            ])
           
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['class'=>'form-control']
            ])
            ->add('religion', EntityType::class,[
                'class' => Religion::class,
                'attr' => ['class'=>'form-control']
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Height(centi meter)',
                'required' => false,
                'attr' => ['class'=>'form-control']
            ])
         

            ->add('nationality', EntityType::class, [
                'class' => Nationality::class,
                'attr' => ['class'=>'form-control']
            ])
            ->add('education', EntityType::class, [
                'class' => Education::class,
                'required' => false,
                'attr' => ['class'=>'form-control']
            ])

            ->add('city', EntityType::class, [
                'class' => City::class,
                'attr' => ['class'=>'form-control']
            ])

            ->add('occupation', EntityType::class, [
                'class' => Occupation::class,
                'required' => false,
                'attr' => ['class'=>'form-control']
            ])
            ->add('maritalStatus', EntityType::class, [
                'class' => MaritalStatus::class,
                'required' => false,
                'attr' => ['class'=>'form-control']
            ])
            ->add('phone', NumberType::class, [
                'label' => "Phone Number",
                'required' => false,
                'attr' => ['class'=>'form-control']
            ])
            ->add('color' ,ChoiceType::class,[
                'required' => false,
                'attr'=>['class'=>'form-control'],
                'choices'  => [
                    'Black' => "black",
                    'White' => "white",
                 ],
            ])
            ->add('quote', null,[
                'required' => false,
                'label' => "Short Quote",
                'attr' => ['class'=>'form-control']
            ])
            // ->add('profileImage', FileType::class,[
            //     'label' => "Image",
            //     'required' => false,
            //     'mapped' => false,
            //     'attr' => ['class'=>"form-control profile-image-input"]
            // ])
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