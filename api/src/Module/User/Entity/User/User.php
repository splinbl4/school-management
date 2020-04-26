<?php
declare(strict_types=1);

namespace App\Module\User\Entity\User;

use App\Module\Company\Entity\Company\Company;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

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
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
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
}
