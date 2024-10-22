<?php
declare(strict_types=1);

namespace PragmaGoTech\Interview\Test\Unit\Application\Services;

use Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Application\Services\FeeRounder;

class FeeRounderTest extends TestCase
{
    public function testIsInstantiable(): void
    {
        $this->assertInstanceOf(FeeRounder::class, new FeeRounder());
    }

    #[DataProvider('dataProviderRoundedTo')]
    public function testRoundedTo(int $baseAmount, int $roundedUpAmount): void
    {
        $feeRounder = new FeeRounder();
        $rounded = $feeRounder->roundUpToNearestRoundedNumber(Money::PLN($baseAmount));

        $this->assertEquals(Money::PLN($roundedUpAmount), $rounded);
    }

    public static function dataProviderRoundedTo(): array
    {
        return [
            [
                100, 100
            ],
            [
                101, 105
            ],
            [
                256, 260
            ],
            [
                11, 15
            ],
            [
                2137, 2140
            ],
        ];
    }
}
