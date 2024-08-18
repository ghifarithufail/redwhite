<?php

namespace App\Models\Admin;

use App\Models\Armada;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'id', 'armada_id');
    }

    public function rutes()
    {
        return $this->belongsTo(Rute::class);
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
