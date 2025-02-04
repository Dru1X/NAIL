<?php

namespace App\Data;

use Carbon\CarbonImmutable;

readonly class CompetitionData
{
    public function __construct(
        public string          $name,
        public CarbonImmutable $entriesOpenOn,
        public CarbonImmutable $entriesCloseOn,
        public StageData       $leagueStage,
        public StageData       $playoffStage,
    ) {}
}
