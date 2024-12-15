<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    // Attributes ----

    public function entryPeriod(): Attribute
    {
        return Attribute::get(fn() => $this->entries_open_on->toPeriod($this->entries_close_on));
    }

    public function period(): Attribute
    {
        return Attribute::get(fn() => $this->starts_on->toPeriod($this->ends_on));
    }

    public function status(): Attribute
    {
        return Attribute::get(fn() => match (true) {
            !$this->period->isStarted()   => 'planning',
            $this->period->isInProgress() => 'ongoing',
            $this->period->isEnded()      => 'ended',
            default                       => 'unknown',
        });
    }

    // Relationships ----

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
