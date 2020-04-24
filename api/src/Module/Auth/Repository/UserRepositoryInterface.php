<?php
declare(strict_types=1);

namespace App\Module\Auth\Repository;

use App\Module\Auth\Entity\User\Email;

/**
 * Interface UserRepositoryInterface
 * @package App\Module\Auth\Repository
 */
interface UserRepositoryInterface
{
    public function hasByEmail(Email $email): bool;
}