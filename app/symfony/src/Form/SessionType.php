<?php

namespace App\Form;

use App\Entity\Quiz;
use App\Entity\Session;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quiz', EntityType::class, [
                'label' => 'form.session_quiz.quiz',
                'class' => Quiz::class,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('user', EntityType::class, [
                'label' => 'form.session_quiz.user',
                'class' => User::class,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }
}
