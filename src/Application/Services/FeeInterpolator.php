<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Services;

use PragmaGoTech\Interview\Domain\Entities\Breakpoint;
use Money\Money;

readonly class FeeInterpolator
{
    public function interpolate(Money $loanAmount, Breakpoint $lowerBreakpoint, Breakpoint $upperBreakpoint): Money
    {
        $amountRange = $upperBreakpoint->getAmount()->subtract($lowerBreakpoint->getAmount());
        $feeRange = $upperBreakpoint->getFee()->subtract($lowerBreakpoint->getFee());
        $position = $loanAmount->subtract($lowerBreakpoint->getAmount())
            ->ratioOf($amountRange);

        return $lowerBreakpoint->getFee()->add($feeRange->multiply($position));
    }
}
