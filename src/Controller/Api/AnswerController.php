<?php

namespace App\Controller\Api;

use App\Entity\Answer;
use OpenApi\Attributes as OA;
use App\Repository\UserRepository;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/api')]
class AnswerController extends AbstractController
{
    /**
     * Display the list of all answers
     *
     * @param AnswerRepository $answerRepository
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Answer')]
    #[Route('/answers', name:'api_answer_list', methods: ['GET'])]
    public function getAnswers(AnswerRepository $answerRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $userId = $user->getId();

        $answers = $answerRepository->findAllAnswerByUser($userId);
        return $this->json($answers, 200, [], ['groups' => 'get_answer']);
    }


    /**
     * Create a new answer
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Answer')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "studentAnswer", type: "text", example: "Réponse de l'étudiant"),
                new OA\Property(property: "userId", type: "integer", example: "Id de l'utilisateur"),
                new OA\Property(property: "questionId", type: "integer", example: "Id de la question"),
            ]
        )
    )]
    #[Route('/answers/create', name: 'api_answer_create', methods: ['POST'])]
    public function createAnswer(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, AnswerRepository $answerRepository, QuestionRepository $questionRepository, UserRepository $userRepository): JsonResponse
    {
        $answer = $serializer->deserialize($request->getContent(), Answer::class, 'json');
        
        $content = $request->toArray();
        $questionId = $content ['questionId'];
        $userId = $content ['userId'];

        $answer->setQuestion($questionRepository->find($questionId));
        $answer->setUser($userRepository->find($userId));

        $entityManager->persist($answer);
        $entityManager->flush();

        return $this->json($answer, 201, [], ['groups' => 'get_answer']);
    }

    /**
     * Display the details of a answer
     * 
     * @param QuestionRepository $answerRepository
     * @param int $id the identifier of a anwser
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Answer')]
    #[Route('/answers/{id<\d+>}', name: 'api_answer_show', methods: ['GET'])]
    public function getAnswerId(AnswerRepository $answerRepository, int $id): JsonResponse
    {
        $answer = $answerRepository->find($id);
        if (!$answer){
            return $this->json("Erreur : reponse inexistante", 404);
        }
        return $this->json($answer, 200, [], ['groups' => 'get_answer']);
    }

    /**
     * Update a answer
     * 
     * @param Answer $answer instance of Answer
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Answer')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "studentAnswer", type: "text", example: "Réponse de l'étudiant"),
            ])
    )]
    #[Route('/answers/{id}/edit', name: 'api_answer_update', methods: ['PUT'])]
    public function updateAnswer(Answer $answer, EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $updatedAnswer= $serializer->deserialize($request->getContent(), Answer::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $answer]);

        $entityManager->persist($updatedAnswer);
        $entityManager->flush();

        return $this->json($updatedAnswer, 200, [], ['groups' => 'get_answer']);
    }

}