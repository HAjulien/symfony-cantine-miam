<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



        /**
    * return all product per page 
    * @return void
    */
    public function getPaginatedProduit($page, $limit){
        $query = $this->createQueryBuilder('p')
        ->orderBy('p.createAt', 'DESC')
        ->setFirstResult(($page * $limit) - $limit)
        ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of Products
    * @return void
    */
    public function getTotalProduit()
    {
        $query = $this->createQueryBuilder('p')
        ->select('COUNT(p)')
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
    * return all product per page 
    * @return void
    */
    public function getPaginatedProduitsSemaine($page, $limit){
        $query = $this->createQueryBuilder('p')
        ->where('p.selectionner = 1')
        ->orderBy('p.category', 'ASC')
        ->setFirstResult(($page * $limit) - $limit)
        ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of Products
    * @return void
    */
    public function getTotalProduitsSemaine()
    {
        $query = $this->createQueryBuilder('p')
        ->select('COUNT(p)')
        ->where('p.selectionner = 1')
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }


    /**
    * recherche les produits en fonction du formulaire
    * @return void
    */
    public function search($mots = null, $category = null){
        $query = $this->createQueryBuilder('p');
        if($mots != null){
            // en reference au doctrine yaml match_against
            $query->andWhere('MATCH_AGAINST(p.nom, p.description) AGAINST(:mots boolean)>0')
                ->setParameter('mots', '*' . $mots . '*');
        }
        if($category != null){
            $query->leftJoin('p.category', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id', $category);
        }
        return $query->getQuery()->getResult();
    }



    /**
    * return all product filter per category per page 
    * @return void
    */
    public function getPaginatedProduitFiltre($page, $limit, $categorieFiltrer){
        $query = $this->createQueryBuilder('p');
        $query->leftJoin('p.category', 'c');
        $query->andWhere('c.id = :id')
        ->setParameter('id',  $categorieFiltrer)
        ->orderBy('p.createAt', 'DESC')
        ->setFirstResult(($page * $limit) - $limit)
        ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of Products filter per category
    * @return void
    */
    public function getTotalProduitFiltre($categorieFiltrer)
    {
        $query = $this->createQueryBuilder('p')
        ->select('COUNT(p)');
        $query->leftJoin('p.category', 'c');
        $query->andWhere('c.id = :id')
        ->setParameter('id',  $categorieFiltrer)
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }
//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
