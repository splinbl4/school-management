<?php

declare(strict_types=1);

namespace App\Module\Company\Repository\DoctrineOrm;

use App\Module\Company\Entity\Company\Company;
use App\Module\Company\Repository\CompanyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class CompanyRepository
 * @package App\Module\Company\Repository\DoctrineOrm
 */
class CompanyRepository implements CompanyRepositoryInterface
{
    private EntityManagerInterface $em;

    /**
     * @var EntityRepository
     */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        /** @var EntityRepository $this->repo */
        $this->repo = $em->getRepository(Company::class);
    }

    public function add(Company $company): void
    {
        $this->em->persist($company);
    }
}
