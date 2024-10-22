<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Application\Services;

use Exception;
use Money\Money;
use OutOfBoundsException;
use PragmaGoTech\Interview\Domain\Entities\Breakpoint;
use PragmaGoTech\Interview\Domain\Model\LoanProposal;
use PragmaGoTech\Interview\Domain\Repository\FeeBreakpointsRepository;
use PragmaGoTech\Interview\Domain\Services\FeeCalculator as FeeCalculatorInterface;

readonly class FeeCalculator implements FeeCalculatorInterface
{
    public function __construct(private FeeBreakpointsRepository $breakpoints,
                                private FeeInterpolator $interpolator,
                                private FeeRounder $rounder)
    {
    }

    /**
     * @throws Exception
     */
    public function calculate(LoanProposal $application): Money
    {
        try {
            $breakpoints = $this->breakpoints->getBreakpointsByTerm($application->term());
        } catch (\Throwable $exception) {
            throw new Exception('Breakpoints not found.');
        }

        $lowerBreakpoint = null;
        $upperBreakpoint = null;

        /** @var Breakpoint $breakpoint */
        foreach ($breakpoints as $breakpoint) {
            $amount = $breakpoint->getAmount();
            if ($amount->equals($application->amount())) {
                return $breakpoint->getFee();
            }

            if ($amount->lessThan($application->amount())) {
                $lowerBreakpoint = $breakpoint;
                continue;
            }

            if ($amount->greaterThan($application->amount()) && !$upperBreakpoint) {
                $upperBreakpoint = $breakpoint;
                break;
            }
        }

        if ($lowerBreakpoint && $upperBreakpoint) {
            $fee = $this->interpolator->interpolate($application->amount(), $lowerBreakpoint, $upperBreakpoint);

            return $this->rounder->roundUpToNearestRoundedNumber($fee->add($application->amount()))->subtract($application->amount());
        }

        throw new OutOfBoundsException("Loan amount out of bounds.");
    }
}
