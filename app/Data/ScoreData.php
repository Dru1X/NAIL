<?php

namespace App\Data;

use App\Enums\Side;

readonly class ScoreData
{
    public function __construct(
        public Side $side,
        public int  $entryId,
        public int  $matchPoints,
    ) {}
}
