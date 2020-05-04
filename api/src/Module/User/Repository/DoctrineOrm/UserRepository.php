<?php
declare(strict_types=1);

namespace App\Module\User\Repository\DoctrineOrm;

use App\Module\User\Entity\User\Email;
use App\Module\User\Entity\User\Id;
use App\Module\User\Entity\User\User;
use App\Module\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Laminas\EventManager\Exception\DomainException;

/**
 * Class UserRepository
 * @package App\Module\User\Repository
 */
class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * @param Email $email
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param string $token
     * @return User|object|null
     */
    public function findByJoinConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['joinConfirmToken.value' => $token]);
    }

    /**
     * @param Email $email
     * @return User
     */
    public function getByEmail(Email $email): User
    {
        $user = $this->repo->findOneBy(['email' => $email->getValue()]);

        if (!$user instanceof User) {
            throw new DomainException('User is not found.');
        }

        return $user;
    }

    /**
     * @param string $token
     * @return User|object|null
     */
    public function findByPasswordResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['passwordResetToken.value' => $token]);
    }

    /**
     * @param Id $id
     * @return User|object|null
     */
    public function get(Id $id): User
    {
        if (!$user = $this->repo->find($id->getValue())) {
            throw new DomainException('User is not found.');
        }

        return $user;
    }

    /**
     * @param string $token
     * @return User|object|null
     */
    public function findByNewEmailToken(string $token): ?User
    {
        return $this->repo->findOneBy(['newEmailToken.value' => $token]);
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }
}
