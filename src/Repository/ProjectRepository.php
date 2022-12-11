<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Project $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Project $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function counterAll() 
    {
        return $this->createQueryBuilder('h')
        ->select('count(h.id) AS TOTAL')
        ->getQuery()
        ->getOneOrNullResult();
    }
       
    public function findOneBySomeField($value)
    {
        return $this->createQueryBuilder('u')
            ->select('u.id as TOTAL')
            ->andWhere('u.id in (:id)')
            ->setParameter('id', $value)
            ->getQuery()
            ->getArrayResult()
        ;
    }
   
    public function findNames($value)
    {
        return $this->createQueryBuilder('u')
            ->select('u.nameproject as TOTAL')
            ->andWhere('u.id in (:id)')
            ->setParameter('id', $value)
            ->orderBy('u.id')
            ->getQuery()
            ->getArrayResult()
        ;
    }
    public function findID($value)
    {
        return $this->createQueryBuilder('u')
            ->select('u.nameproject')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function counter() 
    {
        $time = new \DateTime();
        

         return $this->createQueryBuilder('h')
        ->select('count(h.id) AS TOTAL')
        ->andWhere('h.enddate < :val')
        ->setParameter('val', $time->format('Y-m-d'))
        ->getQuery()
        ->getOneOrNullResult();
    }
    
    public function counterValid() 
    {
        $time = new \DateTime();
        

         return $this->createQueryBuilder('h')
        ->select('count(h.id) AS TOTAL')
        ->andWhere('h.enddate >= :val')
        ->setParameter('val', $time->format('Y-m-d'))
        ->getQuery()
        ->getOneOrNullResult();
    }
    public function counterTotal()
    {
        return $this->createQueryBuilder('h')
        ->select('count(h.id) AS TOTAL')
        ->getQuery()
        ->getOneOrNullResult();
    }
}
