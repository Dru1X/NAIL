<?php

namespace App\Services;

use App\Models\Handicap;

class HandicapService
{
    // Lookup ----

    /**
     * Recalculate a handicap given a match score
     */
    public function recalculateHandicap(Handicap $handicap, int $matchPoints): Handicap
    {
        $achievedHandicap = Handicap::orderBy('number')
            ->whereBowStyle($handicap->bow_style)
            ->forMatchScore($matchPoints)
            ->firstOrFail();

        if($achievedHandicap->number >= $handicap->number) {
            return $handicap;
        }

        $newNumber = (int)round(
            collect([$handicap->number, $achievedHandicap->number])->average()
        );

        return Handicap::whereBowStyle($handicap->bow_style)
            ->whereNumber($newNumber)
            ->firstOrFail();
    }
}
