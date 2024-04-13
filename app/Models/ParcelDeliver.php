<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelDeliver extends Model
{
    use HasFactory;

    public function parcel()
    {
        return $this->belongsTo(Parcel::class, "parcel_no", "parcel_no");
    }

}
