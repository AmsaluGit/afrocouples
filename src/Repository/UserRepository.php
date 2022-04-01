<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function filterUser($username)
    {
        return  $this->createQueryBuilder('u')
            ->Where('u.username LIKE :username')
            ->setParameter('username', '%'.$username.'%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFilteredData($name, $sex, $religion, $age, $start, $end)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u.fname','u.profileImage', 'c.name as city', 'n.name as nationality','u.username','r.id as religion', 'r.name as religionName', "DATE_FORMAT(u.birthdate, '%Y-%m-%d') as dob")
           ->join('u.religion', 'r')
           ->join('u.city', 'c')
           ->join('u.nationality', 'n');
            
            if(sizeof($age)>0){
                $totalRange = sizeof($age);
                for($i=0; $i<$totalRange; $i++){
                    if($i%2==0)
                    {
                        $minYear = $age[$i];
                        $maxYear = $age[$i+1];
                        // $qb->orWhere("(DATE_FORMAT(u.birthdate, '%Y-%m-%d') <= $minYear and DATE_FORMAT(u.birthdate, '%Y-%m-%d') >= $maxYear)");
                        $qb->orWhere("u.birthdate between '$maxYear' and '$minYear'");
                    }
                }
            }

            // dd($qb->getDQL());

            if(sizeof($sex)>0){
                $qb->andWhere('u.sex in (:sex)');
                $qb->setParameter('sex', $sex);
                
            }

            if(sizeof($religion)>0){
                $qb->andWhere('r.id in (:religion)');
                $qb->setParameter('religion', $religion);
            }

            // $qb->orderBy('u.id', 'ASC')
            $qb->setMaxResults($end)
            ->setFirstResult($start)
                        // dd($qb->getDQL());

        ;
        return $qb->getQuery();
    }
    
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
