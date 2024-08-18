<?php

namespace App\Models\Cso;

use Carbon\Carbon;
use App\Models\Spj;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi dengan model BookingDetail
    public function bookingDetails()
    {
        return $this->hasMany(\App\Models\Cso\BookingDetail::class, 'booking_id', 'id');
    }

    public function armadas()
    {
        return $this->belongsToMany(\App\Models\Admin\Armada::class, 'booking_details', 'booking_id', 'armada_id');
    }
    public function armada()
    {
        return $this->belongsTo(\App\Models\Admin\Armada::class, 'armada_id', 'id');
    }

    public function tujuan() {
        return $this->belongsTo(\App\Models\Cso\Tujuan::class, 'tujuan_id', 'id');
    }

    public function pengemudi() {
        return $this->belongsTo(\App\Models\Hrd\Pengemudi::class, 'pengemudi_id', 'id');
    }

    public function kondektur() {
        return $this->belongsTo(\App\Models\Hrd\Kondektur::class, 'kondektur_id', 'id');
    }

    public function spjs()
    {
        return $this->belongsTo(Spj::class, 'id', 'booking_detail_id');
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

    public static function generateInitialBookingNumber()
    {
        $year = Carbon::now()->format('y'); // Two-digit year
        $monthRoman = self::convertToRoman(Carbon::now()->month); // Convert current month to Roman numeral

        return sprintf('PP/WST/%s/%s/1', $year, $monthRoman);
    }

    private static function convertToRoman($number)
    {
        $array_bln = [
            1 => "I", 2 => "II", 3 => "III", 4 => "IV", 5 => "V",
            6 => "VI", 7 => "VII", 8 => "VIII", 9 => "IX", 10 => "X",
            11 => "XI", 12 => "XII"
        ];

        return $array_bln[$number] ?? '';
    }
}
