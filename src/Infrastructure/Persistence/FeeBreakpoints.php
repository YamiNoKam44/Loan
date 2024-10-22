<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure\Persistence;

use Exception;
use Money\Money;
use PragmaGoTech\Interview\Domain\Entities\Breakpoint;
use PragmaGoTech\Interview\Domain\Repository\FeeBreakpointsRepository;
use PragmaGoTech\Interview\Infrastructure\Config\Breakpoints;

class FeeBreakpoints implements FeeBreakpointsRepository
{
    /**
     * @throws Exception
     */
    public function getBreakpointsByTerm(int $term): iterable
    {
        if (!array_key_exists($term, Breakpoints::BREAKPOINTS)) {
            throw new Exception('Breakpoints not found');
        }

        foreach (Breakpoints::BREAKPOINTS[$term] as $breakpoint) {
            yield new Breakpoint(Money::PLN($breakpoint['amount']), Money::PLN($breakpoint['fee']));
        }
    }
}
