<?php

namespace App\Order\Domain\Contract;

use App\Order\Domain\Model\Order;
use App\Order\Domain\Model\OrderId;


interface OrderRepositoryInterface
{
    public function save(Order $order): void;
    public function findById(OrderId $id): ?Order;
}