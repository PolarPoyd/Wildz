<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('roles', ChoiceType::class, [
            'choices' => [
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN',
            ],
            'expanded' => true,
            'multiple' => true,
            'label' => 'Rôles',
            'data' => ['ROLE_USER'],
            'constraints' => [
                new Count([
                    'min' => 1,
                    'minMessage' => 'Veuillez sélectionner au moins un rôle.',
                ]),
            ],
        ])        
    ->add('email', EmailType::class, [
        'label' => 'Adresse e-mail',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ],
        'attr' => [
            'class' => 'form-control',
            'aria-describedby' => 'emailHelp',
            'placeholder' => 'johndoe@helloworld.com'
        ],
        'required' => false,
        'constraints' => [
            new NotBlank(['message' => 'Veuillez entrer une adresse e-mail valide.']),
            new Length(['max' => 180, 'maxMessage' => 'L\'adresse e-mail ne doit pas dépasser {{ limit }} caractères.']),
        ]
    ])
    ->add('plainPassword', PasswordType::class, [
        'label' => 'Mot de passe',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ],
        'mapped' => false,
        'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Entrez un mot de passe'
        ],
        'constraints' => [
            new NotBlank(['message' => 'Veuillez entrer un mot de passe.']),
            new Length(['min' => 6, 'max' => 255, 'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères.', 'maxMessage' => 'Le mot de passe ne doit pas dépasser {{ limit }} caractères.'])
        ]
    ])
    ->add('firstname', TextType::class, [
        'label' => 'Prénom',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ],
        'attr' => [
            'class' => 'form-control',
            'placeholder' => 'Jhon'
        ],
        'required' => false,
        'constraints' => [
            new NotBlank(['message' => 'Veuillez entrer un prénom.']),
            new Length(['max' => 255, 'maxMessage' => 'Le prénom ne doit pas dépasser {{ limit }} caractères.'])
        ]
    ])
    ->add('lastname', TextType::class, [
    'label' => 'Nom',
    'label_attr' => [
        'class' => 'form-label mt-4'
    ],
    'attr' => [
        'class' => 'form-control',
        'placeholder' => 'Doe'
    ],
    'required' => false,
    'constraints' => [
        new NotBlank(['message' => 'Veuillez entrer un nom.']),
        new Length(['max' => 255, 'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.'])
    ]
    ]);

}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => User::class,
    ]);
}
}
