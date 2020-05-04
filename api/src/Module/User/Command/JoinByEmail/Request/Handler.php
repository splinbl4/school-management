<?php
declare(strict_types=1);

namespace App\Module\User\Command\JoinByEmail\Request;

use App\Module\User\Entity\User\Email;
use App\Module\User\Entity\User\Id;
use App\Module\User\Entity\User\Name;
use App\Module\User\Entity\User\Role;
use App\Module\User\Entity\User\User;
use App\Module\User\Repository\UserRepositoryInterface;
use App\Module\User\Service\JoinConfirmationSender;
use App\Module\User\Service\PasswordHasher;
use App\Module\User\Service\Tokenizer;
use App\Module\Company\Entity\Company\Company;
use App\Module\Company\Entity\Company\Id as IdCompany;
use App\Module\Company\Repository\CompanyRepositoryInterface;
use App\Module\Flusher;
use DateTimeImmutable;
use DomainException;

/**
 * Class Handler
 * @package App\Module\User\Command\JoinByEmail\Request
 */
class Handler
{
    private UserRepositoryInterface $userRepository;

    private CompanyRepositoryInterface $companyRepository;

    private Flusher $flusher;

    private Tokenizer $tokenizer;

    private PasswordHasher $passwordHasher;
    /**
     * @var JoinConfirmationSender
     */
    private JoinConfirmationSender $sender;

    /**
     * Handler constructor.
     * @param UserRepositoryInterface $userRepository
     * @param CompanyRepositoryInterface $companyRepository
     * @param Flusher $flusher
     * @param Tokenizer $tokenizer
     * @param PasswordHasher $passwordHasher
     * @param JoinConfirmationSender $sender
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        CompanyRepositoryInterface $companyRepository,
        Flusher $flusher,
        Tokenizer $tokenizer,
        PasswordHasher $passwordHasher,
        JoinConfirmationSender $sender
    ) {
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
        $this->flusher = $flusher;
        $this->tokenizer = $tokenizer;
        $this->passwordHasher = $passwordHasher;
        $this->sender = $sender;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new DomainException('User already exists.');
        }

        $date = new DateTimeImmutable();
        $company = new Company(IdCompany::generate());
        $token = $this->tokenizer->generate($date);

        $user = User::joinByEmail(
            Id::generate(),
            $date,
            new Name($command->firstName, $command->lastName),
            Role::owner(),
            $company,
            new Email($command->email),
            $this->passwordHasher->hash($command->password),
            $token
        );

        $this->companyRepository->add($company);
        $this->userRepository->add($user);
        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
