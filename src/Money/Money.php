<?php

declare(strict_types=1);

namespace App\Money;

final class Money
{
    public function __construct(
        public readonly int $amount,      // minor units (cents)
        public readonly string $currency, // ISO 4217
    ) {
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount
            && $this->currency === $other->currency;
    }

    public function __toString(): string
    {
        return $this->amount . ' ' . $this->currency;
    }
}
