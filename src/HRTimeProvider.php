<?php

declare(strict_types=1);

namespace thomasmeschke\phpstopwatch;

interface HRTimeProvider
{
    /**
     * @return array{int, int}|int|float|false
     */
    public function hrtime(bool $asNumber = false): array|int|float|false;
}
