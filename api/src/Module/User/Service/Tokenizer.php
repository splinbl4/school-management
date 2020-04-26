<?php
declare(strict_types=1);

namespace App\Module\User\Service;

use App\Module\User\Entity\User\Token;
use DateInterval;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

/**
 * Class Tokenizer
 * @package App\Module\User\Service
 */
class Tokenizer
{
    private DateInterval $interval;

    public function __construct(DateInterval $interval)
    {
        $this->interval = $interval;
    }

    public function generate(DateTimeImmutable $date)
    {
        return new Token(
            Uuid::uuid4()->toString(),
            $date->add($this->interval)
        );
    }
}
