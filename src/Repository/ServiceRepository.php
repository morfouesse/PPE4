<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * @return Service[] Returns an array of Service objects
     */
    
    public function lesServicesLesPlusUtilise()
    {
        $qb= $this->createQueryBuilder('s')
            ->innerJoin('s.serviceDuUsers','c')
            ->addSelect('c')
            ->orderBy('c.nbDroitUtiliser', 'DESC')
            ->select('s.nom,c.nbDroitUtiliser')
            ->getQuery();
        return $qb->execute()
        ;
    }
  
    

    /*
    public function findOneBySomeField($value): ?Service
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
