<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Model;

use Money\Money;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
readonly class LoanProposal
{
    public function __construct(private int $term, private Money $amount)
    {
    }

    /**
     * Term (loan duration) for this loan application
     * in number of months.
     */
    public function term(): int
    {
        return $this->term;
    }

    /**
     * Amount requested for this loan application.
     */
    public function amount(): Money
    {
        return $this->amount;
    }
}
