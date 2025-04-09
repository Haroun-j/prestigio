<?php

namespace App\Repository;

use App\Entity\AdditionalProductImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdditionalProductImage>
 *
 * @method AdditionalProductImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdditionalProductImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdditionalProductImage[]    findAll()
 * @method AdditionalProductImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdditionalProductImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdditionalProductImage::class);
    }

//    /**
//     * @return AdditionalProductImage[] Returns an array of AdditionalProductImage objects
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

//    public function findOneBySomeField($value): ?AdditionalProductImage
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
