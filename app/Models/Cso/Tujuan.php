<?php

namespace App\Models\Cso;

use App\Models\TypeArmada;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tujuan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function booking()
    {
        return $this->belongsTo(\App\Models\Cso\Booking::class, 'booking_id');
    }

    public function typearmadas()
    {
        return $this->belongsTo(TypeArmada::class, 'type_bus', 'id');
    }


}
