<?php

declare(strict_types=1);

namespace App\Tests\Unit\Module\User\Entity\User\ChangeEmail;

use App\Module\User\Entity\User\Email;
use App\Module\User\Entity\User\Token;
use App\Tests\Builder\User\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class RequestTest
 * @package App\Tests\Unit\Module\User\Entity\User\ChangeEmail
 */
class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $oldEmail = new Email('old-email@app.test');

        $user = (new UserBuilder())
            ->withEmail($oldEmail)
            ->active()
            ->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));
        $newEmail = new Email('new-email@app.test');

        $user->requestEmailChanging($token, $now, $newEmail);

        self::assertEquals($token, $user->getNewEmailToken());
        self::assertEquals($oldEmail, $user->getEmail());
        self::assertEquals($newEmail, $user->getNewEmail());
    }

    public function testSame(): void
    {
        $oldEmail = new Email('old-email@app.test');

        $user = (new UserBuilder())
            ->withEmail($oldEmail)
            ->active()
            ->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $this->expectExceptionMessage('Email is already same.');
        $user->requestEmailChanging($token, $now, $oldEmail);
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));
        $email = new Email('new-email@app.test');

        $user->requestEmailChanging($token, $now, $email);

        $this->expectExceptionMessage('Changing is already requested.');
        $user->requestEmailChanging($token, $now, $email);
    }

    public function testExpired(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));
        $user->requestEmailChanging($token, $now, new Email('temp-email@app.test'));

        $newDate = $now->modify('+2 hours');
        $newToken = $this->createToken($newDate->modify('+1 hour'));
        $newEmail = new Email('new-email@app.test');
        $user->requestEmailChanging($newToken, $newDate, $newEmail);

        self::assertEquals($newToken, $user->getNewEmailToken());
        self::assertEquals($newEmail, $user->getNewEmail());
    }

    public function testByCreate(): void
    {
        $user = (new UserBuilder())->viaCreate()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));
        $newEmail = new Email('new-email@app.test');

        $this->expectExceptionMessage('User does not have an old email.');

        $user->requestEmailChanging($token, $now, $newEmail);
    }

    public function testNotActive(): void
    {
        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user = (new UserBuilder())->build();

        $this->expectExceptionMessage('User is not active.');
        $user->requestEmailChanging($token, $now, new Email('new-email@app.test'));
    }

    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $date
        );
    }
}
