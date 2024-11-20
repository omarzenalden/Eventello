<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rate extends Model
{
    use HasFactory;
    protected $fillable = [
        'rate',
        'comment',
        'like',
        'event_id',
        'user_id',
        'place_id',
        'planner_id',
    ];
    public function events()//done
    {
        return $this->belongsTo(event::class);
    }
    public function users()//done
    {
        return $this->belongsTo(user::class);
    }
    public function places()//done
    {
        return $this->belongsTo(place::class);
    }
    public function planners()//done
    {
        return $this->belongsTo(planner::class);
    }
    public function Companies()//done
    {
        return $this->belongsTo(Company::class);
    }

}
