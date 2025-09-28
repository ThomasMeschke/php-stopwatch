<?php

declare(strict_types=1);

namespace thomasmeschke\phpstopwatch\tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use thomasmeschke\phpstopwatch\Stopwatch;

#[CoversNothing]
class UsageTest extends TestCase
{
    public function test_TakeTime(): void
    {
        $sw = new Stopwatch();
        $sw->start();
        time_nanosleep(1, 5000);
        $sw->stop();

        $elapsed = $sw->elapsed();

        $this->assertEquals(1, $elapsed->seconds());
        $this->assertGreaterThanOrEqual(5000, $elapsed->nanoseconds());
    }
}