<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_name',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Helper methods for roles
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Eloquent Relations
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'customer_id');
    }

    public function createdShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'created_by');
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class, 'created_by');
    }

    public function reportedExceptions(): HasMany
    {
        return $this->hasMany(ShipmentException::class, 'reported_by');
    }

    public function resolvedExceptions(): HasMany
    {
        return $this->hasMany(ShipmentException::class, 'resolved_by');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }
}
