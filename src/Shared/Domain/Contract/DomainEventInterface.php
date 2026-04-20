<?php

namespace App\Shared\Domain\Contract;


interface DomainEventInterface
{
    public function aggregateId(): string;
    public function eventName(): string;
    public function occurredOn(): \DateTimeImmutable;
    /** @return array<string, mixed> */
    public function toPrimitives(): array;
}