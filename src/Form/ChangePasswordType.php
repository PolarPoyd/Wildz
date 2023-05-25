<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre mot de passe actuel.']),
                ]
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nouveau mot de passe.']),
                    new Length(['min' => 6, 'max' => 255, 'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères.', 'maxMessage' => 'Le mot de passe ne doit pas dépasser {{ limit }} caractères.'])
                ]
            ]);
    }
}
