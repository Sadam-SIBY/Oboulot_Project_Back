<?php

namespace App\Controller\Backoffice;

use App\Repository\GroupRepository;
use App\Repository\ExerciseRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/backoffice', name: 'home')]
    public function home(GroupRepository $groupRepository, ExerciseRepository $exerciseRepository, UserRepository $userRepository): Response
    {
        $groups = $groupRepository->findAllOrderByNameAsc();
        $exercises = $exerciseRepository->findAllOrderByTitleAsc();
        $nbUsers = $userRepository->count([]);

        return $this->render('main/home.html.twig', [
            'groups' => $groups,
            'exercises' => $exercises,
            'nbUsers' => $nbUsers,
        ]);
    }
}
