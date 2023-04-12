<?php

namespace App\Form;

use App\Entity\Cats;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\CatRace;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mimi'
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un nom.']),
                    new Length(['max' => 30, 'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.'])
                ]
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '2'
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un âge.']),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Une petite description de votre chat.'
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer une description.']),
                    new Length(['max' => 255, 'maxMessage' => 'La description ne doit pas dépasser {{ limit }} caractères.'])
                ]
            ])
            ->add('race', EntityType::class, [
                'class' => CatRace::class,
                'label' => 'Race de chat',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true,
                'placeholder' => 'Sélectionnez une race',
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
            'data_class' => Cats::class,
        ]);
    }
}
