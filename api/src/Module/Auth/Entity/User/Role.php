<?php
declare(strict_types=1);

namespace App\Module\Auth\Entity\User;

use Webmozart\Assert\Assert;

/**
 * Class Role
 * @package App\Module\Auth\Entity\User
 */
class Role
{
    public const USER = 'user';
    public const ADMIN = 'admin';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN,
        ]);

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
