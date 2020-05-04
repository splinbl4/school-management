<?php
declare(strict_types=1);

namespace App\Module\User\Command\ChangeEmail\Confirm;

use App\Module\Flusher;
use App\Module\User\Entity\User\User;
use App\Module\User\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use DomainException;

/**
 * Class Handler
 * @package App\Module\User\Command\ChangeEmail\Confirm
 */
class Handler
{
    private UserRepositoryInterface $userRepository;

    private Flusher $flusher;

    public function __construct(UserRepositoryInterface $userRepository, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->findByNewEmailToken($command->token);

        if (!$user instanceof User) {
            throw new DomainException('Incorrect token.');
        }

        $user->confirmEmailChanging($command->token, new DateTimeImmutable());

        $this->flusher->flush();
    }
}
