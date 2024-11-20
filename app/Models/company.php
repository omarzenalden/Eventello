<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    use HasFactory;
    protected $table = 'companies';
    //  protected $primaryKey = 'company_id';
    protected $fillable = ['name', 'photo', 'address', 'email', 'phone'];


    public function rates()//done
    {
        return $this->hasMany(rate::class,'company_id');
    }
    public function users()//done
    {
        return $this->hasMany(User::class,'company_id');
    }
    public function planners()//done
    {
        return $this->hasMany(Planner::class,'company_id');
    }
    public function events()//done
    {
        return $this->hasMany(Event::class,'company_id');
    }
}
