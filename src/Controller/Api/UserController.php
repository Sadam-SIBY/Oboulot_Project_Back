<?php

namespace App\Controller\Api;

use App\Entity\User;
use OpenApi\Attributes as OA;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api')]
class UserController extends AbstractController
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    /**
     * Display the list of all users
     *
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    #[OA\Tag(name: 'User')]
    #[Route('/users', name: 'api_user_list', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        /** @var User $userConnected */
        $userConnected = $this->getUser();
        $userId = $userConnected->getId();
        
        $users = $userRepository->findAllUsersCreatedByTeacher($userId);
        return $this->json($users, 200, [], ['groups' => 'get_user']);
    }

    /**
     * Display the information of the logged-in user
     *
     * @return JsonResponse
     */
    #[OA\Tag(name: 'User')]
    #[Route('/users/profile', name: 'api_user_profil', methods: ['GET'])]
    public function getUsersConnect(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json($user, 200, [], ['groups' => 'get_user']);
    }

    /**
     * Create a new user (student)
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @return JsonResponse
     */
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "email", type: "string", example: "exemple@exemple.fr"),
                new OA\Property(property: "firstname", type: "string", example: "Exemple"),
                new OA\Property(property: "lastname", type: "string", example: "Exemple"),
                new OA\Property(property: "password", type: "string", example: "motdepasse"),
            ]
        )
    )]
    #[OA\Tag(name: 'User')]
    #[Route('/users/create', name: 'api_user_create', methods: ['POST'])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        /** @var User $userConnected */
        $userConnected = $this->getUser();
        $userId = $userConnected->getId();

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $user->setCreatorNumber($userId);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201, [], ['groups' => 'get_user']);
    }

    /**
     * Display the details of a user
     * 
     * @param UserRepository $userRepository
     * @param int $id the identifier of a user
     * @return JsonResponse
     */
    #[OA\Tag(name: 'User')]
    #[Route('/users/{id<\d+>}', name: 'api_user_show', methods: ['GET'])]
    public function getUserId(UserRepository $userRepository, int $id): JsonResponse
    {
        $user = $userRepository->find($id);
        if (!$user){
            return $this->json("Erreur : utilisateur inexistant", 404);
        }
        return $this->json($user, 200, [], ['groups' => 'get_user']);
    }

    /**
     * Update a user
     * 
     * @param User $user instance of user
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return JsonResponse
     */
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "email", type: "string", example: "exemple@exemple.fr"),
                new OA\Property(property: "firstname", type: "string", example: "Exemple"),
                new OA\Property(property: "lastname", type: "string", example: "Exemple"),
            ]
        )
    )]
    #[OA\Tag(name: 'User')]
    #[Route('/users/{id}/edit', name: 'api_user_update', methods: ['PUT'])]
    public function updateUser(User $user, EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $user = $this->getUser();
    
        $serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $entityManager->persist($user);
        $entityManager->flush();

        $token = $this->jwtManager->create($user);

        return $this->json($token, 200, [], []);
    }

    /**
     * Update a user
     * 
     * @param User $user instance of user
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return JsonResponse
     */
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "oldPassword", type: "string", example: "Ancien mot de passe"),
                new OA\Property(property: "newPassword", type: "string", example: "Nouveau mot de passe"),
            ]
        )
    )]
    #[OA\Tag(name: 'User')]
    #[Route('/users/{id}/edit-password', name: 'api_user_update_password', methods: ['PUT'])]
    public function updatePassword(User $user, EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();

        if(!$passwordHasher->isPasswordValid($user, $requestData['oldPassword'])){
            return $this->json("Erreur : mot de passe incorrect", 400, [], []);
        }
        $newPassword = $requestData['newPassword'];
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        $token = $this->jwtManager->create($user);

        return $this->json($token, 200, [], []);
    }

    /**
     * Delete a user
     * 
     * @param User $user instance of User
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[OA\Tag(name: 'User')]
    #[Route('/users/{id}/delete', name: 'api_user_delete', methods: ['DELETE'])]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json($user, 200, [], ['groups' => 'get_user']);
    }


}
