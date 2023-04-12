<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    $builder
    ->add('email', EmailType::class, [
        'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Enter email',
            'id' => 'exampleInputEmail1',
            'aria-describedby' => 'emailHelp'
        ],
        'label' =>'Email address',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ]
    ])
    ->add('agreeTerms', CheckboxType::class, [
        'mapped' => false,
        'constraints' => [
            new IsTrue([
                'message' => 'You should agree to our terms.',
            ]),
        ],
        'attr' => [
            'class' => 'form-check-input'
        ],
        'label_attr' => [
            'class' => 'form-check-label'
        ]
    ])
    ->add('firstname', TextType::class, [
        'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Enter first name'
        ],
        'label' =>'First name',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ]
    ])
    ->add('lastname', TextType::class, [
        'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Enter last name'
        ],
        'label' =>'Last name',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ]
    ])
    ->add('plainPassword', PasswordType::class, [
        'mapped' => false,
        'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Enter password',
            'autocomplete' => 'new-password'
        ],
        'constraints' => [
            new NotBlank([
                'message' => 'Please enter a password',
            ]),
            new Length([
                'min' => 6,
                'minMessage' => 'Your password should be at least {{ limit }} characters',
                // max length allowed by Symfony for security reasons
                'max' => 4096,
            ]),
        ],
        'label' =>'Password',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ]
    ]);}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
