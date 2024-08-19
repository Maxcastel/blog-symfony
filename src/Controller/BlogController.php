<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from($data["email"])
                ->to("maxence.castel59@gmail.com")
                ->subject($data["subject"])
                ->text($data["message"]);

            $mailer->send($email);

            $this->addFlash('success', 'Email envoyé avec succès !');
        }

        return $this->render('blog/home.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
