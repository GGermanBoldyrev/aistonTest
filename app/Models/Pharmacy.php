<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    /** @use HasFactory<\Database\Factories\PharmacyFactory> */
    use HasFactory;

    protected $fillable = ['code', 'address', 'city'];

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }
}
