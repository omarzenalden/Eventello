<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $primaryKey = 'idguest';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
    public function user()//done
    {
        return $this->belongsTo(User::class);
    }
    public function Requirement()//done
    {
        return $this->belongsTo(Requirement::class);
    }


}


