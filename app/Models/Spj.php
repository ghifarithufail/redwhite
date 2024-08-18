<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spj extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function pengemudis()
    {
        return $this->belongsTo(Pengemudi::class, 'supir_id', 'id');
    }

    public function kondekturs()
    {
        return $this->belongsTo(Kondektur::class, 'kondektur_id', 'id');
    }

    public function booking_details()
    {
        return $this->belongsTo(Booking_detail::class, 'booking_detail_id', 'id');
    }

    public function bookingDetails()
    {
        return $this->hasMany(Booking_detail::class, 'booking_id', 'booking_id');
    }

    public function user_in()
    {
        return $this->belongsTo(User::class, 'user_masuk', 'id');
    }

    public function user_out()
    {
        return $this->belongsTo(User::class, 'user_keluar', 'id');
    }
}
