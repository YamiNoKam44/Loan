<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Services;

use Money\Money;
use PragmaGoTech\Interview\Domain\Model\LoanProposal;

interface FeeCalculator
{
    /**
     * @return Money The calculated total fee.
     */
    public function calculate(LoanProposal $application): Money;
}
