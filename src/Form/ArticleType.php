<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['attr' => ['placeholder' => 'Titre de l\'article'], 'label' => 'Titre' ])
            ->add('description', null, ['attr' => ['placeholder' => 'Description de l\'article'] ])
            ->add('imageUrl', TextType::class, ['attr' => ['placeholder' => 'Url de l\'image'], 'label' => 'Url de l\'image' ])
            ->add('content', HiddenType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
