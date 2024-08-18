<?php

namespace App\Models\Admin;

use App\Models\Spj;
use App\Models\Cso\Booking;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Armada extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

//     public function bookings()
// {
//     return $this->hasManyThrough(Booking::class, BookingDetail::class);
// }

    public function bookingDetails()
    {
        return $this->hasMany(\App\Models\Cso\BookingDetail::class, 'booking_id','armada_id', 'id');
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
        return $this->belongsTo(Pengemudi::class, 'pengemudi_id', 'id');
    }

    public function kondekturs(){
        return $this->belongsTo(Kondektur::class, 'kondektur_id', 'id');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_details', 'armada_id', 'booking_id');
    }
}
