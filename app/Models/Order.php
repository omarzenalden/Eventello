<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    use HasFactory;
    protected $fillable = [
        'Pending_request_id', 'cost_planner', 'payment_status', 'date_of_expiration',
    ];
    public function requirement()//done
    {
        return $this->belongsTo(Requirement::class);
    }
    public function pending_Request()
    {
        return $this->belongsTo(Pending_request::class, 'pending_request_id');
    }
    protected $primaryKey = 'idorder';
}
