<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Services;

use Exception;
use PragmaGoTech\Interview\Application\DTO\Loan;
use PragmaGoTech\Interview\Domain\Model\LoanProposal;

readonly class LoanService
{
    public function __construct(private FeeCalculator $feeCalculator)
    {
    }

    /**
     * @throws Exception
     */
    public function createLoan(LoanProposal $loanProposal): Loan
    {
        $fee = $this->feeCalculator->calculate($loanProposal);

        return new Loan($loanProposal->amount(), $loanProposal->term(), $fee);
    }
}
