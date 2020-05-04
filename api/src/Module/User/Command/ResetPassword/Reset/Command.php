<?php
declare(strict_types=1);

namespace App\Module\User\Command\ResetPassword\Reset;

/**
 * Class Command
 * @package App\Module\User\Command\ResetPassword\Reset
 */
class Command
{
    public string $token = '';
    public string $password = '';
}