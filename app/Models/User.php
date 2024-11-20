<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable implements JWTSubject{

    use Notifiable;


   // use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    protected $guard = ["api"];
    protected $fillable = [
        'name',
        'email',
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

    public function orders()//done
    {
        return $this->hasOne(Order::class);
    }
    public function events()//done
    {
        return $this->hasMany(Event::class);
    }
    public function planners()//done
    {
        return $this->belongsTo(planner::class);
    }
    public function companies()//done
    {
        return $this->belongsTo(Company::class,);
    }
    public function requirement()//done
    {
        return $this->hasone(requirement::class,);
    }
    public function rates()//done
    {
        return $this->hasMany(rate::class,'user_id');
    }
    public function guest()//done
    {
        return $this->hasMany(guest::class,'user_id');
    }

    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }
    public function Pending_requests()//done
    {
        return $this->hasone(Pending_request::class,'Pending_request_id');
    }
   
    }

