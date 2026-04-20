<?php
declare(strict_types=1);

namespace App\Order\Domain\Model;

use Ramsey\Uuid\Uuid;

final readonly class OrderId
{
    public function __construct(public string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException("Invalid OrderId: {$value}");
        }
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}