<?php

namespace App\Repository;

use App\Entity\Critique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Critique>
 *
 * @method Critique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Critique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Critique[]    findAll()
 * @method Critique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CritiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Critique::class);
    }

    public function add(Critique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Critique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * return all critiques per page 
    * @return void
    */
    public function getPaginatedCritique($page, $limit){
        $query = $this->createQueryBuilder('c')
        //->where('a.isVerified = 1')
        ->orderBy('c.createAt', 'DESC')
        ->setFirstResult(($page * $limit) - $limit)
        ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of Features
    * @return void
    */
    public function getTotalCritique()
    {
        $query = $this->createQueryBuilder('c')
        ->select('COUNT(c)')
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }


//    /**
//     * @return Critique[] Returns an array of Critique objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Critique
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
