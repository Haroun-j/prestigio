<?php

namespace App\Repository;

use App\Entity\AvailableSize;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvailableSize>
 *
 * @method AvailableSize|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvailableSize|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvailableSize[]    findAll()
 * @method AvailableSize[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvailableSizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvailableSize::class);
    }

//    /**
//     * @return AvailableSize[] Returns an array of AvailableSize objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AvailableSize
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
