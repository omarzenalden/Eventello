<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ShouldQueue;
class Planner extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use HasApiTokens, Notifiable;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    protected $guard = ['planner'];
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'company_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function places()    //: //BelongsToMany
    {
        return $this->belongsToMany(Place::class,'planner_place', 'planner_id', 'place_id');
    }
    public function orders()//done
    {
        return $this->belongsToMany(order::class, 'planners_has_orders', 'planner_id', 'order_id');
    }
    public function events()
{
    return $this->belongsToMany(Event::class, 'event_planner', 'planner_id', 'event_id');
}
public function users()//done
{
    return $this->hasMany(user::class,'planner_id');
}
public function companies()//done
{
    return $this->belongsTo(Company::class,'planner_id');
}


public function rates()//done
    {
        return $this->hasMany(rate::class,'planner_id');
    }
    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }
    public function Pending_requests()//done
    {
        return $this->hasMany(Pending_request::class,'Pending_request_id');
    }
    public function Image_planners()
    {
        return $this->hasOne(Image_planners::class);
    }
}
