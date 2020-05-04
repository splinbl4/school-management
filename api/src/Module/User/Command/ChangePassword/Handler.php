<?php
declare(strict_types=1);

namespace App\Module\User\Command\ChangePassword;

use App\Module\Flusher;
use App\Module\User\Entity\User\Id;
use App\Module\User\Repository\UserRepositoryInterface;
use App\Module\User\Service\PasswordHasher;

/**
 * Class Handler
 * @package App\Module\User\Command\ChangePassword
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

    public function handle(Command $command): void
    {
        $user = $this->userRepository->get(new Id($command->id));

        $user->changePassword(
            $command->current,
            $command->new,
            $this->passwordHasher
        );

        $this->flusher->flush();
    }
}
