<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Score extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entry_id',
        'handicap_before',
        'handicap_after',
        'allowance',
        'match_points',
        'match_points_adjusted',
        'bonus_points',
        'league_points',
    ];

    protected $casts = [
        'entry_id'              => 'integer',
        'handicap_before'       => 'integer',
        'handicap_after'        => 'integer',
        'allowance'             => 'integer',
        'match_points'          => 'integer',
        'match_points_adjusted' => 'integer',
        'bonus_points'          => 'integer',
        'league_points'         => 'integer',
    ];

    // Relationships ----

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function matchResult(): HasOne
    {
        return $this->hasOne(MatchResult::class);
    }
}
