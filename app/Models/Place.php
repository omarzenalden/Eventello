<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'address',
        'capacity',
        'start_work',
        'end_work',

    ];
    public function planners(): BelongsToMany
    {
        return $this->belongsToMany(Planner::class ,'planner_place', 'place_id','planner_id');
    }
    public function rates()//done
    {
        return $this->hasMany(rate::class,'place_id');
    }
    public function Pending_requests()//done
    {
        return $this->hasMany(Pending_request::class,'Pending_request_id');
    }
    public function images()
{
    return $this->hasMany(Image::class);
}
    public function events(){
        return $this->belongsToMany(event::class);
    }
}
