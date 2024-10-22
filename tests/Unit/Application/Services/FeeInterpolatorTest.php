<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Test\Unit\Application\Services;

use Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Application\Services\FeeInterpolator;
use PragmaGoTech\Interview\Domain\Entities\Breakpoint;

class FeeInterpolatorTest extends TestCase
{
    public function testIsInstantiable(): void
    {
        $this->assertInstanceOf(FeeInterpolator::class, new FeeInterpolator());
    }

    #[DataProvider('interpolateDataProvider')]
    public function testInterpolate(Money $loanAmount, Breakpoint $lowerBreakpoint,
                                    Breakpoint $upperBreakpoint, int $resultAmount): void
    {
        $interpolator = new FeeInterpolator();

        $interpolatedFee = $interpolator->interpolate($loanAmount, $lowerBreakpoint, $upperBreakpoint);

        $this->assertEquals(Money::PLN($resultAmount), $interpolatedFee);
    }

    public static function interpolateDataProvider(): array
    {
        return [
            [
                Money::PLN(1200),
                new Breakpoint(Money::PLN(1000), Money::PLN(100)),
                new Breakpoint(Money::PLN(2000), Money::PLN(120)),
                104
            ],
            [
                Money::PLN(5000),
                new Breakpoint(Money::PLN(1000), Money::PLN(100)),
                new Breakpoint(Money::PLN(20000), Money::PLN(200)),
                121
            ],
            [
                Money::PLN(3000),
                new Breakpoint(Money::PLN(10), Money::PLN(1)),
                new Breakpoint(Money::PLN(10000), Money::PLN(55)),
                17
            ],
            [
                Money::PLN(13370),
                new Breakpoint(Money::PLN(2137), Money::PLN(100)),
                new Breakpoint(Money::PLN(42069), Money::PLN(1000)),
                353
            ],

        ];
    }
}
