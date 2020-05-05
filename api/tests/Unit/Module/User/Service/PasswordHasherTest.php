<?php

declare(strict_types=1);

namespace App\Tests\Unit\Module\User\Service;

use App\Module\User\Service\PasswordHasher;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class PasswordHasherTest
 * @package App\Tests\Unit\Module\User\Service
 */
class PasswordHasherTest extends TestCase
{
    public function testHash(): void
    {
        $hasher = new PasswordHasher(16);
        $password = 'new-password';

        $hash = $hasher->hash($password);

        self::assertNotEmpty($hash);
        self::assertNotEquals($password, $hash);
    }

    public function testHashEmpty(): void
    {
        $hasher = new PasswordHasher(16);

        $this->expectException(InvalidArgumentException::class);
        $hasher->hash('');
    }

    public function testValidate(): void
    {
        $hasher = new PasswordHasher(16);
        $password = 'new-password';

        $hash = $hasher->hash($password);

        self::assertTrue($hasher->validate($password, $hash));
        self::assertFalse($hasher->validate('wrong-password', $hash));
    }
}
