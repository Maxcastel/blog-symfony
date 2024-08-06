<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ArticleController extends AbstractController
{
    #[Route('/article/create', name: 'article_create')]
    public function createArticle(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = new Article();
            
            $title = $form->get('title')->getData();
            
            $article->setTitle($title);

            $article->setDescription($form->get('description')->getData());
            $article->setContent($form->get('content')->getData());

            $slugger = new AsciiSlugger();

            $article->setLink($slugger->slug($title));
            $article->setUser($this->getUser());

            date_default_timezone_set('Europe/Paris');
            
            $article->setCreationDate(new DateTime());

            $em->persist($article);
            $em->flush();
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}/edit', name: 'article_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editArticle(int $id, Request $request, ArticleRepository $articleRepository, EntityManagerInterface $em): Response
    {
        $article = $articleRepository->find($id);
        
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set('Europe/Paris');

            $article->setLastUpdate(new DateTime());

            $slugger = new AsciiSlugger();

            $article->setLink($slugger->slug($form->get('title')->getData()));

            $em->persist($article);
            $em->flush();
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
