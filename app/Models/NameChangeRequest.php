<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameChangeRequest extends Model
{
    use HasFactory;


    public function createuser()
    {
        return $this->belongsTo(User::class, "request_id");
    }

    public function processuser()
    {
        return $this->belongsTo(User::class, "process_id");
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, "merchant_id");
    }
}
