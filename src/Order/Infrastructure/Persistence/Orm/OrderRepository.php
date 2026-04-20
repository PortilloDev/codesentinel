<?php

namespace App\Order\Infrastructure\Persistence\Orm;

use App\Order\Domain\Contract\OrderRepositoryInterface;
use App\Order\Domain\Model\Order;
use App\Order\Domain\Model\OrderId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findById(OrderId $id): ?Order
    {
        return $this->find($id->value);
    }

    public function save(Order $order): void
    {
        $em = $this->getEntityManager();
        $em->persist($order);
        $em->flush();
    }

}