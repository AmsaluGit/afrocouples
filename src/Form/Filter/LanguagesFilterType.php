<?php

namespace App\Form\Filter;

use App\Entity\City;
use App\Entity\Languages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class LanguagesFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('required' => false));
            /*->add('name', null, [
                'label' => 'Middle Name',
                'attr' => ['class'=>'form-control']
            ]);*/
          
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Languages::class,
        ]);
    }
}