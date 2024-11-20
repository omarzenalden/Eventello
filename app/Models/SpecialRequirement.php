<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialRequirement extends Model
{

    protected $fillable=
    [
        'food',
        'decor',
        'photographer',
        'chairs',
        'max_value',
        'lighting',
        'dj',
        'car',
        'the_band',
        'Additions',
        'comments',
    ];
    use HasFactory;

    // Model relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function Requirement()//done
    {
        return $this->belongsTo(Requirement::class);
    }

}
