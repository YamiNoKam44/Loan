<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Entities;

use Money\Money;

readonly class Breakpoint
{
    public function __construct(private Money $amount, private Money $fee)
    {
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getFee(): Money
    {
        return $this->fee;
    }
}
