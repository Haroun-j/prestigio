<?php

namespace App\Repository;

use App\Entity\FeaturedProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeaturedProduct>
 *
 * @method FeaturedProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeaturedProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeaturedProduct[]    findAll()
 * @method FeaturedProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeaturedProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeaturedProduct::class);
    }

//    /**
//     * @return FeaturedProduct[] Returns an array of FeaturedProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FeaturedProduct
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
