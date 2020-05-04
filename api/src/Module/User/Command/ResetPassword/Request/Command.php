<?php
declare(strict_types=1);

namespace App\Module\User\Command\ResetPassword\Request;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Module\User\Command\ResetPassword\Request
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
}
