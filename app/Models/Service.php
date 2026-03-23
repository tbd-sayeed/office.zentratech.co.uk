<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'client_id',
        'service_type_id',
        'project_type_id',
        'service_name',
        'total_amount',
        'paid_amount',
        'currency',
        'start_date',
        'notes',
        'is_active',
        'domain_name',
        'hosting_package',
        'expiration_date',
        'provider_name',
        'credentials',
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
        'discount' => 'decimal:2',
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

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function projectType(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class, 'project_type_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    public function teamAssignments(): HasMany
    {
        return $this->hasMany(ServiceTeamAssignment::class);
    }

    public function teamMembers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'service_team_assignments')
            ->withPivot('agreed_amount', 'notes')
            ->withTimestamps();
    }

    public function teamMemberPayments(): HasMany
    {
        return $this->hasMany(TeamMemberPayment::class);
    }

    public function getNetAmountAttribute(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->discount);
    }

    public function getDueAmountAttribute(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->discount - (float) $this->paid_amount);
    }

    /** Sum of agreed amounts to team members (converted to service currency for profit calc). */
    public function getTeamCostAttribute(): float
    {
        $total = 0;
        foreach ($this->teamAssignments as $a) {
            $total += CurrencyHelper::toBase((float) $a->agreed_amount, $a->currency ?? 'USD');
        }
        return $total;
    }

    /**
     * ZentraTech profit = Client agreed (net) - Team cost.
     * When no team member assigned or no agreed amount = project done by ZentraTech, profit = full net.
     */
    public function getProfitAttribute(): float
    {
        $netGbp = CurrencyHelper::toBase((float) $this->net_amount, $this->currency ?? 'GBP');
        return max(0, $netGbp - $this->team_cost);
    }

    public function getProfitInServiceCurrencyAttribute(): float
    {
        return CurrencyHelper::fromBase($this->profit, $this->currency ?? 'GBP');
    }
}
