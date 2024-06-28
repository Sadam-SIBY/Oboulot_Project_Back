<?php

namespace App\Controller\Backoffice;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * All routes in this exercises will start with'/exercise'
 */
#[Route('/backoffice/exercise')]
class ExerciseController extends AbstractController
{
    /**
     * View all exercise in the backoffice
     * @return Response
     */
    #[Route('/', name: 'exercise_list')]
    public function list(ExerciseRepository $exerciseRepository): Response
    {
        $exercises = $exerciseRepository->findAllOrderCreatedDateDesc();
        return $this->render('exercise/list.html.twig', [
            'exercises' => $exercises,
        ]);
    }

    /**
     * Displays a exercise via its id in the back office
     * @return Response
     */
    #[Route('/{id}/show', name: 'exercise_show')]
    public function show(Exercise $exercise): Response
    {
       $questions = $exercise->getQuestions();
    
        return $this->render('exercise/show.html.twig', [
            'exercise' => $exercise,
            'questions' => $questions,      
        ]);
    }

    /**
     * Display the exercice's questions through its ID in the back office
     * @return Response
     */
    #[Route('/{id}/questions', name: 'exercise_questions')]
    public function exercise_questions(Exercise $exercise): Response
    {
        $questions = $exercise->getQuestions();
       
        return $this->render('exercise/exercise_questions.html.twig', [
            'exercise' => $exercise,
            'questions' => $questions
        ]);
    }

    /**
     * Create a class via a form in the backoffice  
     * @return Response
     */
    #[Route('/create', name: 'exercise_create')]
    public function create(Request $request, EntityManagerInterface  $entityManager): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise); 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($exercise);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'L\' '.$exercise->getTitle().' a bien été crée !'
            );
       
            return $this->redirectToRoute('exercise_list');
        }
        return $this->render('exercise/create.html.twig', [
            'form' => $form,
        ]);
    }

     /**
     * Modify a class via its id in a form in the backoffice
     *
     * @return Response
     */
    #[Route('/{id}/edit', name: 'exercise_edit')]
    public function edit(Exercise $exercise, Request $request, EntityManagerInterface  $entityManager): Response
    {
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); 
            $this->addFlash(
                'success',
                'L\' '.$exercise->getTitle().' a bien été modifié !'
            );
            return $this->redirectToRoute('exercise_list');
        }
        return $this->render('exercise/edit.html.twig', [
            'form' => $form,
            'exercise' => $exercise
        ]);
    }

 /**
     * Deletes a class via its id in a form in the backoffice
     *
     * @return Response
     */
    #[Route('/{id}/remove', name: 'exercise_remove')]
    public function remove(Exercise $exercise, EntityManagerInterface  $entityManager): Response
    {
        $entityManager->remove($exercise);
        $entityManager->flush();
        
        return $this->redirectToRoute('exercise_list');
    }


}