<?php

namespace App\Controller\Backoffice;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'question_list', methods: ['GET'])]
    public function list(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/list.html.twig', [
            'questions' => $questionRepository->findAllOrderExerciseTitleAsc(),
        ]);
    }

    #[Route('/create', name: 'question_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/create.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('question_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/remove', name: 'question_remove')]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($question);
            $entityManager->flush();

        return $this->redirectToRoute('question_list');
    }
}
