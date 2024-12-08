<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
    ];

    // Attributes ----

    public function initials(): Attribute
    {
        return Attribute::get(fn() => strtoupper($this['first_name'][0] . $this['last_name'][0]));
    }
}
