<?php
declare(strict_types=1);

namespace App\Module\Company\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Company
 * @package App\Module\Company\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="company_companies")
 */
class Company
{
    /**
     * @ORM\Column(type="company_company_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name = '';

    /**
     * @ORM\Column(type="company_company_status", length=16)
     */
    private Status $status;

    public function __construct(Id $id)
    {
        $this->id = $id;
        $this->status = Status::wait();
    }

    public function activate(): void
    {
        $this->status = Status::active();
    }
}
