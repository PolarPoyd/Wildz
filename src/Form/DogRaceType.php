<?php

namespace App\Form;

use App\Entity\DogRace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class DogRaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Nom de la race',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Chihuahua'
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom.']),
                    new Length(['max' => 30, 'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.'])
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo de la race',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DogRace::class,
        ]);
    }
}
