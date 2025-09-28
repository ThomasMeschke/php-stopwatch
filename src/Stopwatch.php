<?php

declare(strict_types=1);

namespace thomasmeschke\phpstopwatch;

class Stopwatch
{
    protected int $elapsed;
    protected int $startTimestamp;
    protected bool $isRunning;

    public function __construct()
    {
        $this->Reset();
    }

    public function start(): void
    {
        if (! $this->isRunning()) {
            $this->startTimestamp = $this->getTimestamp()->asNumber();
            $this->isRunning = true;
        }
    }

    public static function startNew(): Stopwatch
    {
        $stopwatch = new static();
        $stopwatch->start();

        return $stopwatch;
    }

    public function stop(): void
    {
        if (! $this->isRunning) {
            return;
        }

        $now = $this->getTimestamp()->asNumber();
        $elapsedThisPeriod = $now - $this->startTimestamp;
        $this->elapsed += $elapsedThisPeriod;
        $this->isRunning = false;

        if ($this->elapsed < 0) {
            $this->elapsed = 0;
        }
    }

    public function reset(): void
    {
        $this->elapsed = 0;
        $this->isRunning = false;
        $this->startTimestamp = 0;
    }

    public function restart(): void
    {
        $this->elapsed = 0;
        $this->startTimestamp = $this->getTimestamp()->asNumber();
        $this->isRunning = true;
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function elapsed(): HRTime
    {
        return HRTime::fromNumber($this->elapsed);
    }

    public function __toString(): string
    {
        return (string)$this->elapsed();
    }

    protected function getTimestamp(): HRTime
    {
        return HRTime::get();
    }
}
