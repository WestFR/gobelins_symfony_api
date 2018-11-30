<?php

namespace App\Repository;

use App\Entity\ActionCustom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ActionCustom|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionCustom|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionCustom[]    findAll()
 * @method ActionCustom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionCustomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActionCustom::class);
    }

    // /**
    //  * @return ActionCustom[] Returns an array of ActionCustom objects
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
    public function findOneBySomeField($value): ?ActionCustom
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
