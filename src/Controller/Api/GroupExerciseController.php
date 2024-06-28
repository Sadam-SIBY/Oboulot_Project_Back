<?php

namespace App\Controller\Api;

use App\Entity\UserExercise;
use App\Entity\GroupExercise;
use OpenApi\Attributes as OA;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupExerciseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class GroupExerciseController extends AbstractController
{
    #[OA\Tag(name: 'GroupExercise')]
    #[Route('/groupexercises', name: 'api_group_exercise_list', methods: ['GET'])]
    public function getGroupExercise(GroupExerciseRepository $groupExerciseRepository): JsonResponse
    {

         /** @var User $user */
         $user = $this->getUser();
         $userId = $user->getId();

        $groupExercise = $groupExerciseRepository->findGroupExerciseByUser($userId);

        return $this->json($groupExercise, 200, [], ['groups' => 'get_group_exercise']);
    }

    #[OA\Tag(name: 'GroupExercise')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "idGroup", type: "integer", example: "Id de la classe"),
                new OA\Property(property: "idExercise", type: "integer", example: "Id de l'exercice")
                ]
            )
    )]
    #[Route('/groupexercises/create', name: 'api_group_exercise_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, GroupRepository $groupRepository, ExerciseRepository $exerciseRepository, UserRepository $userRepository): JsonResponse
    {
        $groupExercise = $serializer->deserialize($request->getContent(), GroupExercise::class, 'json');
        
        $content = $request->toArray();
        $idGroup = $content['idGroup'];
        $idExercise = $content ['idExercise'];

        $groupExercise->setGroup($groupRepository->find($idGroup));
        $groupExercise->setExercise($exerciseRepository->find($idExercise));
        $groupExercise->setStatus(1);

        $exercice = $groupExercise->getExercise();
        $exercice->setPublishedAt(new \DateTimeImmutable());

        $id = $groupExercise->getGroup()->getId();
        $users = $groupRepository->findUsersByGroup($id);

        foreach ($users as $user){
            $userConnected = $this->getUser();
            $userId = $user['id'];
            /** @var User $userConnected */
            if($userConnected && $userId !== $userConnected->getId()){
                $userExercise = new UserExercise();
                $userEntity = $userRepository->find($userId);
                $userExercise->setUser($userEntity);
                $userExercise->setExercise($groupExercise->getExercise()); 

                $entityManager->persist($userExercise);
            } 
        }

        $entityManager->persist($groupExercise);
        $entityManager->flush();


        return $this->json($groupExercise, 201, [], ['groups' => 'get_group_exercise']);
    }
}
