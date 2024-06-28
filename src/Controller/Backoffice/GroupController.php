<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * All routes in this class will start with'/group'
 */
#[Route('/backoffice/group')]
class GroupController extends AbstractController
{
    /**
     * View all classes/groups in the backoffice
     * @return Response
     */
    #[Route('/', name: 'group_list')]
    public function list(GroupRepository $groupRepository): Response
    {
        // /** @var User $user */
        // $user = $this->getUser();
        // $userId = $user->getId();
        $groups = $groupRepository->findAllOrderByName();
        // $groups = $groupRepository->findAllGroupByUser($userId);
       
        return $this->render('group/list.html.twig', [
            'groups' => $groups
        ]);

    }

    /**
     * Displays a class via its id in the back office
     * @return Response
     */
    #[Route('/{id}/show', name: 'group_show')]
    public function show(Group $group): Response
    {
       
        return $this->render('group/show.html.twig', [
            'group' => $group,

        ]);
    }

    /**
     * Display the class's exercises through its ID in the back office
     * @return Response
     */
    #[Route('/{id}/exercises', name: 'group_exercise')]
    public function group_exercise(Group $group, GroupRepository $groupRepository, $id): Response
    {
        $exercises = $groupRepository->findExercisesByGroup($id);
       
        return $this->render('group/group_exercise.html.twig', [
            'group' => $group,
            'exercises' => $exercises
        ]);
    }

     /**
     * Display the class's exercises through its ID in the back office
     * @return Response
     */
    #[Route('/{id}/user', name: 'group_user')]
    public function group_user(Group $group): Response
    {
        $users = $group->getUser();
       
        return $this->render('group/group_user.html.twig', [
            'group' => $group,
            'users' => $users
        ]);
    }

    /**
     * Create a class via a form in the backoffice  
     * @return Response
     */
    #[Route('/create', name: 'group_create')]
    public function create(Request $request, EntityManagerInterface  $entityManager): Response
    {
        $user = new User();
        $user = $this->getUser();
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $group->addUser($user);
            $entityManager->persist($group);

            $entityManager->flush();
            // $this->addFlash(
            //     'success',
            //     'La '.$group->getName().' a bien été créée !'
            // );
            return $this->redirectToRoute('group_list');
        }
        return $this->render('group/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Modify a class via its id in a form in the backoffice
     *
     * @return Response
     */
    #[Route('/{id}/edit', name: 'group_edit')]
    public function edit(Group $group, Request $request, EntityManagerInterface  $entityManager): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); 
            // $this->addFlash(
            //     'success',
            //     'La '.$group->getName().' a bien été modifié !'
            // );
            return $this->redirectToRoute('group_list');
        }
        return $this->render('group/edit.html.twig', [
            'form' => $form,
            'group' => $group
        ]);
    }

    /**
     * Deletes a class via its id in a form in the backoffice
     *
     * @return Response
     */
    #[Route('/{id}/remove', name: 'group_remove')]
    public function remove(Group $group, EntityManagerInterface  $entityManager): Response
    {
        $entityManager->remove($group);
        $entityManager->flush();

        return $this->redirectToRoute('group_list');
    }
    

}
