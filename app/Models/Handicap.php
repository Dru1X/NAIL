<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Handicap extends Model
{
    public $timestamps = false;

    protected $casts = [
        'number'          => 'integer',
        'match_score'     => 'integer',
        'match_allowance' => 'integer',
        'set_score'       => 'integer',
        'set_allowance'   => 'integer',
    ];

    // Scopes ----

    /**
     * Find appropriate handicaps for the given match score
     */
    public function scopeForMatchScore(Builder $query, int $score): Builder
    {
        return $query->where('match_score', '<=', $score);
    }

    /**
     * Find appropriate handicaps for the given set score
     */
    public function scopeForSetScore(Builder $query, int $score): Builder
    {
        return $query->where('set_score', '<=', $score);
    }
}
