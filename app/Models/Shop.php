<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function shopnamechanges()
    {
        return $this->hasMany(NameChangeRequest::class, "shop_id")->where('type', "shop")->orderBy('id', 'desc');
    }

    public function nameChange()
    {
        return $this->hasOne(NameChangeRequest::class, "shop_id")->where('type', "shop")->latest();
    }
}

