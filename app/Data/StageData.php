<?php

namespace App\Data;

use App\Enums\StageType;
use Carbon\CarbonImmutable;

readonly class StageData
{
    public function __construct(
        public StageType       $type,
        public CarbonImmutable $startsOn,
        public CarbonImmutable $endsOn,
    ) {}
}
