<?php
declare(strict_types=1);

namespace App\Order\Infrastructure\Controller;

use App\Order\Application\CreateOrder\CreateOrderCommand;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use App\Shared\Infrastructure\Symfony\Http\BadRequestResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class CreateOrderController extends AbstractApiController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route('/orders', name: 'orders_create', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        foreach (['customerEmail', 'amountCents', 'currency'] as $field) {
            if (!isset($data[$field])) {
                return $this->badRequest("Missing field: {$field}");
            }
        }

        $orderId = $this->handle(new CreateOrderCommand(
            customerEmail: (string) $data['customerEmail'],
            amountCents:   (int)    $data['amountCents'],
            currency:      (string) $data['currency'],
        ));

        return new JsonResponse(['orderId' => $orderId->value], 201);
    }
}