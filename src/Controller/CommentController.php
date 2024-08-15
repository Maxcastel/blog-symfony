<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    private $commentRepository;
    private $em;

    public function __construct(CommentRepository $commentRepository, EntityManagerInterface $em)
    {
        $this->commentRepository = $commentRepository;
        $this->em = $em;
    }

    #[Route('/admin/comments', name: 'admin_comments')]
    #[IsGranted('ROLE_ADMIN')]
    public function showCommentsAdmin(): Response
    {
        return $this->render('comment/admin/index.html.twig', [
            'comments' => $this->commentRepository->findBy(['isValid' => false]),
        ]);
    }

    #[Route('/admin/comments/validate/{id}', name: 'admin_comment_validate')]
    #[IsGranted('ROLE_ADMIN')]
    public function validateComment($id, Request $request): Response
    {
        $comment = $this->commentRepository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Commentaire non trouvé.');
        }

        if (!$this->isCsrfTokenValid('validate'.$id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $comment->setIsValid(true);
        $this->em->flush();

        return $this->redirectToRoute('admin_comments');
    }
    
    #[Route('/admin/comments/delete/{id}', name: 'admin_comment_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteComment($id, Request $request): Response
    {
        $comment = $this->commentRepository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Commentaire non trouvé.');
        }

        if (!$this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $this->em->remove($comment);
        $this->em->flush();

        return $this->redirectToRoute('admin_comments');
    }
}
