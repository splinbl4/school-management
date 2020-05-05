<?php

declare(strict_types=1);

namespace App\Module\User\Command\JoinByEmail\Confirm;

/**
 * Class Command
 * @package App\Module\User\Command\JoinByEmail\Confirm
 */
class Command
{
    public string $token = '';

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
