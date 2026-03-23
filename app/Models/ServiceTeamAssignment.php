<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceTeamAssignment extends Model
{
    protected $fillable = ['service_id', 'team_member_id', 'agreed_amount', 'currency', 'notes'];

    protected $casts = ['agreed_amount' => 'decimal:2'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) TeamMemberPayment::where('team_member_id', $this->team_member_id)
            ->where('service_id', $this->service_id)
            ->sum('amount');
    }

    public function getDueAmountAttribute(): float
    {
        return max(0, $this->agreed_amount - $this->paid_amount);
    }
}
