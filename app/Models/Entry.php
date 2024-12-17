<?php

namespace App\Models;

use App\Enums\BowStyle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'competition_id',
        'person_id',
        'bow_style',
        'initial_handicap',
        'current_handicap',
    ];

    protected $casts = [
        'competition_id'   => 'integer',
        'person_id'        => 'integer',
        'bow_style'        => BowStyle::class,
        'initial_handicap' => 'integer',
        'current_handicap' => 'integer',
    ];

    // Relationships ----

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function standings(): HasMany
    {
        return $this->hasMany(Standing::class);
    }
}
