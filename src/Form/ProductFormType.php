<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\VarDumper\Cloner\Data;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class,
        [
            'label' => "Product Name",
            'required' => true
        ])
        ->add('price' , MoneyType::class,
        [
            'currency' => "USD"
        ])
        ->add('description', TextType::class,
        [
            'label' => "Description",
            'required' => true
        ])
        ->add('images', FileType::class,
        [
            'data_class' => null,
            'required' => is_null($builder->getData()->getImages())
        ])
        ->add('CAT', EntityType::class,
        [
            'class' => Category::class,
            'label' => 'Category',
            'choice_label' => 'Name',
            'multiple' => true,  //true: select many, false: select only 1
            'expanded' => false  //true: checkbox   , false: drop-down list
        ])
        ->add('DateAdded', DateType::class,
        [
            'widget' => 'single_text'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
