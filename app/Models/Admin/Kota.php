<?php

namespace App\Models\Admin;

use App\Models\Admin\Provinsi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kota extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function provinsis()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'id');
    }
}
