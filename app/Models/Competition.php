<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'entries_open_on',
        'entries_close_on',
        'starts_on',
        'ends_on',
    ];

    protected function casts(): array
    {
        return [
            'entries_open_on'  => 'immutable_date',
            'entries_close_on' => 'immutable_date',
            'starts_on'        => 'immutable_date',
            'ends_on'          => 'immutable_date',
        ];
    }

    // Relationships ----

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }
}
