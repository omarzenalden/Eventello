<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_planners extends Model
{protected $fillable = ['image','planner_id'];
    public function planner()
    {
        return $this->belongsTo(planner::class);
    }
    use HasFactory;
}
