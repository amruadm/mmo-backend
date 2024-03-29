<?php

namespace App\Repository;

use App\Entity\GameServer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameServer|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameServer|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameServer[]    findAll()
 * @method GameServer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameServerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameServer::class);
    }

    // /**
    //  * @return GameServer[] Returns an array of GameServer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameServer
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
