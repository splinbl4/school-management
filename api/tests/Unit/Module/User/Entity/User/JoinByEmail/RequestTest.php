<?php
declare(strict_types=1);

namespace App\Tests\Unit\Module\User\Entity\User\JoinByEmail;

use App\Module\Company\Entity\Company\Company;
use App\Module\Company\Entity\Company\Id as CompanyId;
use App\Module\User\Entity\User\Email;
use App\Module\User\Entity\User\Id;
use App\Module\User\Entity\User\Name;
use App\Module\User\Entity\User\Role;
use App\Module\User\Entity\User\Token;
use App\Module\User\Entity\User\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class RequestTest
 * @package App\Tests\Unit\Module\User\Entity\User\JoinByEmail
 */
class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $company = new Company(CompanyId::generate());

        $user = User::joinByEmail(
            $id = Id::generate(),
            $date = new DateTimeImmutable(),
            $name = new Name('First', 'Last'),
            Role::owner(),
            $company,
            $email = new Email('mail@example.com'),
            $hash = 'hash',
            $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable())
        );

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($token, $user->getJoinConfirmToken());

        self::assertEquals(Role::OWNER, $user->getRole()->getName());
    }
}
