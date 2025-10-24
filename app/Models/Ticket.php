<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'number',
        'pharmacy_id',
        'topic',
        'priority_id',
        'category_id',
        'technician_id',
        'status_id',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
