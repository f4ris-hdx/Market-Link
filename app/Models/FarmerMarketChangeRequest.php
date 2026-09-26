<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['farmer_id', 'current_market_id', 'requested_market_id', 'requested_by', 'reviewed_by', 'status', 'reviewed_at'])]
class FarmerMarketChangeRequest extends Model
{
    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function currentMarket(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'current_market_id');
    }

    public function requestedMarket(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'requested_market_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
