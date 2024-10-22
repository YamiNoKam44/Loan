<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Repository;

interface FeeBreakpointsRepository
{
    public function getBreakpointsByTerm(int $term): iterable;
}
