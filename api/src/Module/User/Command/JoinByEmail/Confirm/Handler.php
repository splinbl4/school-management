<?php
declare(strict_types=1);

namespace App\Module\User\Command\JoinByEmail\Confirm;

use App\Module\Flusher;
use App\Module\User\Entity\User\User;
use App\Module\User\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use DomainException;

/**
 * Class Handler
 * @package App\Module\User\Command\JoinByEmail\Confirm
 */
class Handler
{
    private UserRepositoryInterface $userRepository;

    private Flusher $flusher;

    /**
     * Handler constructor.
     * @param UserRepositoryInterface $userRepository
     * @param Flusher $flusher
     */
    public function __construct(UserRepositoryInterface $userRepository, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $user = $this->userRepository->findByJoinConfirmToken($command->token);
        if (!$user instanceof User) {
            throw new DomainException('Incorrect token.');
        }

        $user->confirmJoin($command->token, new DateTimeImmutable());

        $this->flusher->flush();
    }
}
