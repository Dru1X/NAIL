<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stage_id',
        'entry_id',
        'handicap',
        'matches_played',
        'matches_won',
        'matches_drawn',
        'matches_lost',
        'match_points',
        'match_points_adjusted',
        'bonus_points',
        'league_points',
    ];

    protected $casts = [
        'stage_id'              => 'integer',
        'entry_id'              => 'integer',
        'handicap'              => 'integer',
        'matches_played'        => 'integer',
        'matches_won'           => 'integer',
        'matches_drawn'         => 'integer',
        'matches_lost'          => 'integer',
        'match_points'          => 'integer',
        'match_points_adjusted' => 'integer',
        'bonus_points'          => 'integer',
        'league_points'         => 'integer',
    ];

    // Relationships ----

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}
