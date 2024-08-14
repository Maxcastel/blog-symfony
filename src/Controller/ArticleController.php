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
use App\Entity\Comment;
use App\Form\CommentType;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_show_all')]
    public function showAllArticles(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/articles.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/articles/{slug}', name: 'article_show')]
    public function showArticle(string $slug, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBy(["link" => $slug]);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm->createView()
        ]);
    }

    #[Route('/article/create', name: 'article_create')]
    #[IsGranted('ROLE_ADMIN')]
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

            $article->setUser($this->getUser());

            date_default_timezone_set('Europe/Paris');
            
            $article->setCreationDate(new DateTime());

            $em->persist($article);
            $em->flush();

            $slugger = new AsciiSlugger();

            $article->setLink($slugger->slug($title)->lower().'-'.$article->getId());

            $em->flush();

            $this->addFlash('success', 'L\'article a été créé avec succès !');
        }

        return $this->render('article/admin/create.html.twig', [
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

            $article->setLink($slugger->slug($form->get('title')->getData())->lower().'-'.$article->getId());

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'L\'article a été modifié avec succès !');
        }

        return $this->render('article/admin/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }   

    #[Route('/admin/articles', name: 'article_show_all_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function showAllArticlesAdmin(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/admin/articles.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/article/{id}/delete', name: 'article_delete', methods: ['POST', 'DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteArticle(int $id, Request $request, ArticleRepository $articleRepository, EntityManagerInterface $em): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', 'L\'article a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('article_show_all_admin');
    }
}
