<?php
declare(strict_types=1);

namespace App\Tests\Unit\Module\User\Entity\User;

use App\Module\User\Service\PasswordHasher;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangePasswordTest
 * @package App\Tests\Unit\Module\User\Entity\User
 */
class ChangePasswordTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();
        $hash = 'new-hash';
        $hasher = $this->createHasher(true, $hash);

        $user->changePassword('old-password', 'new-password', $hasher);

        self::assertEquals($user->getPasswordHash(), $hash);
    }

    public function testWrongCurrent(): void 
    {
        $user = (new UserBuilder())->active()->build();
        $hash = 'new-hash';
        $hasher = $this->createHasher(false, $hash);

        $this->expectExceptionMessage('Incorrect current password.');

        $user->changePassword('wrong-old-password', 'new-password', $hasher);
    }

    private function createHasher(bool $valid, string $hash): PasswordHasher
    {
        $hasher = $this->createStub(PasswordHasher::class);
        $hasher->method('validate')->willReturn($valid);
        $hasher->method('hash')->willReturn($hash);
        return $hasher;
    }
}
