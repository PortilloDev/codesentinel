<?php
declare(strict_types=1);

namespace App\Shared\Domain\Contract;

use App\Shared\Domain\Contract\DomainEventInterface;

interface DomainEventPublisher
{
    /** @param DomainEventInterface[] $events */
    public function publish(array $events): void;
}