<?php
declare(strict_types=1);

namespace App\Module\User\Command\ChangeEmail\Request;

/**
 * Class Command
 * @package App\Module\User\Command\ChangeEmail\Request
 */
class Command
{
    public string $id = '';
    public string $email = '';
}