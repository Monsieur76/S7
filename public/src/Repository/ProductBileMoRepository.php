<?php

namespace App\Repository;

use App\Entity\ProductBileMo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductBileMo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductBileMo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductBileMo[]    findAll()
 * @method ProductBileMo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductBileMoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductBileMo::class);
    }

    // /**
    //  * @return ProductBileMo[] Returns an array of ProductBileMo objects
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
    public function findOneBySomeField($value): ?ProductBileMo
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
