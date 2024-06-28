<?php

namespace App\Repository;

use App\Entity\UserExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserExercise>
 *
 * @method UserExercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserExercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserExercise[]    findAll()
 * @method UserExercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserExercise::class);
    }

    /**
     * Retrieve all user_exercise by user
     *
     * @return array
     */
    public function findAllUserExerciseByUser($id)
    {
        $sql = "SELECT user_exercise.*, exercise.title
                FROM `user_exercise`
                INNER JOIN `exercise`
                ON user_exercise.exercise_id = exercise.id
                WHERE user_exercise.user_id = $id";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return UserExercise[] Returns an array of UserExercise objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserExercise
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
