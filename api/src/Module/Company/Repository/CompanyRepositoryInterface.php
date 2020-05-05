<?php

declare(strict_types=1);

namespace App\Module\Company\Repository;

use App\Module\Company\Entity\Company\Company;

/**
 * Interface CompanyRepositoryInterface
 * @package App\Module\Company\Repository
 */
interface CompanyRepositoryInterface
{
    public function add(Company $user): void;
}
