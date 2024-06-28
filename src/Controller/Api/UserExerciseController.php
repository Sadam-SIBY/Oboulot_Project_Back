<?php

namespace App\Controller\Api;

use App\Entity\UserExercise;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserExerciseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class UserExerciseController extends AbstractController
{
    #[OA\Tag(name: 'UserExercise')]
    #[Route('/userexercise', name: 'api_user_exercise_list', methods: ['GET'])]
    public function getUserExercise(UserExerciseRepository $userExerciseRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $userId = $user->getId();

        $userExercise = $userExerciseRepository->findAllUserExerciseByUser($userId);
        return $this->json($userExercise, 200, [], ['groups' => 'get_user_exercise']);
    }

    /**
     * Update user exercise entity
     * 
     * @param UserExercise $answer instance of Answer
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[OA\Tag(name: 'UserExercise')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "exerciseId", type: "integer", example: "Id de l'exercice"),
                new OA\Property(property: "userId", type: "integer", example: "Id de l'utilisateur"),
                new OA\Property(property: "isDone", type: "boolean", example: true),
            ])
    )]
    #[Route('/userexercise/{id}/edit', name: 'api_user_exercise_update', methods: ['PUT'])]
    public function updateUserExercise(UserExercise $userExercise, EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $updatedUserExercise= $serializer->deserialize($request->getContent(), UserExercise::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $userExercise]);

        $entityManager->persist($updatedUserExercise);
        $entityManager->flush();

        return $this->json($updatedUserExercise, 200, [], ['groups' => 'get_user_exercise']);
    }
}


