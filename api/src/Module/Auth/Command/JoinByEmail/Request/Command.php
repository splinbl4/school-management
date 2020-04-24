<?php
declare(strict_types=1);

namespace App\Module\Auth\Command\JoinByEmail\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Module\Auth\Command\JoinByEmail\Request
 */
class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public string $email = '';

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6, allowEmptyString=true)
     */
    public string $password = '';
}
