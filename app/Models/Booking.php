<?php

namespace App\Models;

use App\Models\Spj;
use App\Models\Cso\Tujuan;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function payments()
    {
        return $this->belongsTo(Payment::class, 'id', 'booking_id');
    }

    public function details()
    {
        return $this->hasMany(Booking_detail::class, 'booking_id', 'id');
    }

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class, 'tujuan_id', 'id');
    }

    public function tujuans()
    {
        $tujuanIds = explode(',', $this->tujuan_id);
        return Tujuan::whereIn('id', $tujuanIds)->get();
    }

    public function bookingDetails()
    {
        return $this->hasMany(Booking_detail::class, 'booking_id', 'id');
    }

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id', 'id');
    }

    public function pengemudi()
    {
        return $this->belongsTo(Pengemudi::class, 'pengemudi_id', 'id');
    }

    public function kondektur()
    {
        return $this->belongsTo(Kondektur::class, 'kondektur_id', 'id');
    }

    public function spjs()
    {
        return $this->hasMany(Spj::class, 'booking_detail_id', 'id'); // Disesuaikan menjadi hasMany
    }

}
