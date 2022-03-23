<?php

namespace App\Form;

use App\Entity\SystemSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       /* $builder
            ->add('code')
            ->add('value')
            ->add('active')
        ;*/

        $builder->add('code', null, [
            'label' => 'code',
            'attr' => ['class'=>'form-control']
        ])
        ->add('value', null, [
            'label' => 'value',
            'attr' => ['class'=>'form-control']
        ])
        ->add('active', null, [
            'label' => 'active',
            'attr' => ['class'=>'']
        ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SystemSetting::class,
        ]);
    }
}
