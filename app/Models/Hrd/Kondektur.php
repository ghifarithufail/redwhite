<?php

namespace App\Models\Hrd;

use App\Models\Spj;
use App\Models\User;
use App\Models\Admin\Pool;
use App\Models\Admin\Rute;
use App\Models\Cso\Booking;
use App\Models\Hrd\Biodata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kondektur extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // protected $fillable = ['nokondektur', 'user_id', 'rute_id', 'pool_id', 'tgl_masuk', 'tanggal_kp', 'nojamsostek', 'status', 'ket_kondektur'];

    public static function generateNokondektur()
    {
        $twoDigitYear = date('y');
        $month = date('m');
        $lastNokondektur = self::orderBy('id', 'desc')->value('Nokondektur');

        if ($lastNokondektur) {
            $lastNokondekturNumber = (int) substr($lastNokondektur, -4);
            $newNokondekturNumber = $lastNokondekturNumber + 1;
        } else {
            $newNokondekturNumber = 1;
        }

        $newNokondektur = "K2" . $twoDigitYear . $month . str_pad($newNokondekturNumber, 4, '0', STR_PAD_LEFT);
        return $newNokondektur;
    }

    public function getPoolName($poolId)
    {
        $pool = Pool::find($poolId);

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
        return $this->belongsTo(Biodata::class, 'biodata_id', 'nik');
    }

    public function pools()
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'id');
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
