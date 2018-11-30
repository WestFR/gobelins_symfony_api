<?php

namespace App\Repository;

use App\Entity\UserParent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserParent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserParent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserParent[]    findAll()
 * @method UserParent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserParentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserParent::class);
    }

    // /**
    //  * @return UserParent[] Returns an array of UserParent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserParent
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
