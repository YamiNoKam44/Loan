<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Services;

use Money\Money;

readonly class FeeRounder
{
    private const int ROUNDED_TO = 5;

    public function roundUpToNearestRoundedNumber(Money $amount): Money
    {
        $modulus = $amount->mod(Money::PLN(self::ROUNDED_TO));

        if ($modulus->isZero()) {
            return $amount;
        }

        $difference = Money::PLN(self::ROUNDED_TO)->subtract($modulus);

        return $amount->add($difference);
    }
}
