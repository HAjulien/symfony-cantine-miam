<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $_ENV['LIMIT_PAGINATION_5'] = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry,
    private UserPasswordHasherInterface $userPasswordHasher,
    private EntityManagerInterface $entityManager
    )
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

    public function create($data){
        $user = new User();
        $user->setEmail($data->email);
        $user->setPseudo($data->pseudo);
        $user->setIdentifiantAfpa($data->identifiantAfpa);
        $user->setTelephone($data->telephone);
        $user->setPointFidelite(0);

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $data->password
        );
        $user->setPassword($hashedPassword);
        // $password = $this->userPasswordHasher->hashPassword($data->password,$user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;

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
    public function getPaginatedUsers($page){
        $query = $this->createQueryBuilder('a')
        //->where('a.isVerified = 1')
        ->orderBy('a.id', 'DESC')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
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
    public function getPaginatedNonVerifie($page){
        $query = $this->createQueryBuilder('a')
        ->where('a.isVerified != 1')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
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
    public function getPaginatedPersonnel($page){
        $query = $this->createQueryBuilder('a')
        ->where('a.roles LIKE :role ')
        ->orWhere('a.roles LIKE :role2 ')
        ->setParameter('role', '%"'.'ROLE_PERSONNEL'.'"%')
        ->setParameter('role2', '%"'.'ROLE_ADMIN'.'"%')
        ->orderBy('a.roles', 'DESC')
        ->setFirstResult(($page * $_ENV['LIMIT_PAGINATION_5']) - $_ENV['LIMIT_PAGINATION_5'])
        ->setMaxResults($_ENV['LIMIT_PAGINATION_5'])
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
