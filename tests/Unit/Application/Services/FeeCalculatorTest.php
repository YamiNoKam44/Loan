<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Test\Unit\Application\Services;

use Exception;
use Money\Money;
use OutOfBoundsException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Application\Services\FeeCalculator;
use PragmaGoTech\Interview\Application\Services\FeeInterpolator;
use PragmaGoTech\Interview\Application\Services\FeeRounder;
use PragmaGoTech\Interview\Domain\Entities\Breakpoint;
use PragmaGoTech\Interview\Domain\Model\LoanProposal;
use PragmaGoTech\Interview\Infrastructure\Persistence\FeeBreakpoints;

class FeeCalculatorTest extends TestCase
{
    /** @var FeeBreakpoints|MockObject */
    private readonly FeeBreakpoints|MockObject $feeBreakpoints;

    protected function setUp(): void
    {
        $this->feeBreakpoints = $this->createMock(FeeBreakpoints::class);
    }

    public function testIsInstantiable(): void
    {
        $this->assertInstanceOf(FeeCalculator::class, new FeeCalculator(
            $this->feeBreakpoints,
            new FeeInterpolator(),
            new FeeRounder())
        );
    }

    public function testCalculateWillThrowBreakpointsNotFound(): void
    {
        $this->feeBreakpoints->method('getBreakpointsByTerm')
            ->willThrowException(new Exception('test'));

        $feeCalculator = new FeeCalculator(
            $this->feeBreakpoints,
            new FeeInterpolator(),
            new FeeRounder()
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Breakpoints not found.');

        $feeCalculator->calculate(new LoanProposal(12, Money::PLN(1200)));
    }

    #[DataProvider('outOfBoundDataProvider')]
    public function testCalculateWillThrowOutOfBound(int $amount): void
    {
        $this->feeBreakpoints->method('getBreakpointsByTerm')
            ->willReturn([
                new Breakpoint(Money::PLN(19000), Money::PLN(380)),
                new Breakpoint(Money::PLN(20000), Money::PLN(400))
            ]);
        $feeCalculator = new FeeCalculator(
            $this->feeBreakpoints,
            new FeeInterpolator(),
            new FeeRounder()
        );

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Loan amount out of bounds.");

        $fee = $feeCalculator->calculate(new LoanProposal(12, Money::PLN($amount)));
    }

    #[DataProvider('successfulDataProvider')]
    public function testCalculateSuccessful(int $loanAmount, int $exceptedFee): void
    {
        $this->prepareMockData();
        $feeCalculator = new FeeCalculator(
            $this->feeBreakpoints,
            new FeeInterpolator(),
            new FeeRounder()
        );

        $fee = $feeCalculator->calculate(new LoanProposal(12, Money::PLN($loanAmount)));

        $this->assertEquals(Money::PLN($exceptedFee), $fee);
    }

    /**
     * @return void
     */
    public function prepareMockData(): void
    {
        $this->feeBreakpoints->method('getBreakpointsByTerm')
            ->willReturn([
                new Breakpoint(Money::PLN(1000), Money::PLN(100)),
                new Breakpoint(Money::PLN(3000), Money::PLN(150)),
                new Breakpoint(Money::PLN(5000), Money::PLN(200)),
                new Breakpoint(Money::PLN(7500), Money::PLN(225)),
                new Breakpoint(Money::PLN(10000), Money::PLN(240)),
                new Breakpoint(Money::PLN(15000), Money::PLN(250)),
                new Breakpoint(Money::PLN(17000), Money::PLN(300)),
                new Breakpoint(Money::PLN(18500), Money::PLN(350)),
                new Breakpoint(Money::PLN(19000), Money::PLN(380)),
                new Breakpoint(Money::PLN(20000), Money::PLN(400))
            ]);
    }

    public static function outOfBoundDataProvider(): array
    {
        return [
            [1500],
            [50000]
        ];
    }


    public static function successfulDataProvider(): array
    {
        return [
            [1500, 115],
            [5200, 205],
            [7300, 225],
            [12500, 245],
            [14585, 250],
            [19995, 400],

        ];
    }
}
