<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'invoice_number',
        'customer_id',
        'carrier_id',
        'origin_location_id',
        'destination_location_id',
        'transport_mode',
        'cargo_type',
        'weight_kg',
        'volume_cbm',
        'base_cost',
        'weight_volume_cost',
        'insurance_cost',
        'total_amount',
        'payment_method',
        'payment_status',
        'paid_at',
        'payment_confirmed_by',
        'status',
        'expected_departure',
        'expected_arrival',
        'estimated_arrival',
        'delay_hours',
        'delay_severity',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'volume_cbm' => 'decimal:2',
            'base_cost' => 'integer',
            'weight_volume_cost' => 'integer',
            'insurance_cost' => 'integer',
            'total_amount' => 'integer',
            'paid_at' => 'datetime',
            'expected_departure' => 'datetime',
            'expected_arrival' => 'datetime',
            'estimated_arrival' => 'datetime',
            'delay_hours' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    public function originLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_location_id');
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }

    public function currentLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'current_location_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function paymentConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_confirmed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class, 'shipment_id');
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class, 'shipment_id');
    }

    public function exceptions(): HasMany
    {
        return $this->hasMany(ShipmentException::class, 'shipment_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'shipment_id');
    }
}
