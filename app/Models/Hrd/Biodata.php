<?php

namespace App\Models\Hrd;

use App\Models\User;
use App\Models\Admin\Kota;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Biodata extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kotas()
    {
        return $this->belongsTo(Kota::class, 'kota_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function pengemudis(): BelongsTo
    {
        return $this->belongsTo(Pengemudi::class);
    }

    public function kondekturs(): BelongsTo
    {
        return $this->belongsTo(Kondektur::class);
    }
}
