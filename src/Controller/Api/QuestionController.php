<?php

namespace App\Controller\Api;

use OpenApi\Attributes as OA;
use App\Entity\Question;
use App\Repository\ExerciseRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class QuestionController extends AbstractController
{
    /**
     * Display the list of all questions
     *
     * @param QuestionRepository $questionRepository
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Question')]
    #[Route('/questions', name: 'api_question_list', methods: ['GET'])]
    public function getQuestions(QuestionRepository $questionRepository): JsonResponse
    {
        $questions = $questionRepository->findAll();
        return $this->json($questions, 200, [], ['groups' => 'get_question']);
    }

    /**
     * Create a new question
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Question')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "number", type: "integer", example: "Numéro de la question"),
                new OA\Property(property: "content", type: "string", example: "Intitulé de la question"),
                new OA\Property(property: "teacherAnswer", type: "string", example: "Réponse attendue"),
                new OA\Property(property: "exerciseId", type: "integer", example: "Id de l'exercice"),
            ]
        )
    )]
    #[Route('/questions/create', name: 'api_question_create', methods: ['POST'])]
    public function createQuestion(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ExerciseRepository $exerciseRepository): JsonResponse
    {
        $question = $serializer->deserialize($request->getContent(), Question::class, 'json');
        
        $content = $request->toArray();
        $exerciseId = $content ["exerciseId"];
        $exerciseEntity = $exerciseRepository->find($exerciseId);

        $entityManager->persist($question);

        $exerciseEntity->addQuestion($question);

        $entityManager->persist($exerciseEntity);
        $entityManager->flush();

        return $this->json($question, 201, [], ['groups' => 'get_question_create']);
    }

    /**
     * Display the details of a question
     * 
     * @param QuestionRepository $questionRepository
     * @param int $id the identifier of a question
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Question')]
    #[Route('/questions/{id<\d+>}', name: 'api_question_show', methods: ['GET'])]
    public function getQuestionId(QuestionRepository $questionRepository, int $id): JsonResponse
    {
        $question = $questionRepository->find($id);
        if (!$question){
            return $this->json("Erreur : question inexistante", 404);
        }
        return $this->json($question, 200, [], ['groups' => 'get_question']);
    }

    /**
     * Update a question
     * 
     * @param Question $question instance of Question
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Question')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "number", type: "integer", example: "Numéro de la question"),
                new OA\Property(property: "content", type: "string", example: "Intitulé de la question"),
                new OA\Property(property: "teacherAnswer", type: "string", example: "Réponse attendue"),
            ])
    )]
    #[Route('/questions/{id}/edit', name: 'api_question_update', methods: ['PUT'])]
    public function updateQuestion(Question $question, EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $question->setNumber($requestData['number'] ?? $question->getNumber());
        $question->setContent($requestData['content'] ?? $question->getContent());
        $question->setTeacherAnswer($requestData['teacherAnswer'] ?? $question->getTeacherAnswer());

        $entityManager->flush();

        return $this->json($question, 200, [], ['groups' => 'get_question_update']);
    }

    /**
     * Delete a question
     * 
     * @param Question $question instance of Question
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Question')]
    #[Route('/questions/{id}/delete', name: 'api_question_delete', methods: ['DELETE'])]
    public function deleteQuestion(Question $question, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($question);
        $entityManager->flush();
        return $this->json($question, 200, [], ['groups' => 'get_question']);
    }
}
