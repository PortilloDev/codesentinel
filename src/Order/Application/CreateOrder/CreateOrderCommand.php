<?php
declare(strict_types=1);

namespace App\Order\Application\CreateOrder;

final readonly class CreateOrderCommand
{
    public function __construct(
        public string $customerEmail,
        public int $amountCents,
        public string $currency,
    ) {}
}