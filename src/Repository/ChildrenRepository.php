<?php

namespace App\Repository;

use App\Entity\Children;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Children|null find($id, $lockMode = null, $lockVersion = null)
 * @method Children|null findOneBy(array $criteria, array $orderBy = null)
 * @method Children[]    findAll()
 * @method Children[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChildrenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Children::class);
    }

    // /**
    //  * @return Children[] Returns an array of Children objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Children
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
