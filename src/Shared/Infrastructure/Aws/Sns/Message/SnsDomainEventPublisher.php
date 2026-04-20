<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Aws\Sns\Message;

use App\Shared\Domain\Contract\DomainEventPublisher;
use AsyncAws\Sns\SnsClient;


final readonly class SnsDomainEventPublisher implements DomainEventPublisher
{
    public function __construct(
        private SnsClient $snsClient,
        private string $topicArn,
    ) {}

    public function publish(array $events): void
    {
        foreach ($events as $event) {
            $this->snsClient->publish([
                'TopicArn' => $this->topicArn,
                'Message'  => json_encode([
                    'eventId'     => bin2hex(random_bytes(16)),
                    'eventName'   => $event->eventName(),
                    'aggregateId' => $event->aggregateId(),
                    'occurredOn'  => $event->occurredOn()->format(DATE_ATOM),
                    'payload'     => $event->toPrimitives(),
                ], JSON_THROW_ON_ERROR),
                'MessageAttributes' => [
                    'eventName' => [
                        'DataType'    => 'String',
                        'StringValue' => $event->eventName(),
                    ],
                ],
            ]);
        }
    }
}