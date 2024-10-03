<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('picture', FileType::class, ['label'=> 'Add a picture'])
            ->add('title',TextType::class,['label'=> 'Add a title', 'constraints'=>[
                new Assert\NotBlank (['message' => 'Le titre ne peut pas être vide.']),
                new Assert\Length([
                    'min' => 3,
                    'max' => 40,
                    'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.'
                ]),
                 ]])
            ->add('description', TextareaType ::class, ['label'=> 'add a description of your article','constraints'=>[
                new Assert\NotBlank (['message' => 'La description ne ne peut pas être vide.']),
                new Assert\Length([
                    'min' => 3,
                    'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                ]),
                ]])
            ->add('category', ChoiceType::class,[
                'choices'=>[
                    [
                        'Woman' => 'woman',
                        'Man' => 'man',
                    ],
                    'label' => 'Choose a category', 
                'constraints' => [
                    new Assert\Choice([
                        'choices' => ['woman', 'man'],
                        'message' => 'Please choose a category',
                    ]),
                    ]
                 ]])
            ->add('price', MoneyType::class,['label'=> 'Choose a price',
                'constraints' => [
                new Assert\NotBlank(['message' => 'Le prix doit être renseigné.']),
                new Assert\Positive(['message' => 'Le prix doit être supérieur à 0.']),
            ],
            ])
            ->add('Ajoutez', SubmitType::class)
            ;         
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }   
}
