<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Contract\DomainEventInterface;
use App\Shared\Domain\Contract\DomainEventPublisher;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class SymfonyDomainEventPublisher implements DomainEventPublisher
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    /** @param DomainEventInterface[] $events */
    public function publish(array $events): void
    {
        foreach ($events as $event) {
            // Dispatch the domain event object using its event name
            $this->dispatcher->dispatch($event, $event->eventName());
        }
    }
}
