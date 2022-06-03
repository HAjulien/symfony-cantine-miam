<?php

namespace App\Repository;

use App\Entity\ImageCarousel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageCarousel>
 *
 * @method ImageCarousel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageCarousel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageCarousel[]    findAll()
 * @method ImageCarousel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageCarouselRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageCarousel::class);
    }

    public function add(ImageCarousel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ImageCarousel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * return all image per page 
    * @return void
    */
    public function getPaginatedImage($page){
        $query = $this->createQueryBuilder('i')
        //->where('a.isVerified = 1')
        ->orderBy('i.id', 'DESC')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of Features
    * @return void
    */
    public function getTotalImage()
    {
        $query = $this->createQueryBuilder('i')
        ->select('COUNT(i)')
        //-> where('a.isVerified = 1')
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return ImageCarousel[] Returns an array of ImageCarousel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImageCarousel
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
