<?php

namespace App\Models\Hrd;

use App\Models\User;
use App\Models\Admin\Kota;
use App\Models\Admin\Pool;
use App\Models\Admin\Rute;
use App\Models\Hrd\Biodata;
use App\Models\Admin\Jabatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function generateNoinduk()
    {
        $twoDigitYear = date('y');
        $month = date('m');
        $lastNoinduk = self::orderBy('id', 'desc')->value('noinduk');

        if ($lastNoinduk) {
            $lastNoindukNumber = (int) substr($lastNoinduk, -4);
            $newNoindukNumber = $lastNoindukNumber + 1;
        } else {
            $newNoindukNumber = 1;
        }

        $newNoinduk =  $twoDigitYear . $month . str_pad($newNoindukNumber, 4, '0', STR_PAD_LEFT);
        return $newNoinduk;
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function biodatas()
    {
        return $this->belongsTo(Biodata::class, 'biodata_id', 'nik');
    }

    public function jabatans()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }

    public function pools()
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'id');
    }

    public function kotas()
    {
        return $this->belongsTo(Kota::class, 'kota_id', 'id');
    }

    public function rutes()
    {
        return $this->belongsTo(Rute::class, 'rute_id', 'id');
    }
}
