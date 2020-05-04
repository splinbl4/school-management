<?php
declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\Entity\User\Email;
use App\Module\User\Entity\User\Token;

/**
 * Interface NewEmailConfirmTokenSender
 * @package App\Module\User\Service
 */
interface NewEmailConfirmTokenSender
{
    public function send(Email $email, Token $token): void;
}