<?php

namespace App\Models;

use Database\Factories\MarketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'location', 'days', 'farmers_count', 'distance'])]
class Market extends Model
{
    /** @use HasFactory<MarketFactory> */
    use HasFactory;

    public function farmers(): HasMany
    {
        return $this->hasMany(Farmer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['status', 'reviewed_by', 'reviewed_at']);
    }
}
