<?php

namespace App\Form;

use App\Entity\ContactUs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsTypeOLD extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       /* $builder
            ->add('message')
            ->add('email')
            ->add('createdAt')
        ;*/
        $builder
        ->add('email', null, [
            'label' => 'email',
            'attr' => ['class'=>'form-control']
        ])
        ->add('message', null, [
            'label' => 'message',
            'attr' => ['class'=>'form-control']
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
