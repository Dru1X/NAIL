<?php

namespace App\Models;

use App\Enums\StageType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'competition_id',
        'name',
        'type',
        'capacity',
        'starts_on',
        'ends_on',
    ];

    protected $casts = [
        'competition_id' => 'integer',
        'type'           => StageType::class,
        'capacity'       => 'integer',
        'starts_on'      => 'immutable_date',
        'ends_on'        => 'immutable_date',
    ];

    // Attributes ----

    public function period(): Attribute
    {
        return Attribute::get(fn() => $this->starts_on->toPeriod($this->ends_on));
    }

    // Relationships ----

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class)
            ->orderBy('starts_on')
            ->orderBy('id');
    }

    public function standings(): HasMany
    {
        return $this->hasMany(Standing::class)
            ->orderByDesc('total_points')
            ->orderByDesc('bonus_points')
            ->orderByDesc('matches_won')
            ->orderByDesc('match_points_adjusted')
            ->orderBy('id');
    }
}
