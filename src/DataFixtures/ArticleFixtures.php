<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ArticleFixtures extends Fixture implements DependentFixtureInterface
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

            $title = 'Découvrez mon Portfolio'.($i == 1 ? '' : $i);
            $article->setTitle($title);
            $article->setDescription('Plongez dans mon portfolio et découvrez comment je l\'ai réalisé. Vous y découvrirez les langages et frameworks que j\'ai utilisé pour le créer, mon parcours, les compétences acquises, et bien plus encore !');
            $article->setContent(
                '<p><br></p><p>J\'ai réalisé mon portfolio en <strong>Symfony </strong>et <strong>React</strong>. ' . 
                'Une API en Symfony pour accéder aux données en frontend avec React.</p>' . 
                '<p><br></p><p>Je l\'ai créé car je suis passionné par le développement web, ' . 
                'j\'aime créer des projets et apprendre de nouvelles choses.</p>' . 
                '<p><br></p><p>Mais aussi afin de présenter mes projets, mes compétences, ' . 
                'mon parcours, et avoir un site professionnel permettant de trouver un stage, ' . 
                'une alternance ou un emploi, et de me contacter grâce à un formulaire de contact.</p>' . 
                '<p><br></p><h3>Voici à quoi ressemble la page d\'accueil :</h3>' . 
                '<p><br></p><p><img src="https://i.ibb.co/jTrZgFg/portfolio.png"></p>' . 
                '<p><br></p><h3>La partie présentant les projets :</h3>' . 
                '<p><br></p><p><img src="https://i.ibb.co/rmC93bQ/portfolio-projets.png"></p>' . 
                '<p><br></p><h3>La partie présentant les expériences et la partie présentant les formations :</h3>' . 
                '<p><br></p><p><img src="https://i.ibb.co/j31L3NK/portfolio-experience-formations.png"></p>' . 
                '<p><br></p><h3>La partie présentant les compétences :</h3>' . 
                '<p><br></p><p><img src="https://i.ibb.co/HVK3v9S/portfolio-skills.png"></p>' . 
                '<p><br></p><h3>La partie permettant de me contacter :</h3>' . 
                '<p><br></p><p><img src="https://i.ibb.co/SwSLnyJ/portfolio-contact-form.png"></p>' . 
                '<p><br></p><h3>On peut choisir le thème light/dark et la langue :</h3>' . 
                '<p><br></p><p><img src="https://i.ibb.co/8PbbDJM/portfolio2.png"></p>' . 
                '<p><br></p><h3>Pour la traduction anglais/français, j\'ai utilisé <a href="https://www.i18next.com/" rel="noopener noreferrer" target="_blank">i18next</a> :</h3>' . 
                '<p><br></p><p><img src="https://i.ibb.co/3cz8m8t/i18nextt.png"></p>' . 
                '<p><br></p><h3>J\'ai créé un espace d\'administration avec un système pour créer des projets en backoffice</h3>' . 
                '<h3><br></h3><h3>Formulaire de connexion avec gestion d\'erreurs :</h3>' . 
                '<p><br></p><p><img src="https://i.ibb.co/sCRx4rZ/portfolio-login1.png"><img src="https://i.ibb.co/RCQCWXR/portfolio-login2.png"><img src="https://i.ibb.co/Pm9hCL9/portfolio-login3.png"></p>' . 
                '<h3>Liste des projets :</h3>' . 
                '<p><br></p><p><img src="https://images.mcastel.fr/projets/portfolio/liste-projets-tableau-admin.png"></p>' . 
                '<h3>Éditeur de projet :</h3>' . 
                '<p><br></p><p><img src="https://images.mcastel.fr/projets/portfolio/editor1.png"></p>' . 
                '<p><img src="https://images.mcastel.fr/projets/portfolio/editor2.png"></p>' . 
                '<p><img src="https://images.mcastel.fr/projets/portfolio/editor3.png"></p>' . 
                '<p><img src="https://images.mcastel.fr/projets/portfolio/editor4.png"></p>' . 
                '<p><br></p><h3>Lien du portfolio : <a href="https://mcastel.fr/" rel="noopener noreferrer" target="_blank">https://mcastel.fr/</a></h3>'
            );
            date_default_timezone_set('Europe/Paris');
            
            $creationDate = (new \DateTime())->modify('+'.(($i-1)*2).' days');
            $creationDate->setTime(rand(8, 23), rand(0, 59));
            
            $article->setCreationDate($creationDate);
            if (rand(0, 1)) {
                if (rand(0, 1)) {
                    $lastUpdate = (clone $creationDate)->modify('+'.rand(1, 59).' minutes');
                    $article->setLastUpdate($lastUpdate);
                }
                else{
                    $lastUpdate = (clone $creationDate)->modify('+'.rand(1, 10).' days');
                    $lastUpdate->setTime(rand(8, 23), rand(0, 59), rand(0, 59));
                    $article->setLastUpdate($lastUpdate);
                }
            }

            $article->setImageUrl("https://i.ibb.co/jTrZgFg/portfolio.png");

            $article->setUser($this->userRepository->findOneBy(['email' => 'admin@admin.com']));

            $manager->persist($article);
            $manager->flush();

            $slugger = new AsciiSlugger();

            $article->setLink($slugger->slug($title)->lower().'-'.$article->getId());
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
    
}
