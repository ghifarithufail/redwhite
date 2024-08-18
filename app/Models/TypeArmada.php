<?php

namespace App\Models;

use App\Models\Armada;
use App\Models\Cso\Tujuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeArmada extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function armadas()
    {
        return $this->hasMany(Armada::class, 'type_id');
    }

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class, 'type_bus','id');
    }
}
