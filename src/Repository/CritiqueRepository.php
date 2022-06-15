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
 * @method Critique[]    findBy(array $criteria, array $orderBy = null, $_ENV['LIMIT_PAGINATION_5'] = null, $offset = null)
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
    public function getPaginatedCritique($page){
        $query = $this->createQueryBuilder('c')
        //->where('a.isVerified = 1')
        ->orderBy('c.createAt', 'DESC')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of critique
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

    /**
    * return all critiques per page suivant filtrage
    * @return void
    */
    public function getPaginatedCritiqueFiltre($page, $filtrer, $tableJoint){
        $query = $this->createQueryBuilder('c');
        $query->leftJoin($tableJoint , 'u');
        $query->andWhere('u.id = :id')
        ->setParameter('id', $filtrer)
        ->orderBy('c.createAt', 'DESC')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return all critiques per CATEGORY 
    * @return void
    */
    public function getPaginatedCritiqueCategorie($page, $filtrer){
        $query = $this->createQueryBuilder('c')
        ->join('c.produit', 'p')
        ->andWhere('p.category = :id')
        ->setParameter('id', $filtrer)
        ->orderBy('c.createAt', 'DESC')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of critique par CATEGORY
    * @return void
    */
    public function getTotalCritiqueCategory($filtrer )
    {
        $query = $this->createQueryBuilder('c')
        ->select('COUNT(c)')
        ->join('c.produit', 'p')
        ->andWhere('p.category = :id')
        ->setParameter('id', $filtrer)
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
    * return number of critique 
    * @return void
    */
    public function getMaxCritique()
    {
        $query = $this->createQueryBuilder('c')
        ->select('COUNT(c) as maxCom, p.nom as nom , AVG(c.note) as note, p.id ')
        ->join('c.produit', 'p')
        ->groupBy('p.id')
        ->orderBy('maxCom', 'DESC')
        ;
        return $query->getQuery()->getResult();
    }


    /**
    * return number of critique
    * @return void
    */
    public function getTotalCritiqueFiltre($filtrer, $tableJoint)
    {
        $query = $this->createQueryBuilder('c')
        ->select('COUNT(c)');      
        $query->leftJoin($tableJoint, 'u');
        $query->andWhere('u.id = :id')
        ->setParameter('id', $filtrer)
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
    * return avg of produit note
    * @return void
    */
    public function avgNote($filtrer, $tableJoint)
    {
        $query = $this->createQueryBuilder('c');
        $query->leftJoin($tableJoint, 'u');
        $query->andWhere('u.id = :id')
        ->setParameter('id', $filtrer)
        ->select('avg(c.note)');      
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
