<?php

declare(strict_types=1);

namespace App\Module\User\Command\ResetPassword\Reset;

use App\Module\Flusher;
use App\Module\User\Entity\User\User;
use App\Module\User\Repository\UserRepositoryInterface;
use App\Module\User\Service\PasswordHasher;
use DateTimeImmutable;
use DomainException;

/**
 * Class Handler
 * @package App\Module\User\Command\ResetPassword\Reset
 */
class Handler
{
    private UserRepositoryInterface $userRepository;

    private PasswordHasher $passwordHasher;

    private Flusher $flusher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHasher $passwordHasher,
        Flusher $flusher
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->flusher = $flusher;
    }

    public function handler(Command $command): void
    {
        $user = $this->userRepository->findByPasswordResetToken($command->token);
        if (!$user instanceof User) {
            throw new DomainException('Token is not found.');
        }

        $user->resetPassword(
            $command->token,
            $command->password,
            new DateTimeImmutable(),
            $this->passwordHasher
        );

        $this->flusher->flush();
    }
}
