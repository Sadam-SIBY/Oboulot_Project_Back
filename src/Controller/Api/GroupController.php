<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Group;
use OpenApi\Attributes as OA;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class GroupController extends AbstractController
{
    /**
     * Display the list of all classes
     *
     * @param GroupRepository $groupRepository
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Group')]
    #[Route('/groups', name: 'api_group_list', methods: ['GET'])]
    public function getGroups(GroupRepository $groupRepository): JsonResponse
    {
         /** @var User $user */
        $user = $this->getUser();
        $userId = $user->getId();
        $groups = $groupRepository->findAllGroupByUser($userId);
        return $this->json($groups, 200, [], ['groups' => 'get_group']);
    }

    /**
     * Create a new class
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Group')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string", example: "Nom de la classe"),
                new OA\Property(property: "level", type: "string", example: "Niveau de la classe"),
                new OA\Property(property: "description", type: "string", example: "Description de la classe"),
            ]
        )
    )]
    #[Route('/groups/create', name: 'api_group_create', methods: ['POST'])]
    public function createGroup(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = new User();
        $user = $this->getUser();
        $group = $serializer->deserialize($request->getContent(), Group::class, 'json');
        $group->addUser($user);
        $entityManager->persist($group);
        $entityManager->flush();

        return $this->json($group, 201, [], ['groups' => 'get_group']);
    }

    /**
     * Display the details of a class
     * 
     * @param GroupRepository $groupRepository
     * @param int $id the identifier of a class
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Group')]
    #[Route('/groups/{id<\d+>}', name: 'api_group_show', methods: ['GET'])]
    public function getGroupId(GroupRepository $groupRepository, int $id): JsonResponse
    {
        $group = $groupRepository->find($id);
        if (!$group){
            return $this->json("Erreur : classe inexistant", 404);
        }
        return $this->json($group, 200, [], ['groups' => 'get_group']);
    }

    /**
     * Update a class
     * 
     * @param Group $group instance of Group
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Group')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string", example: "Nom de la classe"),
                new OA\Property(property: "level", type: "string", example: "Niveau de la classe"),
                new OA\Property(property: "description", type: "string", example: "Description de la classe"),
            ]
        )
    )]
    #[Route('/groups/{id}/edit', name: 'api_group_update', methods: ['PUT'])]
    public function updateGroup(Group $group, EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $updatedGroup = $serializer->deserialize($request->getContent(), Group::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $group]);

        $entityManager->persist($updatedGroup);
        $entityManager->flush();

        return $this->json($updatedGroup, 200, [], ['groups' => 'get_group']);
    }

    /**
     * Delete a class
     * 
     * @param Group $group instance of Group
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Group')]
    #[Route('/groups/{id}/delete', name: 'api_group_delete', methods: ['DELETE'])]
    public function deleteGroup(Group $group, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($group);
        $entityManager->flush();
        return $this->json($group, 200, [], ['groups' => 'get_group']);
    }

    /**
     * Display the list of exercises belonging to a class
     * 
     * @param GroupRepository $groupRepository
     * @param int $id the identifier of a class
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Group')]
    #[Route('/groups/{id<\d+>}/exercises', name: 'api_group_exercises', methods: ['GET'])]
    public function getExercisesByGroup(GroupRepository $groupRepository, int $id): JsonResponse
    {
        $exercises = $groupRepository->findExercisesByGroup($id);
        return $this->json($exercises, 200, [], ['exercises' => 'get_group']);
    }

    /**
     * Display the list of users belonging to a class
     * 
     * @param GroupRepository $groupRepository
     * @param int $id the identifier of a class
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Group')]
    #[Route('/groups/{id<\d+>}/users', name: 'api_group_users', methods: ['GET'])]
    public function getUserByGroup(GroupRepository $groupRepository, int $id): JsonResponse
    {
        $users = $groupRepository->findUsersByGroup($id);
        if (!$users){
            return $this->json("Erreur : classe inexistant", 404);
        }
        return $this->json($users, 200, [], ['users' => 'get_group']);
    }

    /**
     * Add a user in class
     * 
     * @param GroupRepository $groupRepository
     * @param int $id the identifier of a class
     * @return JsonResponse
     */
    #[OA\Tag(name: 'Group')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "userId", type: "integer", example: "Id de l'élève"),
            ]
        )
    )]
    #[Route('/groups/{id}/add', name: 'api_group_add_user', methods: ['PUT'])]
    public function addUserInGroupId(Group $group, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $updatedGroup = $serializer->deserialize($request->getContent(), Group::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $group]);

        $content = $request->toArray();
        $userId =$content["userId"];

        $updatedGroup->addUser($userRepository->find($userId));

        $entityManager->persist($updatedGroup);
        $entityManager->flush();

        return $this->json($updatedGroup, 200, [], ['groups' => 'get_group']);
    }

}
