<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    // /**
    //  * @return Chat[] Returns an array of Chat objects
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

   
    public function findChatHistory($user1, $user2)
    {
        $query =  $this->createQueryBuilder('c')
            ->andWhere('c.mfrom = :mfrom1 and c.mto = :mto1 ')
            ->orWhere('c.mfrom = :mfrom2 and c.mto = :mto2 ')
            ->setParameter('mfrom1', $user1)
            ->setParameter('mto1', $user2)
            ->setParameter('mfrom2', $user2)
            ->setParameter('mto2', $user1)
            ->orderBy('c.createdAt', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
 
        return $query;
    }
   
}
