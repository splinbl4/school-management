<?php

declare(strict_types=1);

namespace App\Module\User\Entity\User;

use Webmozart\Assert\Assert;

/**
 * Class Email
 * @package App\Module\User\Entity\User
 */
class Email
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::email($value);

        $this->value = mb_strtolower($value);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param Email $other
     * @return bool
     */
    public function isEqualTo(self $other): bool
    {
        return $this->value === $other->value;
    }
}
