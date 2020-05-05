<?php

declare(strict_types=1);

namespace App\Tests\Unit\Module\User\Entity\User\JoinByEmail;

use App\Module\User\Entity\User\Token;
use App\Tests\Builder\User\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class ConfirmTest
 * @package App\Tests\Unit\Module\User\Entity\User\JoinByEmail
 */
class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $token = $this->createToken();

        $user = (new UserBuilder())
            ->withJoinConfirmToken($token)
            ->build();

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        $user->confirmJoin($token->getValue(), $token->getExpires()->modify('-1 day'));

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());
        self::assertNull($user->getJoinConfirmToken());
    }

    public function testWrong(): void
    {
        $token = $this->createToken();

        $user = (new UserBuilder())
            ->withJoinConfirmToken($token)
            ->build();

        $this->expectExceptionMessage('Token is invalid.');

        $user->confirmJoin(
            Uuid::uuid4()->toString(),
            $token->getExpires()->modify('-1 day')
        );
    }

    public function testExpired(): void
    {
        $token = $this->createToken();

        $user = (new UserBuilder())
            ->withJoinConfirmToken($token)
            ->build();

        $this->expectExceptionMessage('Token is expired.');

        $user->confirmJoin(
            $token->getValue(),
            $token->getExpires()->modify('+1 day')
        );
    }

    public function testAlready(): void
    {
        $token = $this->createToken();

        $user = (new UserBuilder())
            ->withJoinConfirmToken($token)
            ->active()
            ->build();

        $this->expectExceptionMessage('Confirmation is not required.');

        $user->confirmJoin(
            $token->getValue(),
            $token->getExpires()->modify('+1 day')
        );
    }

    private function createToken(): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable('+1 day')
        );
    }
}
