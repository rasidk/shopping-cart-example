<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product_name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],

            ])
            ->add('price', NumberType::class, [
                'scale' => 2, // set to 2 decimal places
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],

            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],

            ])
            ->add('sizes', ChoiceType::class, [
                'choices' => [
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                ],
                'multiple' => true,
                'expanded' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select at least one size',
                    ])
                ]
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
