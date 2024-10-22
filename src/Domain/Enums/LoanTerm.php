<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Enums;

enum LoanTerm: int
{
    case ONE_YEAR_IN_MONTH = 12;
    case TWO_YEAR_IN_MONTH = 24;
}

