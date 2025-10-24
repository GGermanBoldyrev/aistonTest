<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    /** @use HasFactory<\Database\Factories\TechnicianFactory> */
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email'];

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class);
    }
}
