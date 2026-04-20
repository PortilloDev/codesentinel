<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Contract\DomainEventInterface;
use App\Shared\Domain\Contract\DomainEventPublisher;
use Psr\Log\LoggerInterface;

final class InMemoryDomainEventPublisher implements DomainEventPublisher
{
    public function __construct(private ?LoggerInterface $logger = null)
    {
    }

    /** @param DomainEventInterface[] $events */
    public function publish(array $events): void
    {
        foreach ($events as $event) {
            if ($event instanceof DomainEventInterface) {
                $this->logger?->info('Published domain event', [
                    'event' => $event->eventName(),
                    'aggregateId' => $event->aggregateId(),
                ]);
            }
        }
    }
}
