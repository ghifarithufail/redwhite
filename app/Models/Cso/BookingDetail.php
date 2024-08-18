<?php

namespace App\Models\Cso;

use App\Models\Spj;
use App\Models\Cso\Booking;
use App\Models\Admin\Armada;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingDetail extends Model
{
    use HasFactory;

    protected $table = 'booking_details';

    protected $guarded = ['id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
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

    public function spj()
    {
        return $this->belongsTo(Spj::class, 'booking_detail_id', 'id');
    }
}
