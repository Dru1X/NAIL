<?php

namespace App\Enums;

enum StageType: string
{
    case League = 'league';
    case Playoff = 'playoff';

    // Helpers ----

    public function defaultCapacity(): int
    {
        return match ($this) {
            StageType::League  => 24,
            StageType::Playoff => 8,
        };
    }
}
