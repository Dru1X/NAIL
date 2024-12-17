<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'round_id',
        'left_score_id',
        'right_score_id',
        'winner_id',
        'shot_at',
    ];

    protected $casts = [
        'round_id'       => 'integer',
        'left_score_id'  => 'integer',
        'right_score_id' => 'integer',
        'winner_id'      => 'integer',
        'shot_at'        => 'immutable_datetime',
    ];

    // Attributes ----

    public function hasWinner(): Attribute
    {
        return Attribute::get(fn() => !!$this->winner_id);
    }

    public function isDraw(): Attribute
    {
        return Attribute::get(fn() => !$this->winner_id);
    }

    // Relationships ----

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function leftScore(): BelongsTo
    {
        return $this->belongsTo(Score::class, 'left_score_id');
    }

    public function rightScore(): BelongsTo
    {
        return $this->belongsTo(Score::class, 'right_score_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Entry::class, 'winner_id');
    }

    // Scopes ----

    /** @noinspection PhpUnused */
    public function scopeShotBefore(Builder $query, CarbonInterface $dateTime): Builder
    {
        return $query->where('shot_at', '<', $dateTime);
    }
}
