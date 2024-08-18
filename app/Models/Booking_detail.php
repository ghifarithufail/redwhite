<?php

namespace App\Models;

use App\Models\Spj;
use App\Models\Armada;
use App\Models\Booking;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking_detail extends Model
{
    use HasFactory;

    public function bookings()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function armadas()
    {
        return $this->belongsTo(Armada::class, 'armada_id', 'id');
    }

    public function spjs()
    {
        return $this->belongsTo(Spj::class, 'id', 'booking_detail_id');
    }

    public function pengemudis(){
        return $this->belongsTo(Pengemudi::class, 'supir_id', 'id');
    }

    public function kondekturs(){
        return $this->belongsTo(Kondektur::class, 'kondektur_id', 'id');
    }
}
