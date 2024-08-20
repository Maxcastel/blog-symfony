<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ArticleType extends AbstractType
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['attr' => ['placeholder' => 'Titre de l\'article'], 'label' => 'Titre' ])
            ->add('description', null, ['attr' => ['placeholder' => 'Description de l\'article'] ])
            ->add('imageUrl', TextType::class, ['attr' => ['placeholder' => 'Url de l\'image'], 'label' => 'Url de l\'image' ])
            ->add('content', HiddenType::class, ['attr' => ['class' => 'content']]);
        ;

        if ($options['isEdit']) {
            $builder
                ->add('user', ChoiceType::class, [
                    'choices' => $this->userRepository->findByRole('ROLE_ADMIN'),
                    'choice_label' => function (User $user) {
                        return $user->getFirstName() . ' ' . $user->getName();
                    },
                    'label' => 'Auteur',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'isEdit' => false,
        ]);
    }
}
