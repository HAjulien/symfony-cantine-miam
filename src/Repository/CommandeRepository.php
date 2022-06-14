<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function add(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * return all commandes
    * @return void
    */
    public function getPaginatedCommande($page){
        $query = $this->createQueryBuilder('c')
        ->orderBy('c.date', 'DESC')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return all commandes
    * @return void
    */
    public function getCommandeRecente(){
        $query = $this->createQueryBuilder('c')
        ->orderBy('c.date', 'DESC')
        ->setMaxResults(10)
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of commande
    * @return void
    */
    public function getTotalCommande()
    {
        $query = $this->createQueryBuilder('c')
        ->select('COUNT(c)')
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
    * return all critiques per page 
    * @return void
    */
    public function getPaginatedCommandeFiltre($page, $filtrer){
        $query = $this->createQueryBuilder('c');
        $query->leftJoin('c.utilisateur' , 'u');
        $query->andWhere('u.id = :id')
        ->setParameter('id', $filtrer)
        ->orderBy('c.date', 'DESC')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of commande
    * @return void
    */
    public function getTotalCommandeFiltre($filtrer)
    {
        $query = $this->createQueryBuilder('c')
        ->select('COUNT(c)');      
        $query->leftJoin('c.utilisateur', 'u');
        $query->andWhere('u.id = :id')
        ->setParameter('id', $filtrer)
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Commande[] Returns an array of Commande objects
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

//    public function findOneBySomeField($value): ?Commande
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
