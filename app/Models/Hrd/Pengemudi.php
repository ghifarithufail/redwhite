<?php

namespace App\Models\Hrd;

use App\Models\Spj;
use App\Models\User;
use App\Models\Admin\Pool;
use App\Models\Admin\Rute;
use App\Models\Booking_detail;
use App\Models\Cso\Booking;
use App\Models\Hrd\Biodata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengemudi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function generateNopengemudi()
    {
        $twoDigitYear = date('y');
        $month = date('m');
        $lastNopengemudi = self::orderBy('id', 'desc')->value('Nopengemudi');

        if ($lastNopengemudi) {
            $lastNopengemudiNumber = (int) substr($lastNopengemudi, -4);
            $newNopengemudiNumber = $lastNopengemudiNumber + 1;
        } else {
            $newNopengemudiNumber = 1;
        }

        $newNopengemudi = "P2" . $twoDigitYear . $month . str_pad($newNopengemudiNumber, 4, '0', STR_PAD_LEFT);
        return $newNopengemudi;
    }

    public function getPoolName($id)
    {
        $pool = Pool::find($id);

        if ($pool) {
            return response()->json(['nama_pool' => $pool->nama_pool]);
        } else {
            return response()->json(['nama_pool' => 'Pool Tidak Ditemukan'], 404);
        }
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function biodatas()
    {
        return $this->belongsTo(Biodata::class, 'nik', 'nik');
    }

    public function pools()
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'id');
    }

    public function booking_details()
    {
        return $this->belongsTo(Booking_detail::class, 'id', 'supir_id');
    }

    public function rutes()
    {
        return $this->belongsTo(Rute::class, 'rute_id', 'id');
    }

    public function spjs()
    {
        return $this->belongsTo(Spj::class, 'id', 'booking_detail_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
