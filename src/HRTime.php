<?php

declare(strict_types=1);

namespace thomasmeschke\phpstopwatch;

final class HRTime
{
    private function __construct(
        private int $seconds,
        private int $nanoseconds
    ) {
    }

    public static function get(?HRTimeProvider $hrTimeProvider = null): HRTime
    {
        $hrTimeProvider ??= new SystemTimeProvider();
        $now = $hrTimeProvider->hrtime();
        return new self(...$now);
    }

    public function seconds(): int
    {
        return $this->seconds;
    }

    public function nanoseconds(): int
    {
        return $this->nanoseconds;
    }

    public function asNumber(): int
    {
        return ($this->seconds * (10 ** 9)) + $this->nanoseconds;
    }

    public static function fromNumber(int $number): HRTime
    {
        $nanoseconds = $number % (10 ** 9);
        $seconds = (int)($number / (10 ** 9));
        return new self($seconds, $nanoseconds);
    }

    public function __toString(): string
    {
        return sprintf(
            "%d.%'09d",
            $this->seconds,
            $this->nanoseconds
        );
    }
}
