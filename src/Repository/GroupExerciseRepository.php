<?php

namespace App\Repository;

use App\Entity\GroupExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupExercise>
 *
 * @method GroupExercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupExercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupExercise[]    findAll()
 * @method GroupExercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupExercise::class);
    }

    /**
     * Retrieve all groupExercise by user
     *
     * @return array
     */
    public function findGroupExerciseByUser($id)
    {
        $sql = "SELECT *
        FROM `exercise`
        INNER JOIN `group_exercise`
        ON exercise.id = group_exercise.exercise_id
        INNER JOIN `user_exercise`
        ON exercise.id = user_exercise.exercise_id
        WHERE user_exercise.user_id = $id";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return GroupExercise[] Returns an array of GroupExercise objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GroupExercise
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
