<?php

namespace App\Models\Cso;

use Carbon\Carbon;
use App\Models\Spj;
use App\Models\Admin\Armada;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use App\Models\Keuangan\Pembayaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $guarded = ['id'];

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class, 'booking_id', 'id');
    }

    public function users(){
        return $this->belongsTo(User::class, "user_id", 'id');
    }


    public function armada()
    {
        return $this->belongsToMany(Armada::class, 'booking_details', 'booking_id', 'armada_id');
    }

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class, 'tujuan_id', 'id');
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
        return $this->hasMany(Spj::class, 'booking_detail_id', 'id');
    }

    public function generateBookingNumber()
    {
        $lastBooking = self::where('no_booking', 'LIKE', 'PP/WST/%/%/%')
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastBooking ? (int) substr($lastBooking->no_booking, -1) : 0;
        $newNumber = $lastNumber + 1;

        $year = Carbon::now()->format('y'); // Two-digit year
        $monthRoman = $this->convertToRoman(Carbon::now()->month); // Convert current month to Roman numeral

        return sprintf('PP/WST/%s/%s/%d', $year, $monthRoman, $newNumber);
    }

    private function convertToRoman($number)
    {
        $array_bln = [
            1 => "I", 2 => "II", 3 => "III", 4 => "IV", 5 => "V",
            6 => "VI", 7 => "VII", 8 => "VIII", 9 => "IX", 10 => "X",
            11 => "XI", 12 => "XII"
        ];

        return $array_bln[$number] ?? '';
    }

    // mutator untuk mengatur format tanggal date_start ke d-m-Y
    public function setDateStartAttribute($value)
    {
        $this->attributes['date_start'] = Carbon::createFromFormat('Y-m-d', $value)->format('d-m-Y');
    }

    // mutator untuk mengatur format tanggal date_end ke d-m-Y
    public function setDateEndAttribute($value)
    {
        $this->attributes['date_end'] = Carbon::createFromFormat('Y-m-d', $value)->format('d-m-Y');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'booking_id', 'id');
    }
}
