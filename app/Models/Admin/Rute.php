<?php

namespace App\Models\Admin;

use App\Models\Armada;
use App\Models\Admin\Pool;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rute extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_rute',
        'nama_rute',
        'jenis',
        'stdrit',
        'pool_id',
        'status',
    ];

    public function pools()
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'id');
    }

    public function armadas()
    {
        return $this->belongsTo(Armada::class);
    }

    public function pengemudis()
    {
        return $this->belongsTo(Pengemudi::class);
    }

    public function kondekturs()
    {
        return $this->belongsTo(Kondektur::class);
    }

}
