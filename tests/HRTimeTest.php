<?php

declare(strict_types=1);

namespace Thomasmeschke\Stopwatch\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use thomasmeschke\phpstopwatch\HRTime;
use thomasmeschke\phpstopwatch\tests\TestTimeProvider;

#[CoversClass(HRTime::class)]
class HRTimeTest extends TestCase
{
    public function test_get_returnsInitializedInstance(): void
    {
        $expectedSeconds = 123456789;
        $expectedNanoseconds = 987654321;
        $timeProvider = new TestTimeProvider($expectedSeconds, $expectedNanoseconds);

        $sut = HRTime::get($timeProvider);

        $this->assertEquals($expectedSeconds, $sut->seconds());
        $this->assertEquals($expectedNanoseconds, $sut->nanoseconds());
    }

    public function test_asNumber_returnsTotalNanoseconds(): void
    {
        $expectedSeconds = 123456789;
        $expectedNanoseconds = 987654321;
        $expectedNumber = $expectedSeconds * (10 ** 9) + $expectedNanoseconds;

        $timeProvider = new TestTimeProvider($expectedSeconds, $expectedNanoseconds);

        $result = HRTime::get($timeProvider)->asNumber();

        $this->assertEquals($expectedNumber, $result);
    }

    public function test_fromNumber_returnsMatchingInstance(): void
    {
        $expectedSeconds = 123456789;
        $expectedNanoseconds = 987654321;
        $number = $expectedSeconds * (10 ** 9) + $expectedNanoseconds;

        $result = HRTime::fromNumber($number);

        $this->assertEquals($expectedSeconds, $result->seconds());
        $this->assertEquals($expectedNanoseconds, $result->nanoseconds());
    }

    public function test_toString_fillsNanoseconds_WithLeadingZeros(): void
    {
        $expectedSeconds = 0;
        $expectedNanoseconds = 0;
        $timeProvider = new TestTimeProvider($expectedSeconds, $expectedNanoseconds);

        $result = (string)HRTime::get($timeProvider);

        $this->assertEquals('0.000000000', $result);
    }

    public function test_toString_returnsMatchingString(): void
    {
        $expectedSeconds = 42;
        $expectedNanoseconds = 987654321;
        $timeProvider = new TestTimeProvider($expectedSeconds, $expectedNanoseconds);

        $result = (string)HRTime::get($timeProvider);

        $this->assertEquals('42.987654321', $result);
    }
}
