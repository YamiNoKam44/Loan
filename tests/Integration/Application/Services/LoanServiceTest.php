<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Test\Integration\Application\Services;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use Money\Money;
use OutOfBoundsException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Application\Services\FeeCalculator;
use PragmaGoTech\Interview\Application\Services\FeeInterpolator;
use PragmaGoTech\Interview\Application\Services\FeeRounder;
use PragmaGoTech\Interview\Application\Services\LoanService;
use PragmaGoTech\Interview\Domain\Model\LoanProposal;
use PragmaGoTech\Interview\Infrastructure\Persistence\FeeBreakpoints;

class LoanServiceTest extends TestCase
{

    public function testIsInstantiable(): void {
        $this->assertInstanceOf(LoanService::class, new LoanService(
            new FeeCalculator(
                new FeeBreakpoints(),
                new FeeInterpolator(),
                new FeeRounder()
            )));
    }

    #[NoReturn] #[DataProvider('successfulData')]
    public function testSuccessfulLoan(int $term, int $amount, int $fee): void
    {
        $loanService = new LoanService(new FeeCalculator(
            new FeeBreakpoints(),
            new FeeInterpolator(),
            new FeeRounder())
        );

        $loan = $loanService->createLoan(new LoanProposal($term, Money::PLN($amount)));

        $this->assertEquals($fee, (int) $loan->getFee()->getAmount());
    }

    #[NoReturn] #[DataProvider('throwableData')]
    public function testServiceWillThrowException(
        int $term, int $amount, string $exceptionClass, string $exceptionMessage
    ): void
    {
        $loanService = new LoanService(new FeeCalculator(
                new FeeBreakpoints(),
                new FeeInterpolator(),
                new FeeRounder())
        );

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
        $loanService->createLoan(new LoanProposal($term, Money::PLN($amount)));
    }

    public static function successfulData(): array
    {
        return [
            [
                24, 11500, 460
            ],
            [
                12, 19250, 385
            ],
            [
                12, 5000, 100
            ],
            [
                24, 20000, 800
            ]
        ];
    }

    public static function throwableData(): array
    {
        return [
            [
                    11, 2499, Exception::class, 'Breakpoints not found'
            ],
            [
                    27, 2137, Exception::class, 'Breakpoints not found'
            ],
            [
                    12, 900, OutOfBoundsException::class, 'Loan amount out of bounds.'
            ],
            [
                    24, 1500900, OutOfBoundsException::class, 'Loan amount out of bounds.'
            ],
        ];
    }
}
