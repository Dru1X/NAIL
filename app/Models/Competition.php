<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'entries_open_at',
        'entries_close_at',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'entries_open_at'  => 'immutable_datetime',
            'entries_close_at' => 'immutable_datetime',
            'starts_at'        => 'immutable_datetime',
            'ends_at'          => 'immutable_datetime',
        ];
    }
}
