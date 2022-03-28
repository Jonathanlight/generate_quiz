<?php

namespace App\Form\Security;

use App\Entity\User;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => null,
            'is_new' => false,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'label' => '',
                'required'=>true,
                'expanded' => false,
                'placeholder' => false,
                'choices'  => [
                    'register.form.gender.name' => null,
                    'register.form.gender.man' => User::STATUS_MAN,
                    'register.form.gender.woman' => User::STATUS_WOMAN,
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Types',
                ]
            ])
            ->add('lastName', TextType::class, [])
            ->add('firstName', TextType::class, [])
            ->add('hasValidatedCGU', CheckboxType::class, [
                'required'=>true,
            ])
            ->add('email', EmailType::class, [])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'help' => 'form.action.password.help',
                'first_options'  => array(
                    'label' => 'form.action.password'
                ),
                'second_options' => array(
                    'label' => 'form.action.newPassword'
                ),
                'attr' => array(
                    'min' => 6,
                    'max' => 20
                )
            ))
        ;
    }
}
