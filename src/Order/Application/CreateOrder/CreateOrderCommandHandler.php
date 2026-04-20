<?php
declare(strict_types=1);

namespace App\Order\Application\CreateOrder;

use App\Shared\Domain\Contract\DomainEventPublisher;
use App\Order\Domain\Model\Order;
use App\Order\Domain\Model\OrderId;
use App\Order\Domain\Contract\OrderRepositoryInterface as OrderRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateOrderCommandHandler
{
    public function __construct(
        private OrderRepository $repository,
        private DomainEventPublisher $eventPublisher,
    ) {}

    public function __invoke(CreateOrderCommand $command): OrderId
    {
        $order = Order::place(
            OrderId::generate(),
            $command->customerEmail,
            $command->amountCents,
            $command->currency,
        );

        $this->repository->save($order);
        $this->eventPublisher->publish($order->pullDomainEvents());

        return $order->id();
    }
}