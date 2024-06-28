<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Exercise;
use App\Entity\UserExercise;
use OpenApi\Attributes as OA;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ExerciseController extends AbstractController
{
    /**
     * Display the list of all exercises
     *
     * @param ExerciseRepository $exerciseRepository
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises', name: 'api_exercise_list', methods: ['GET'])]
    public function getExercises(ExerciseRepository $exerciseRepository): JsonResponse
    {
         /** @var User $user */
        $user = $this->getUser();
        $userId = $user->getId();

        $exercises = $exerciseRepository->findAllExerciseByUser($userId);

        return $this->json($exercises, 200, [], ['groups' => 'get_exercise']);
    }

    /**
     * Create a new exercise
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "title", type: "string", example: "titre de l'exercice"),
                new OA\Property(property: "instruction", type: "string", example: "Consigne de l'exercice"),
                new OA\Property(property: "subject", type: "string", example: "Matière"),
                // new OA\Property(property: "groupExercises", type: "string", example: "[]"),
            ]
        )
    )]
    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises/create', name: 'api_exercise_create', methods: ['POST'])]
    public function createExercise(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $userExercise = new UserExercise();

        $exercise = $serializer->deserialize($request->getContent(), Exercise::class, 'json');

        $entityManager->persist($exercise);

        $userExercise->setUser($user);
        $userExercise->setExercise($exercise);
        $entityManager->persist($userExercise);

        $entityManager->flush();

        return $this->json($exercise, 201, [], ['groups' => 'get_exercise_create']);
    }

    /**
     * Display the details of a exercise
     * 
     * @param ExerciseRepository $exerciseRepository
     * @param int $id the identifier of a exercise
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises/{id<\d+>}', name: 'api_exercise_show', methods: ['GET'])]
    public function getExerciseId(exerciseRepository $exerciseRepository, int $id): JsonResponse
    {
        $exercise = $exerciseRepository->find($id);
        if (!$exercise){
            return $this->json("Erreur : exercice inexistant", 404);
        }
        return $this->json($exercise, 200, [], ['groups' => 'get_exercise']);
    }

    /**
     * Update a exercise
     * 
     * @param Exercise $exercise instance of Exercise
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */

    #[OA\Tag(name: 'Exercise')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "title", type: "string", example: "titre de l'exercice"),
                new OA\Property(property: "instruction", type: "string", example: "Consigne de l'exercice"),
                new OA\Property(property: "subject", type: "string", example: "Matière"),
            ]
        )
    )]
    #[Route('/exercises/{id}/edit', name: 'api_exercise_update', methods: ['PUT'])]
    public function updateExercise(Exercise $exercise, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $exercise->setTitle($requestData['title'] ?? $exercise->getTitle());
        $exercise->setInstruction($requestData['instruction'] ?? $exercise->getInstruction());
        $exercise->setSubject($requestData['subject'] ?? $exercise->getSubject());

        $entityManager->flush();

        return $this->json($exercise, 200, [], ['groups' => 'get_exercise_update']);
    }

    /**
     * Delete a exercise
     * 
     * @param Exercise $exercise instance of Exercise
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises/{id}/delete', name: 'api_exercise_delete', methods: ['DELETE'])]
    public function deleteExercise(Exercise $exercise, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($exercise);
        $entityManager->flush();
        return $this->json($exercise, 200, [], ['groups' => 'get_exercise']);
    }

    /**
     * Display the list of groups belonging to a exercise
     * 
     * @param ExerciseRepository $exerciseRepository
     * @param int $id the identifier of a exercise
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises/{id<\d+>}/groups', name: 'api_exercises_groups', methods: ['GET'])]
    public function getGroupsByExercise(ExerciseRepository $exerciseRepository, int $id): JsonResponse
    {
        $groups = $exerciseRepository->findGroupByExercise($id);
        return $this->json($groups, 200, [], ['groups' => 'get_exercise']);
    }

    /**
     * Display the list of questions belonging to a exercise
     * 
     * @param ExerciseRepository $exerciseRepository
     * @param int $id the identifier of a exercise
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises/{id<\d+>}/questions', name: 'api_exercises_questions', methods: ['GET'])]
    public function getQuestionsByExercise(ExerciseRepository $exerciseRepository, int $id): JsonResponse
    {
        $questions = $exerciseRepository->findQuestionByExercise($id);
        return $this->json($questions, 200, [], ['groups' => 'get_exercise']);
    }

    /**
     * Display the list of users belonging to a exercise
     * 
     * @param ExerciseRepository $exerciseRepository
     * @param int $id the identifier of a exercise
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises/{id<\d+>}/users', name: 'api_exercise_users', methods: ['GET'])]
    public function getUserByExercise(ExerciseRepository $exerciseRepository, int $id): JsonResponse
    {
        $users = $exerciseRepository->findUsersByExercise($id);
       
        return $this->json($users, 200, [], ['users' => 'get_exercise']);
    }

    /**
     * Display the list of answers's questions belonging to a exercise filter by user
     * 
     * @param ExerciseRepository $exerciseRepository
     * @param int $id the identifier of a exercise
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises/{id<\d+>}/answers', name: 'api_exercises_answers', methods: ['GET'])]
    public function getAnswerByExerciseAndByUser(ExerciseRepository $exerciseRepository, int $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $userId = $user->getId();

        $questions = $exerciseRepository->findQuestionByExercise($id);
        $answers = $exerciseRepository->findAnswerByExerciseAndByUser($id, $userId);

        $data = [
            'questions' => $questions,
            'answers' => $answers
        ];

        return $this->json($data, 200, [], ['groups' => 'get_answer_response']);
    }

    #[OA\Tag(name: 'Exercise')]
    #[Route('/exercises/lastid', name: 'api_exercise_lastid', methods: ['GET'])]
    public function getLastId(ExerciseRepository $exerciseRepository): JsonResponse
    {
        $lastIdRepository = $exerciseRepository->findLastId();
        $lastId = $lastIdRepository[0];
        return $this->json($lastId, 200, [], ['groups' => 'get_lastid']);
    }

}
