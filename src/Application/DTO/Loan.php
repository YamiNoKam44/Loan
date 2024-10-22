<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\DTO;

use Money\Money;

readonly class Loan
{

    public function __construct(
        private Money $loanAmount,
        private int   $term,
        private Money $fee
    )
    {
    }

    public function getLoanAmount(): Money
    {
        return $this->loanAmount;
    }

    public function getTerm(): int
    {
        return $this->term;
    }

    public function getFee(): Money
    {
        return $this->fee;
    }


    public function toArray(): array
    {
        return [
            'loan_amount' => $this->loanAmount->getAmount(),
            'term' => $this->term,
            'fee' => $this->fee->getAmount(),
        ];
    }
}
