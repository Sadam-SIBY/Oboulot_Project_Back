<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Group;
use App\Entity\Answer;
use DateTimeImmutable;
use App\Entity\Exercise;
use App\Entity\Question;
use App\Entity\GroupExercise;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OboulotProvider;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $provider = new OboulotProvider();

    $user = new User(); 
    $user->setFirstname($faker->firstName());
    $user->setLastname($faker->lastName());
    // $user->setPicture($faker->imageUrl(300, 480, $user->getLastname(), true));
    $user->setPicture('user.png');
    $user->setEmail("admin@admin.fr"); 
    $user->setRoles(['ROLE_ADMIN']); 
    $user->setPassword(password_hash("azerty",PASSWORD_BCRYPT));
    
    $manager->persist($user); 

    $user = new User(); 
    $user->setFirstname($faker->firstName());
    $user->setLastname($faker->lastName());
    $user->setPicture('user.png');
    $user->setEmail("enseignant@gmail.com"); 
    $user->setRoles(['ROLE_ENSEIGNANT']); 
    $user->setPassword(password_hash("azerty",PASSWORD_BCRYPT ));

    $manager->persist($user); 

    $userList = [];
    for ($i = 0; $i < 10; $i++) {
    $user = new User(); 
    $user->setFirstname($faker->firstName());
    $user->setLastname($faker->lastName());
    $user->setPicture('user.png');
    // $user->setPicture($faker->imageUrl(300, 480, $user->getLastname(), true));
    $user->setEmail($faker->bothify('user**@user.fr'));
    $user->setRoles(['ROLE_USER']); 
    $user->setPassword(password_hash("azerty",PASSWORD_BCRYPT));
    $userList[] = $user; 

    $manager->persist($user); 
    }

    $groupList = [];
    for ($i = 0; $i < 10; $i++) {
        $group = new Group();
        $group->setName($provider->group_rand()); 
        $group->setLevel($provider->level_rand());
        $group->setDescription($faker->text(150));
        $group->addUser($userList[rand(0,9)]);
        $groupList[] = $group; 
          
        $manager->persist($group);
    }


     $exerciseList = [];
     for ($i = 0; $i < 20; $i++) {
         $exercise = new Exercise();
         $exercise->setTitle($faker->text(40)); 
         $exercise->setInstruction($faker->text(300));
         $exercise->setSubject($faker->text(20));
         $exercise->setCreatedAt(new DateTimeImmutable());
         $exercise->setPublishedAt(new DateTimeImmutable());
         $exerciseList[] = $exercise; 

         $group_exercise = new GroupExercise();
         $group_exercise->setStatus(rand(0, 2)); 
         $group_exercise ->setExercise($exercise);
         $group_exercise->setGroup($groupList[rand(0,9)]);
     
         $manager->persist($group_exercise);
         $manager->persist($exercise);
     }

         $questionList = [];
         for ($i = 0; $i < 30; $i++) {
            $question = new Question();
            $question->setNumber(rand(0, 29)); 
            $question->setContent($faker->text(200));
            $question->setTeacherAnswer($faker->text(200));
            $question->setExercise($exerciseList[rand(0,19)]); 
            $questionList[] = $question;

            $manager->persist($question);
        }
  
        for ($i = 0; $i < 15; $i++) {
            $answers = new Answer();
            $answers->setStudentAnswer($faker->text(200));
            $answers->setQuestion($questionList[rand(0,29)]);
            $answers->setUser($user); 

            $manager->persist($answers);
        }

    $manager->flush();
    }

}