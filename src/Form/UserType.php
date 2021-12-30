<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Religion;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
                'attr' => ['class'=>'form-control']
            ])
            ->add('lname', null, [
                'label' => 'Last Name (optional)',
                'attr' => ['class'=>'form-control']
            ])
            ->add('username', null, [
                'label' => 'Username',
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
            ->add('age', null, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('likes', ChoiceType::class, [
                // 'mapped' => false,
                'multiple' => true,
                'attr' => ['class'=>'form-control', 'style'=>'display:none'],
                'choices'=> [
                    'Writing' => "writing",
                    'Photography' => "photography",
                    'Walking' => "walking",
                    'Exercise' => "exercise",
                    'Gardening' => "gardening",
                    'Dance' => "dance",
                    'Drawing/painting' => "drawing",
                    'Reading' => 'reading',
                    'Video Games' => "videoGames",
                    'Writing' => "writing",
                    'Photography' => "photography",
                    "Movie Watching" => "watchMovies",
                    "Listen music" => "listenMusic",
                    "Team Sports" => "teamSports",
                    "Traveling" => "traveling",
                    "Watching Sports" => "watchingSports",
                    "Volunteer Work" => "volunteerWork",
                    "Cooking" => "Cooking",
                    "Shopping" => "Shopping"
                 ],
            ])
            ->add('dislikes', ChoiceType::class, [
                'mapped' => false,
                'multiple' => true,
                'attr' => ['class'=>'form-control', 'style'=>'display:none'],
                'choices'=> [
                    'Writing' => "writing",
                    'Photography' => "photography",
                    'Walking' => "walking",
                    'Exercise' => "exercise",
                    'Gardening' => "gardening",
                    'Dance' => "dance",
                    'Drawing/painting' => "drawing",
                    'Reading' => 'reading',
                    'Video Games' => "videoGames",
                    'Writing' => "writing",
                    'Photography' => "photography",
                    "Movie Watching" => "watchMovies",
                    "Listen music" => "listenMusic",
                    "Team Sports" => "teamSports",
                    "Traveling" => "traveling",
                    "Watching Sports" => "watchingSports",
                    "Volunteer Work" => "volunteerWork",
                    "Cooking" => "Cooking",
                    "Shopping" => "Shopping"
                 ],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('phone_number', null, [
                'attr' => ['class'=>'form-control']
            ])
            ->add('religion', EntityType::class,[
                'class' => Religion::class,
                'attr' => ['class'=>'form-control']
            ])
            ->add('facebookLink', null, [
                'label' => 'Facebook Link (optional)',
                'attr' => ['class'=>'form-control']
            ])
            ->add('telegramUsername', null, [
                'label' => "Telegram Username(optional)",
                'attr' => ['class'=>'form-control']
            ])
            ->add('currentCity', null, [
                'label' => "Current City",
                'attr' => ['class'=>'form-control']
            ])
            ->add('height', null, [
                'label' => "Height",
                'attr' => ['class'=>'form-control']
            ])
            ->add('faceColor', null, [
                'label' => "Face Color",
                'attr' => ['class'=>'form-control']
            ])
            ->add('description', null,[
                'label' => "Write short Bio about yourself",
                'attr' => ['class'=>'form-control']
            ])
            ->add('image', FileType::class,[
                'label' => "Image",
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
