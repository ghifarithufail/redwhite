<?php

namespace App\Models\Cso;

use App\Models\Cso\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id','id');
    }

    // Relasi dengan model Armada
    public function armada()
    {
        return $this->belongsTo(\App\Models\Admin\Armada::class, 'armada_id','id');
    }

    public function armadas()
    {
        return $this->belongsTo(\App\Models\Admin\Armada::class, 'armada_id', 'id');
    }

    // public function armadas()
    // {
    //     return $this->belongsToMany(\App\Models\Admin\Armada::class, 'booking_armada');
    // }

    public function pengemudi()
    {
        return $this->belongsTo(\App\Models\Hrd\Pengemudi::class, 'pengemudi_id');
    }

    public function kondektur()
    {
        return $this->belongsTo(\App\Models\Hrd\Kondektur::class, 'kondektur_id');
    }

    public function spj()
    {
        return $this->belongsTo(\App\Models\Spj::class, 'booking_detail_id');
    }

}
