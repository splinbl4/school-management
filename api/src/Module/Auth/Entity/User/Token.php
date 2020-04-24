<?php
declare(strict_types=1);

namespace App\Module\Auth\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use DomainException;
use Webmozart\Assert\Assert;


/**
 * Class Token
 * @package App\Module\Auth\Entity\User
 *
 * @ORM\Embeddable
 */
class Token
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $value;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private DateTimeImmutable $expires;

    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($value)) {
            throw new DomainException('Token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new DomainException('Token is expired.');
        }
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}
