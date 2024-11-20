<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'description',
        'cost_range',
        'status',
        'date',
        'time',
        'max_value',
        'user_orders_idorder',
        'events_idevents',
        'orders_idorder',
        'orders_planner_idplanner',
    ];
    //////req->one to one->event
    public function event()//done
    {
        return $this->hasOne(Event::class);
    }
    public function user()//done
    {
        return $this->belongsTo(Event::class);
    }
    public function order()//done
    {
        return $this->hasOne(Order::class);
    }
    public function Guest()//done
    {
        return $this->hasMany(Guest::class,'requirement_id');
    }
    public function SpecialRequirement()//done
    {
        return $this->hasone(SpecialRequirement::class,'requirement_id');
    }

}
