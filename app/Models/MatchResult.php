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
    public function scopeInCompetition(Builder $query, Competition $competition): Builder
    {
        return $query->whereHas('round', fn(Builder|Round $round) => $round
            ->whereHas('stage', fn(Builder|Stage $stage) => $stage
                ->whereCompetitionId($competition->id)
            )
        );
    }

    /** @noinspection PhpUnused */
    public function scopeInStage(Builder $query, Stage $stage): Builder
    {
        return $query->whereHas('round', fn(Builder|Round $round) => $round->whereStageId($stage->id));
    }

    /** @noinspection PhpUnused */
    public function scopeInRound(Builder $query, Round $round): Builder
    {
        return $query->where('round_id', $round->id);
    }

    /** @noinspection PhpUnused */
    public function scopeShotBefore(Builder $query, CarbonInterface $dateTime): Builder
    {
        return $query->where('shot_at', '<', $dateTime);
    }

    /** @noinspection PhpUnused */
    public function scopeShotBy(Builder $query, Entry $entry): Builder
    {
        return $query->where(fn(Builder|self $match) => $match
            ->whereHas('leftScore', fn(Builder|Score $score) => $score
                ->where('entry_id', $entry->id)
            )
            ->orWhereHas('rightScore', fn(Builder|Score $score) => $score
                ->where('entry_id', $entry->id)
            )
        );
    }

    /** @noinspection PhpUnused */
    public function scopeShotByBoth(Builder $query, Entry $firstEntry, Entry $secondEntry): Builder
    {
        return $query->where(fn(Builder|self $match) => $match
            ->whereHas('leftScore', fn(Builder|Score $score) => $score
                ->where('entry_id', $firstEntry->id)
                ->orWhere('entry_id', $secondEntry->id)
            )
            ->whereHas('rightScore', fn(Builder|Score $score) => $score
                ->where('entry_id', $firstEntry->id)
                ->orWhere('entry_id', $secondEntry->id)
            )
        );
    }
}
