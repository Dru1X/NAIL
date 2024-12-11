<?php

namespace App\Models;

use App\Enums\StageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'type'      => StageType::class,
        'capacity'  => 'int',
        'starts_at' => 'immutable_date',
        'ends_at'   => 'immutable_date',
    ];

    // Relationships ----

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
