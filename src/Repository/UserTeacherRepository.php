<?php

namespace App\Repository;

use App\Entity\UserTeacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserTeacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTeacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTeacher[]    findAll()
 * @method UserTeacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTeacherRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserTeacher::class);
    }

    // /**
    //  * @return UserTeacher[] Returns an array of UserTeacher objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTeacher
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
