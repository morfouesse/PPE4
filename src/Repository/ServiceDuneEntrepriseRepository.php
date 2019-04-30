<?php

namespace App\Repository;

use App\Entity\ServiceDuneEntreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ServiceDuneEntreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceDuneEntreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceDuneEntreprise[]    findAll()
 * @method ServiceDuneEntreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceDuneEntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ServiceDuneEntreprise::class);
    }

    // /**
    //  * @return ServiceDuneEntreprise[] Returns an array of ServiceDuneEntreprise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ServiceDuneEntreprise
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
