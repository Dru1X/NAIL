<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handicap extends Model
{
    public $timestamps = false;

    protected $casts = [
        'number'          => 'integer',
        'match_score'     => 'integer',
        'match_allowance' => 'integer',
        'set_score'       => 'integer',
        'set_allowance'   => 'integer',
    ];
}
