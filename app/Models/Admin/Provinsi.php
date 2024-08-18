<?php

namespace App\Models\Admin;

use App\Models\Admin\Kota;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provinsi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kotas()
    {
        return $this->belongsTo(Kota::class);
    }

}
