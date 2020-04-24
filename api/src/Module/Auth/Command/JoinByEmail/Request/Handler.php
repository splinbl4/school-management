<?php
declare(strict_types=1);

namespace App\Module\Auth\Command\JoinByEmail\Request;

use App\Module\Auth\Entity\User\Email;
use App\Module\Auth\Repository\UserRepositoryInterface;
use DomainException;

/**
 * Class Handler
 * @package App\Module\Auth\Command\JoinByEmail\Request
 */
class Handler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(Command $command)
    {
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new DomainException('User already exists.');
        }


    }
}