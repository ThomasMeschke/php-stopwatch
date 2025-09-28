<?php

declare(strict_types=1);

namespace thomasmeschke\phpstopwatch\tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use thomasmeschke\phpstopwatch\HRTime;
use thomasmeschke\phpstopwatch\Stopwatch;
use thomasmeschke\phpstopwatch\SystemTimeProvider;

#[CoversClass(Stopwatch::class)]
#[UsesClass(HRTime::class)]
#[UsesClass(SystemTimeProvider::class)]
class StopwatchTest extends TestCase
{
    protected TestStopwatch $sut;

    protected function setUp(): void
    {
        $this->sut = new TestStopwatch();
    }

    public function test_start_setsRunningToTrue(): void
    {
        $this->sut->start();

        $this->assertTrue($this->sut->IsRunning());
    }

    public function test_start_setsStartTime(): void
    {
        $hrTime = HRTime::get();

        $this->sut->start();

        $startTime = HRTime::fromNumber($this->sut->getStartTimestamp());
        $this->assertGreaterThanOrEqual($hrTime->seconds(), $startTime->seconds());
        $this->assertGreaterThanOrEqual($hrTime->nanoseconds(), $startTime->nanoseconds());
    }

    public function test_start_doesNothing_WhenAlreadyStarted(): void
    {
        $this->sut->start();
        $originalStartTime = $this->sut->getStartTimestamp();

        $this->sut->start();
        $actualStartTime = $this->sut->getStartTimestamp();

        $this->assertEquals($originalStartTime, $actualStartTime);
        $this->assertTrue($this->sut->isRunning());
    }

    public function test_startNew_returnsRunningStopwatch(): void
    {
        $result = Stopwatch::startNew();

        $this->assertTrue($result->IsRunning());
    }

    public function test_startNew_canReturnDerivedClass(): void
    {
        $result = TestStopwatch::startNew();

        $this->assertTrue(is_a($result, TestStopwatch::class));
    }

    public function test_stop_doesNothing_whenNotStarted(): void
    {
        $this->sut->stop();

        $this->assertEquals(0, $this->sut->getElapsed());
    }

    public function test_stop_setsElapsed_whenStarted(): void
    {
        $this->sut->start();
        $this->sut->stop();

        $this->assertGreaterThan(0, $this->sut->getElapsed());
    }

    public function test_stop_setsIsRunningToFalse(): void
    {
        $this->sut->start();
        $this->sut->stop();

        $this->assertFalse($this->sut->isRunning());
    }

    public function test_stop_setsElapsedToZero_WhenBelowZero(): void
    {
        $now = HRTime::get()->asNumber();
        $tenSeconds = 10 * (10 ** 9); // ten seconds in nanoseconds
        $fakedStartTime = $now + $tenSeconds;
        $this->sut->start();
        $this->sut->setStartTimestamp($fakedStartTime);
        $this->sut->stop();

        $this->assertEquals(0, $this->sut->elapsed()->asNumber());
    }

    public function test_stopStartStop_addsUpElapsedTimes(): void
    {
        $this->sut->start();
        $this->sut->stop();
        $first = $this->sut->elapsed();

        $this->sut->start();
        $this->sut->stop();
        $second = $this->sut->elapsed();

        $this->assertGreaterThan($first, $second);
    }

    public function test_restart_setsNewStartTimestamp(): void
    {
        $this->sut->start();
        $firstStart = $this->sut->getStartTimestamp();

        $this->sut->restart();
        $secondStart = $this->sut->getStartTimestamp();

        $this->assertGreaterThan($firstStart, $secondStart);
    }

    public function test_restart_resetsElapsed(): void
    {
        $this->sut->start();
        $this->sut->stop();
        $this->sut->restart();

        $this->assertEquals(0, $this->sut->getElapsed());
    }

    public function test_toString_returnsZeros_whenNotStarted(): void
    {
        $result = $this->sut->__toString();

        $this->assertEquals('0.000000000', $result);
    }

    public function test_toString_returnsMatchingString_whenElapsedNotZero(): void
    {
        $this->sut->setElapsed(123456789987654321);
        $result = $this->sut->__toString();

        $this->assertEquals('123456789.987654321', $result);
    }
}

class TestStopwatch extends Stopwatch
{
    public function setStartTimestamp(int $startTimestamp): void
    {
        $this->startTimestamp = $startTimestamp;
    }

    public function getStartTimestamp(): int
    {
        return $this->startTimestamp;
    }

    public function setElapsed(int $elapsed): void
    {
        $this->elapsed = $elapsed;
    }

    public function getElapsed(): int
    {
        return $this->elapsed;
    }
}