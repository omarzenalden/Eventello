<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Requirement;
use App\Models\EventsHasPlaces;
class Event extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'budget',
        'status',
    ];

    use HasFactory;
    public function rates()//done
    {
        return $this->hasMany(rate::class,'event_id');
    }
    public function planners()
{
    return $this->belongsToMany(Planner::class, 'event_planner', 'event_id', 'planner_id');
}

//////req->one to one->event
    public function requirements()//done
{
    return $this->belongsTo(requirement::class);
}
public function users()//done
    {
        return $this->belongsTo(User::class, 'user_id');
    }
public function companies()//done
    {
        return $this->belongsTo(Company::class, 'user_id');
    }

    public function places()
    {
        return $this->belongsToMany(place::class);
    }
    public function Pending_requests()//done
    {
        return $this->hasMany(Pending_request::class,'Pending_request_id');
    }
}
