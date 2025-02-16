<?php

namespace App\Data;

use Carbon\CarbonImmutable;

readonly class MatchResultData
{
    public function __construct(
        public CarbonImmutable $shotAt,
        public ScoreData       $leftScore,
        public ScoreData       $rightScore,
    ) {}
}
