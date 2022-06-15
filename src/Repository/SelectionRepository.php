<?php

namespace App\Repository;

use App\Entity\Selection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Selection>
 *
 * @method Selection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Selection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Selection[]    findAll()
 * @method Selection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SelectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Selection::class);
    }

    public function add(Selection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Selection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * return marge pour aujourd'hui ...
    * @return void
    */
    public function getMargeJour($today2){
        $query = $this->createQueryBuilder('s')
        ->select( 'SUM(s.prix * s.quantite) as somme ')
        ->join('s.commande', 'c')
        ->where("c.id = s.commande")
        ->andWhere( 'SUBSTRING(c.date, 3, 8) = :today')
        ->setParameter('today', $today2)
        ;
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
    * return marge pour le mois ...
    * @return void
    */
    public function getMargeMois($mois){
        $query = $this->createQueryBuilder('s')
        ->select( 'SUM(s.prix * s.quantite) as somme ')
        ->join('s.commande', 'c')
        ->where("c.id = s.commande")
        ->andWhere( 'SUBSTRING(c.date, 3, 5) = :mois')
        ->setParameter('mois', $mois)
        ;
        return $query->getQuery()->getSingleScalarResult();
    }
    

//    /**
//     * @return Selection[] Returns an array of Selection objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Selection
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
