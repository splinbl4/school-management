<?php

declare(strict_types=1);

namespace App\Module\User\Command\JoinByEmail\Request;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Module\User\Command\JoinByEmail\Request
 */
class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @Type("string")
     */
    public string $email = '';

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6, allowEmptyString=true)
     *
     * @Type("string")
     */
    public string $password = '';

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2, allowEmptyString=true)
     *
     * @Type("string")
     */
    public string $firstName = '';

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2, allowEmptyString=true)
     *
     * @Type("string")
     */
    public string $lastName = '';
}
