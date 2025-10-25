<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $fillable = [
        'number',
        'topic',
        'description',
        'user_id',
        'is_warranty_case',
        'pharmacy_id',
        'priority_id',
        'category_id',
        'status_id',
        'technician_id',
    ];

    protected $casts = [
        'is_warranty_case' => 'boolean',
        'reacted_at' => 'datetime',
        'resolved_at' => 'datetime',
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

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}
