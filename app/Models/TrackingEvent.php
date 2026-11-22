<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'location_id',
        'status',
        'is_exception',
        'latitude',
        'longitude',
        'event_time',
        'description',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_exception' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'event_time' => 'datetime',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
