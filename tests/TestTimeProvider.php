<?php

declare(strict_types=1);

namespace thomasmeschke\phpstopwatch\tests;

use thomasmeschke\phpstopwatch\HRTimeProvider;

class TestTimeProvider implements HRTimeProvider
{
    public function __construct(
        private int $desiredSeconds = 0,
        private int $desiredNanoseconds = 0
    ){ }

    public function hrtime(bool $asNumber = false): array|int|float|false
    {
        return [
            $this->desiredSeconds,
            $this->desiredNanoseconds
        ];
    }
}