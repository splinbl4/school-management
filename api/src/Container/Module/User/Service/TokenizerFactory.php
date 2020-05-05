<?php

declare(strict_types=1);

namespace App\Container\Module\User\Service;

use App\Module\User\Service\Tokenizer;
use DateInterval;
use Exception;

/**
 * Class ResetTokenizerFactory
 * @package App\Container\Module\User\Service
 */
class TokenizerFactory
{
    /**
     * @param string $interval
     * @return Tokenizer
     * @throws Exception
     */
    public function create(string $interval): Tokenizer
    {
        return new Tokenizer(new DateInterval($interval));
    }
}
