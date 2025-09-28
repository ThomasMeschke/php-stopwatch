<?php

declare(strict_types=1);

namespace thomasmeschke\phpstopwatch;

class SystemTimeProvider implements HRTimeProvider
{
    public function hrtime(bool $asNumber = false): array|int|float|false
    {
        return \hrtime($asNumber);
    }
}
