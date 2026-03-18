<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'client_id',
        'service_type',
        'service_name',
        'total_amount',
        'paid_amount',
        'start_date',
        'notes',
        'is_active',
        'domain_name',
        'hosting_package',
        'expiration_date',
        'provider_name',
        'credentials',
        'project_type',
        'delivery_date',
        'contract_start_date',
        'contract_end_date',
        'custom_service_type',
        'reminder_30_sent',
        'reminder_15_sent',
        'reminder_7_sent',
        'contract_reminder_15_sent',
        'contract_reminder_7_sent',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'start_date' => 'date',
        'expiration_date' => 'date',
        'delivery_date' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'is_active' => 'boolean',
        'reminder_30_sent' => 'boolean',
        'reminder_15_sent' => 'boolean',
        'reminder_7_sent' => 'boolean',
        'contract_reminder_15_sent' => 'boolean',
        'contract_reminder_7_sent' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    public function getDueAmountAttribute(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }
}
