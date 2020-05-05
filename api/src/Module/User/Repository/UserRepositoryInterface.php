<?php

declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Module\User\Entity\User\Email;
use App\Module\User\Entity\User\Id;
use App\Module\User\Entity\User\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Module\User\Repository
 */
interface UserRepositoryInterface
{
    public function hasByEmail(Email $email): bool;

    public function findByJoinConfirmToken(string $token): ?User;

    public function getByEmail(Email $email): User;

    public function findByPasswordResetToken(string $token): ?User;

    public function get(Id $id): User;

    public function findByNewEmailToken(string $token): ?User;

    public function add(User $user): void;
}
