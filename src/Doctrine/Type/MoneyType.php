<?php

declare(strict_types=1);

namespace App\Doctrine\Type;

use App\Money\Money;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;

final class MoneyType extends Type
{
    public const string NAME = 'money';

    private const int LENGTH = 32;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = self::LENGTH;

        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof Money) {
            throw InvalidType::new($value, self::NAME, ['null', Money::class]);
        }

        return $value->amount . ' ' . $value->currency;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Money
    {
        if ($value === null || $value instanceof Money) {
            return $value;
        }

        if (!is_string($value)) {
            throw InvalidType::new($value, self::NAME, ['null', 'string']);
        }

        if (!preg_match('/^(-?\d+) ([A-Z]{3})$/', $value, $m)) {
            throw ValueNotConvertible::new($value, self::NAME, 'expected "<minorUnits> <currency>"');
        }

        return new Money((int) $m[1], $m[2]);
    }
}
