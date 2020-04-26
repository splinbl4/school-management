<?php
declare(strict_types=1);

namespace App\Module\User\Repository;

use App\Module\User\Entity\User\Email;
use App\Module\User\Entity\User\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Module\User\Repository
 */
interface UserRepositoryInterface
{
    public function hasByEmail(Email $email): bool;

    public function add(User $user): void;
}