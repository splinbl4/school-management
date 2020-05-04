<?php
declare(strict_types=1);

namespace App\Module\User\Command\ChangePassword;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\Module\User\Command\ChangePassword
 */
class Command
{
    /**
     * @Assert\NotBlank()
     *
     * @Type("int")
     */
    public string $id = '';

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6, allowEmptyString=true)
     *
     * @Type("string")
     */
    public string $current = '';

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6, allowEmptyString=true)
     *
     * @Type("string")
     */
    public string $new = '';
}
