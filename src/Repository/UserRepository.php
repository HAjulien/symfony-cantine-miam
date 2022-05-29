<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
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

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }



    /**
    * return all users per page 
    * @return void
    */
    public function getPaginatedUsers($page, $limit){
        $query = $this->createQueryBuilder('a')
        //->where('a.isVerified = 1')
        ->orderBy('a.id', 'DESC')
        ->setFirstResult(($page * $limit) - $limit)
        ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of Users
    * @return void
    */
    public function getTotalUsers()
    {
        $query = $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        //-> where('a.isVerified = 1')
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }


    /**
    * return all users non verifie per page 
    * @return void
    */
    public function getPaginatedNonVerifie($page, $limit){
        $query = $this->createQueryBuilder('a')
        ->where('a.isVerified != 1')
        ->setFirstResult(($page * $limit) - $limit)
        ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of Users non verifie
    * @return void
    */
    public function getTotalUsersNonVerifie()
    {
        $query = $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        -> where('a.isVerified != 1')
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
    * return all personnel per page 
    * @return void
    */
    public function getPaginatedPersonnel($page, $limit){
        $query = $this->createQueryBuilder('a')
        ->where('a.roles LIKE :role ')
        ->orWhere('a.roles LIKE :role2 ')
        ->setParameter('role', '%"'.'ROLE_PERSONNEL'.'"%')
        ->setParameter('role2', '%"'.'ROLE_ADMIN'.'"%')
        ->orderBy('a.roles', 'DESC')
        ->setFirstResult(($page * $limit) - $limit)
        ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
    * return number of personnel
    * @return void
    */
    public function getTotalUsersPersonnel()
    {
        $query = $this->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->where('a.roles LIKE :role')
        ->orWhere('a.roles LIKE :role2 ')
        ->setParameter('role', '%"'.'ROLE_PERSONNEL'.'"%')
        ->setParameter('role2', '%"'.'ROLE_ADMIN'.'"%')
        ;
        //seulement chiffre décimaux texte, pas tableau getSingleScalarResult
        return $query->getQuery()->getSingleScalarResult();
    }







    /**
    * recherche les users en fonction du formulaire
    * @return void
    */
    public function search($mots){
        $query = $this->createQueryBuilder('u');
        if($mots != null){
            // en reference au doctrine yaml match_against
            $query->andWhere('MATCH_AGAINST(u.pseudo, u.identifiantAfpa) AGAINST(:mots boolean)>0')
                ->setParameter('mots', '*' . $mots . '*');
        }

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
