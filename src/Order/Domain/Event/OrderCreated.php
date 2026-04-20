<?php

namespace App\Order\Domain\Event;
use App\Order\Domain\Model\Order;
use App\Shared\Domain\Contract\DomainEventInterface;

final readonly class OrderCreated implements DomainEventInterface
{
    public function __construct(
        private string $orderId,
        private string $customerEmail,
        private int $amountCents,
        private string $currency,
        private \DateTimeImmutable $occurredOn,
    ) {}

    public function aggregateId(): string { return $this->orderId; }
    public function eventName(): string   { return 'ordering.order.created'; }
    public function occurredOn(): \DateTimeImmutable { return $this->occurredOn; }

    public function toPrimitives(): array
    {
        return [
            'orderId'       => $this->orderId,
            'customerEmail' => $this->customerEmail,
            'amountCents'   => $this->amountCents,
            'currency'      => $this->currency,
        ];
    }
}