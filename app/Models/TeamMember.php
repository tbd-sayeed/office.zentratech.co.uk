<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamMember extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'role', 'bank_details', 'notes', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_team_assignments')
            ->withPivot('agreed_amount', 'notes')
            ->withTimestamps();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ServiceTeamAssignment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(TeamMemberPayment::class);
    }

    public function getTotalAgreedAmountAttribute(): float
    {
        return (float) $this->assignments()->sum('agreed_amount');
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getTotalDueAttribute(): float
    {
        return max(0, $this->total_agreed_amount - $this->total_paid);
    }

    /**
     * Profit attributable to this team member (in GBP) – for aggregation/totals.
     * For each service they work on: their share of service profit = (their agreed / total team agreed) * service profit.
     */
    public function getProfitAttribute(): float
    {
        $total = 0;
        foreach ($this->assignments as $assignment) {
            $service = $assignment->service;
            if (!$service) continue;

            $teamCostGbp = 0;
            foreach ($service->teamAssignments as $a) {
                $teamCostGbp += CurrencyHelper::toBase((float) $a->agreed_amount, $a->currency ?? 'USD');
            }
            if ($teamCostGbp <= 0) continue;

            $netGbp = CurrencyHelper::toBase((float) $service->net_amount, $service->currency ?? 'GBP');
            $serviceProfit = max(0, $netGbp - $teamCostGbp);
            $memberCostGbp = CurrencyHelper::toBase((float) $assignment->agreed_amount, $assignment->currency ?? 'USD');
            $total += ($memberCostGbp / $teamCostGbp) * $serviceProfit;
        }
        return $total;
    }

    /**
     * Primary currency for this team member (currency with most agreed amount).
     * Used to display profit in the same currency as their work.
     */
    public function getPrimaryCurrencyAttribute(): string
    {
        $byCur = $this->assignments->groupBy(fn ($a) => $a->currency ?? 'USD')->map(fn ($g) => $g->sum('agreed_amount'));
        if ($byCur->isEmpty()) return 'GBP';
        return $byCur->sortDesc()->keys()->first();
    }

    /**
     * Profit in the team member's primary currency (matches Service list display).
     */
    public function getProfitInPrimaryCurrencyAttribute(): float
    {
        return CurrencyHelper::fromBase($this->profit, $this->primary_currency);
    }
}
