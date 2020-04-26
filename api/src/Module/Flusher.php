<?php
declare(strict_types=1);

namespace App\Module;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Flusher
 * @package App\Module
 */
class Flusher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function flush()
    {
        $this->entityManager->flush();
    }
}