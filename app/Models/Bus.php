<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function booking_details()
    {
        return $this->belongsTo(Booking_detail::class, 'id', 'bus_id');
    }
}
