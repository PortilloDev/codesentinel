<?php

namespace App\Order\Domain\Model;


use App\Order\Domain\Event\OrderCreated;
use App\Shared\Domain\Contract\DomainEventInterface;

final class Order
{
    /** @var DomainEventInterface[] */
    private array $recordedEvents = [];

    private function __construct(
        private readonly OrderId $id,
        private readonly string $customerEmail,
        private readonly int $amountCents,
        private readonly string $currency,
    ) {}

    public static function place(
        OrderId $id,
        string $customerEmail,
        int $amountCents,
        string $currency,
    ): self {
        $order = new self($id, $customerEmail, $amountCents, $currency);
        $order->recordedEvents[] = new OrderCreated(
            $id->value,
            $customerEmail,
            $amountCents,
            $currency,
            new \DateTimeImmutable(),
        );
        return $order;
    }

    public function id(): OrderId { return $this->id; }

    /** @return DomainEventInterface[] */
    public function pullDomainEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }
}