<?php
declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence\Doctrine\Type;

use App\Order\Domain\Model\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class OrderIdType extends GuidType
{
    public const NAME = 'order_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?OrderId
    {
        if ($value === null) {
            return null;
        }

        return new OrderId((string) $value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value instanceof OrderId ? $value->value : (string) $value;
    }
}
