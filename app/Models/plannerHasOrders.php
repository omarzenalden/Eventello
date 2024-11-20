<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPlanner extends Model
{
    use HasFactory;

    protected $table = 'order_planner';
    protected $primaryKey = 'id';
    protected $fillable = ['order_id', 'planner_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function planner()
    {
        return $this->belongsTo(Planner::class);
    }
}
