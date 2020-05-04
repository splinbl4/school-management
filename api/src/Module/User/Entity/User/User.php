<?php
declare(strict_types=1);

namespace App\Module\User\Entity\User;

use App\Module\Company\Entity\Company\Company;
use App\Module\User\Service\PasswordHasher;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * Class User
 * @package App\Module\User\Entity\User
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 * })
 */
class User
{
    /**
     * @ORM\Column(type="user_user_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @ORM\Column(type="user_user_email", unique=true)
     */
    private ?Email $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $passwordHash;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $date;

    /**
     * @ORM\Embedded(class="Name")
     */
    private Name $name;

    /**
     * @ORM\Column(type="user_user_status", length=16)
     */
    private Status $status;

    /**
     * @ORM\Column(type="user_user_role", length=16)
     */
    private Role $role;

    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $joinConfirmToken = null;

    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $passwordResetToken = null;

    /**
     * @ORM\Column(type="user_user_email", nullable=true)
     */
    private ?Email $newEmail = null;

    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $newEmailToken = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Module\Company\Entity\Company\Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     */
    private Company $company;

    private function __construct(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Role $role,
        Company $company
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->name = $name;
        $this->role = $role;
        $this->company = $company;
    }

    public static function joinByEmail(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Role $role,
        Company $company,
        Email $email,
        string $hash,
        Token $token
    ): self {
        $user = new self($id, $date, $name, $role, $company);
        $user->email = $email;
        $user->status = Status::wait();
        $user->passwordHash = $hash;
        $user->joinConfirmToken = $token;

        return $user;
    }

    public static function create(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Role $role,
        Company $company,
        Email $email = null
    ): self {
        $user = new self($id, $date, $name, $role, $company);
        $user->email = $email;
        $user->status = Status::active();

        return $user;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Email|null
     */
    public function getEmail(): ?Email
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return Token|null
     */
    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    /**
     * @return Token|null
     */
    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    /**
     * @return Email|null
     */
    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    /**
     * @return Token|null
     */
    public function getNewEmailToken(): ?Token
    {
        return $this->newEmailToken;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new DomainException('Confirmation is not required.');
        }

        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void
    {
        if ($this->joinConfirmToken && $this->joinConfirmToken->isEmpty()) {
            $this->joinConfirmToken = null;
        }
        if ($this->passwordResetToken && $this->passwordResetToken->isEmpty()) {
            $this->passwordResetToken = null;
        }
        if ($this->newEmailToken && $this->newEmailToken->isEmpty()) {
            $this->newEmailToken = null;
        }
    }

    public function requestPasswordReset(Token $token, DateTimeImmutable $date)
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }

        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting is already requested.');
        }

        $this->passwordResetToken = $token;
    }

    public function resetPassword(string $token, string $password, DateTimeImmutable $date, PasswordHasher $hasher): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('Resetting is not requested.');
        }
        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->passwordHash = $hasher->hash($password);
    }
}
