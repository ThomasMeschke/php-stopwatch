<?php

declare(strict_types=1);

namespace thomasmeschke\phpstopwatch\tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use thomasmeschke\phpstopwatch\SystemTimeProvider;

#[CoversClass(SystemTimeProvider::class)]
class SystemTimeProviderTest extends TestCase
{
    protected SystemTimeProvider $sut;

    protected function setUp(): void
    {
        $this->sut = new SystemTimeProvider();
    }

    public function test_hrtime_returnsMatchingResult(): void
    {
        [$expectedSecondsMin, $expectedNanosecondsMin] = hrtime();
        [$actualSeconds, $actualNanoseconds] = $this->sut->hrtime();

        $this->assertGreaterThanOrEqual($expectedSecondsMin, $actualSeconds);
        $this->assertGreaterThanOrEqual($expectedNanosecondsMin, $actualNanoseconds);
    }
}