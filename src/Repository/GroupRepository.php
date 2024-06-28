<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Group>
 *
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * Retrieve all classes in alphabetical order limited to 4
     *
     * @return array
     */
    public function findAllOrderByNameAsc()
    {
        return $this->createQueryBuilder('g')
        ->orderBy('g.name', 'ASC')
        ->setMaxResults(4)
        ->getQuery()
        ->getResult();
    }

    /**
     * Retrieve all classes in alphabetical
     *
     * @return array
     */
    public function findAllOrderByName()
    {
        return $this->createQueryBuilder('g')
           ->orderBy('g.name', 'ASC')
           ->getQuery()
           ->getResult()
       ;
    }

    /**
     * Retrieve all exercises by group
     *
     * @return array
     */
    public function findExercisesByGroup($id)
    {
        $sql = "SELECT `exercise`.*, group_exercise.status
        FROM `group_exercise`
        INNER JOIN `exercise`
        ON exercise.id = group_exercise.exercise_id
        INNER JOIN `group`
        ON group.id = group_exercise.group_id
        WHERE group.id = $id";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    /**
     * Retrieve all users by group
     *
     * @return array
     */
    public function findUsersByGroup($id)
    {
        $sql = "SELECT user.*
        FROM `group_user`
        INNER JOIN `user`
        ON user.id = group_user.user_id
        INNER JOIN `group`
        ON group.id = group_user.group_id
        WHERE group.id = $id";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }

    /**
     * Retrieve all groups by user
     *
     * @return array
     */
    public function findAllGroupByUser($id)
    {
        $sql = "SELECT *
                FROM `group_user`
                INNER JOIN `user`
                    ON user.id = group_user.user_id
                INNER JOIN `group`
                    ON group.id = group_user.group_id
                WHERE user.id = $id";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }


//    /**
//     * @return Group[] Returns an array of Group objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Group
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
