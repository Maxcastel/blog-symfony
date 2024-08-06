<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Repository\UserRepository;

class ArticleFixtures extends Fixture
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 11; $i++) {
            $article = new Article();

            $title = 'Article ' . $i;
            $article->setTitle($title);
            $article->setDescription('Description de l\'article '.$i);
            $article->setContent('Contenu de l\'article '.$i);

            $slugger = new AsciiSlugger();

            $article->setLink($slugger->slug($title));
            $article->setAuthor('Author ' . $i);
            $article->setLastUpdate(new \DateTime());

            $article->setUser($this->userRepository->find(1));

            
            $manager->persist($article);
        }

        $manager->flush();
    }
}
