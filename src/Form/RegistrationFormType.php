<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


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
        'label' =>'Adresse email',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ]
    ])
    // ->add('agreeTerms', CheckboxType::class, [
    //     'mapped' => false,
    //     'constraints' => [
    //         new IsTrue([
    //             'message' => 'You should agree to our terms.',
    //         ]),
    //     ],
    //     'attr' => [
    //         'class' => 'form-check-input'
    //     ],
    //     'label_attr' => [
    //         'class' => 'form-check-label'
    //     ]
    // ])
    ->add('firstname', TextType::class, [
        'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Enter first name'
        ],
        'label' =>'Prénom',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ]
    ])
    ->add('lastname', TextType::class, [
        'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Enter last name'
        ],
        'label' =>'Nom',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ]
    ])
    ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'invalid_message' => 'Les mots de passe ne correspondent pas',
        'mapped' => false,
        'options' => [
            'attr' => [
                'class' => 'form-control',
                'autocomplete' => 'new-password'
            ],
            'label_attr' => [
                'class' => 'form-label mt-4'
            ]
        ],
        'required' => true,
        'first_options'  => [
            'label' => 'Mot de passe',
            'attr' => [
                'placeholder' => 'Enter password'
            ]
        ],
        'second_options' => [
            'label' => 'Répéter le mot de passe',
            'attr' => [
                'placeholder' => 'Répéter le mot de passe'
            ]
        ],
        'constraints' => [
            new NotBlank([
                'message' => 'Please enter a password',
            ]),
            new Length([
                'min' => 6,
                'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                // max length allowed by Symfony for security reasons
                'max' => 4096,
            ]),
            new Callback([$this, 'validatePasswords'])
        ]
    ]);
}


public function validatePasswords($plainPassword, ExecutionContextInterface $context)
{
    $firstPassword = $plainPassword['first'] ?? null;
    $secondPassword = $plainPassword['second'] ?? null;

    if ($firstPassword !== $secondPassword) {
        $context->buildViolation('Les mots de passe ne correspondent pas')
            ->atPath('first')
            ->addViolation();
    }
}




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
