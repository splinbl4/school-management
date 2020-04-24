<?php
declare(strict_types=1);

namespace App\Module\Auth\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package App\Module\Auth\Entity\User
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="auth_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 * })
 */
class User
{
    /**
     * @ORM\Column(type="auth_user_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @ORM\Column(type="auth_user_email", unique=true)
     */
    private Email $email;

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
     * @ORM\Column(type="auth_user_status", length=16)
     */
    private Status $status;

    /**
     * @ORM\Column(type="auth_user_role", length=16)
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
     * @ORM\Column(type="auth_user_email", nullable=true)
     */
    private ?Email $newEmail = null;

    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $newEmailToken = null;

    private function __construct(Id $id, DateTimeImmutable $date, Email $email, Name $name, Status $status)
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->name = $name;
        $this->status = $status;
        $this->role = Role::user();
    }
}
