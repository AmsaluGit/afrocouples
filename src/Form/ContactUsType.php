<?php

namespace App\Form;

use App\Entity\ContactUs;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'mapped'=>true,
                'label' => 'Email',
                'attr'=>['class'=>'form-control mb-1 mt-0'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your email',
                    ]),
                    new Length([
                        'max' => 60
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                // 'type' => TextareaType::class,
                'attr'=>['class'=>'form-control mb-1', 'rows'=>7],
                'invalid_message' => 'Please enter your message.',
               // 'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'mapped' => true,
               // 'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control py-1'],
                'constraints' =>  [
                    new NotBlank([
                        'message' => 'Please enter your message',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Your message should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]/*,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],*/
            ])

        ;
    }

  

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactUs::class,
        ]);
    }


}
