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
        
            ->add('email', EmailType::class, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('phone', null, [
                'attr' => ['class'=>'form-control']
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
