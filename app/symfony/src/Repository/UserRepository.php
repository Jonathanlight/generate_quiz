<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
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

    public function getUserByRole($role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.role LIKE :role')
            ->setParameter('role', "%".$role."%")
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param array|null $filters
     * @param null|string $role
     * @return array
     */
    public function search(?array $filters,?string $role): array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        if (isset($role)) {
            $queryBuilder->andWhere('u.role = :role');
            $queryBuilder->setParameter('role', $role);
        }

        $queryBuilder->andWhere('u.deleted IS NULL');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $username
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('a')
            ->where('a.username = :username')
            ->orWhere('a.username = :username')
            ->setParameter('username', $username)
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return mixed
     */
    public function findUserByRole()
    {
        return $this->createQueryBuilder('a')
            //->select('COUNT(a) as sumUser')
            ->where('a.role LIKE :role')
            ->setParameter('role', '%"ROLE_USER"%')
            ->orderBy('a.created', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function findByEmail(string $email)
    {
        return $this->createQueryBuilder('a')
            ->where('a.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string $role
     * @return mixed
     */
    public function findByRole(string $role)
    {
        return $this->createQueryBuilder('a')
            ->where('a.role = :role')
            ->orderBy('a.created', 'DESC')
            ->setParameter('role', $role)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string $role
     * @param int $valide
     * @return mixed
     */
    public function findByRoleValid(string $role, int $valide)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id) as value')
            ->andWhere('a.role = :role')
            ->andWhere('a.validated = :validated')
            ->setParameter('role', $role)
            ->setParameter('validated', $valide)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return mixed
     */
    public function countAllUser()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('COUNT(a.id) as value');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $role
     * @param int $valide
     * @return mixed
     */
    public function countAllRole(string $role, int $valide)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('COUNT(a.id) as value');

        $queryBuilder->andWhere('a.role = :role');
        $queryBuilder->setParameter('role', $role);

        if (User::PROFIL_NULL !== $valide) {
            $queryBuilder->andWhere('a.validated = :validated');
            $queryBuilder->setParameter('validated', $valide);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $gender
     * @param int $valide
     * @return mixed
     */
    public function countAllGender(string $gender, int $valide)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('COUNT(a.id) as value');

        $queryBuilder->andWhere('a.genre = :gender');
        $queryBuilder->setParameter('gender', $gender);

        if (User::PROFIL_NULL !== $valide) {
            $queryBuilder->andWhere('a.validated = :validated');
            $queryBuilder->setParameter('validated', $valide);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $passwordReset
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByPasswordReset(string $passwordReset)
    {
        return $this->createQueryBuilder('a')
            ->where('a.passwordReset = :passwordReset')
            ->setParameter('passwordReset', $passwordReset)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
