<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $articleRepository;

    public function __construct(UserRepository $userRepository, ArticleRepository $articleRepository)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findByRole('ROLE_ADMIN', false);
        $articles = $this->articleRepository->findAll();

        $comments = [
            'Super article, merci pour le partage !',
            'Le contenu est intéressant, mais la mise en page pourrait être améliorée.',
            'Article très complet, j\'attends la suite avec impatience.',
            'Merci pour ces infos, c\'est exactement ce que je cherchais.',
            'C\'est bien, mais il manque des détails sur certaines technologies.',
            'Une analyse pertinente, merci pour votre travail.',
            'Je ne suis pas fan du style, ça fait un peu daté.',
            'Article correct, mais pourrait être amélioré.',
            'Un article bien structuré, j\'ai tout compris facilement.',
            'C\'est bien, mais il manque un peu de détails.',
            'Super article, très intéressant !',
            'J\'aimerais voir plus de cas pratiques dans les prochains articles.',
            'Un article utile, mais quelques exemples supplémentaires seraient bienvenus.',
            'Bonne présentation mais j\'ai trouvé certaines parties un peu répétitives.',
            'Intéressant, mais pourriez-vous approfondir un peu plus sur ce sujet ?',
            'Continuez comme ça, c\'est top !',
        ];
        
        foreach ($articles as $article) {
            $articleCreationDate = \DateTime::createFromInterface($article->getCreationDate());
            for ($i = 1; $i <= rand(1, 5); $i++) {
                $comment = new Comment();
                
                $comment->setContent($comments[array_rand($comments)]);
                $comment->setArticle($article);
                $comment->setUser($users[array_rand($users)]);

                if (rand(0, 1)) {
                    $createdAt = (clone $articleCreationDate)->setTime(
                        rand($articleCreationDate->format('H'), 23), 
                        rand($articleCreationDate->format('i')+1, 59),
                        rand(0, 59)
                    );
                } else {
                    $createdAt = (clone $articleCreationDate)->modify('+'.rand(1, 10).' days');
                    $createdAt->setTime(rand(8, 23), rand(0, 59));
                }

                $comment->setCreatedAt($createdAt);

                $comment->setIsValid(rand(0, 1));
                
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ArticleFixtures::class
        ];
    }
}
