<?php

namespace App\Controller\Backoffice;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/backoffice/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'message_home')]
    public function messageHome(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route('/send', name: 'message_send')]
    public function send(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());

            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le message a bien été envoyé !'
            );

            return $this->redirectToRoute('message_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/send.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/sent', name: 'message_sent')]
    public function sent(): Response
    {
        return $this->render('message/sent.html.twig');
    }

    #[Route('/received', name: 'message_received')]
    public function receveid(): Response
    {
        return $this->render('message/received.html.twig');
    }

    #[Route('/read/{id}', name: 'message_read')]
    public function read(Message $message, EntityManagerInterface $entityManager): Response
    {
        $message->setIsRead(true);
        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('message/read.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}/remove', name: 'message_remove')]
    public function remove(Message $message, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($message);
        $entityManager->flush();

        return $this->redirectToRoute('message_home');
    }
}
