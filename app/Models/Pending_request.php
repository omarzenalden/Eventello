<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Pending_request extends Model
{protected $fillable = ['status'];

    use HasFactory;
    public function place()
    {
        return $this->belongsTo(place::class);
    }
    public function event()
    {
        return $this->belongsTo(event::class);
    }
    public function planner()
    {
        return $this->belongsTo(planner::class);
    }
    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function order()//done
    {
        return $this->hasOne(Order::class);
    }


}
