<?php

namespace App\Models\Keuangan;

use App\Models\Cso\Booking;
use App\Models\Keuangan\TypePayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayaran';

    protected $guarded = ['id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function typepayment()
    {
        return $this->belongsTo(TypePayment::class, 'payment_id');
    }
}
