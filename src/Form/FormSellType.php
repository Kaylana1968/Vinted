<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormSellType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, [
                'label' => 'Add a picture',
                'mapped' => false,
            ])
            ->add('title', TextType::class, ['label' => 'Add a title', 'constraints' => [
                new Assert\NotBlank(['message' => "Title can't be empty."]),
                new Assert\Length([
                    'min' => 3,
                    'max' => 40,
                    'minMessage' => 'The name must contain at least {{ limit }} characters.',
                    'maxMessage' => "The name can't exceed {{ limit }} characters."
                ]),
            ]])
            ->add('description', TextareaType::class, ['label' => 'add a description of your article', 'constraints' => [
                new Assert\NotBlank(['message' => "Description can't be empty."]),
                new Assert\Length([
                    'min' => 3,
                    'max' => 1027,
                    'minMessage' => 'The description must contain at least {{ limit }} characters.',
                    'maxMessage' => "The description can't exceed {{ limit }} characters."
                ]),
            ]])
            ->add(
                'category',
                ChoiceType::class,
                [
                    'choices' => [
                        'Woman' => 'woman',
                        'Man' => 'man',
                    ],
                ]
            )
            ->add('price', MoneyType::class, [
                'label' => 'Choose a price',
                'constraints' => [
                    new Assert\NotBlank(['message' => "The price must be defined."]),
                    new Assert\Positive(['message' => 'The price must be greater than 0.']),
                ],
            ])
            ->add('Confirm', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
