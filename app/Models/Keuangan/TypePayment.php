<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePayment extends Model
{
    use HasFactory;

    protected $table = 'type_payments';

    public function pemabayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }


}
