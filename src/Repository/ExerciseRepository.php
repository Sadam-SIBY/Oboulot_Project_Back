<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercise>
 *
 * @method Exercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exercise[]    findAll()
 * @method Exercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    /**
     * Retrieve all exercises in alphabetical order limited to 5
     *
     * @return array
     */
    public function findAllOrderByTitleAsc()
    {
        return $this->createQueryBuilder('e')
        ->orderBy('e.title', 'ASC')
        ->setMaxResults(4)
        ->getQuery()
        ->getResult();
    }

    /**
     * Retrieve all exercises in created date order
     *
     * @return array
     */
    public function findAllOrderCreatedDateDesc()
    {
        return $this->createQueryBuilder('e')
        ->orderBy('e.createdAt', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }

    /**
     * Retrieve all groups by exercise
     *
     * @return array
     */
    public function findGroupByExercise($id)
    {
        $sql = "SELECT `group`.*
        FROM `group_exercise`
        INNER JOIN `group`
        ON group.id = group_exercise.group_id
        INNER JOIN `exercise`
        ON exercise.id = group_exercise.exercise_id
        WHERE exercise.id = $id";
        
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }
    
    /**
     * Retrieve all questions by exercise
     *
     * @return array
     */
    public function findQuestionByExercise($id)
    {
        $sql = "SELECT `question`.*
        FROM `question`
        INNER JOIN `exercise`
        ON exercise.id = question.exercise_id
        WHERE exercise.id = $id
        ORDER BY question.number ASC";
        
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    /**
     * Retrieve all users by exercise
     *
     * @return array
     */
    public function findUsersByExercise($id)
    {
        $sql = "SELECT user.*
        FROM `user_exercise`
        INNER JOIN `user`
        ON user.id = user_exercise.user_id
        INNER JOIN `exercise`
        ON exercise.id = user_exercise.exercise_id
        WHERE exercise.id = $id";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    /**
     * Retrieve all exercises by user
     *
     * @return array
     */
    public function findAllExerciseByUser($id)
    {
        $sql = "SELECT exercise.*
                FROM `user_exercise`
                INNER JOIN `user`
                    ON user.id = user_exercise.user_id
                INNER JOIN `exercise`
                    ON exercise.id = user_exercise.exercise_id
                WHERE user.id = $id";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    /**
     * Retrieve the last id of an exercise
     *
     * @return array
     */
    public function findLastId()
    {
        return $this->createQueryBuilder('e')
        ->orderBy('e.id', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getResult();
    }

    /**
     * Retrieve all answers by user and by exercise
     *
     * @return array
     */
    public function findAnswerByExerciseAndByUser($exerciseId, $userId)
    {
        $sql = "SELECT answer.*, user_exercise.is_done
                FROM `user`
                INNER JOIN `user_exercise`
                ON user.id = user_exercise.user_id
                INNER JOIN `answer`
                ON user.id = answer.user_id
                WHERE user_exercise.exercise_id = $exerciseId AND user.id = $userId";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return Exercise[] Returns an array of Exercise objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Exercise
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
