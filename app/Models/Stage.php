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
        'starts_at'      => 'immutable_date',
        'ends_at'        => 'immutable_date',
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
        return $this->hasMany(Round::class);
    }
}
