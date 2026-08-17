<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BakongTransaction extends Model
{
    protected $table = "bakong_transactions";

    protected $fillable = [
        "order_id",
        "amount",
        "currency",
        "status",
        "metadata",
    ];

    protected $casts = [
        "amount"   => "decimal:2",
        "metadata" => "array",
    ];

    /**
     * Get the order that owns this transaction.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class);
    }

    /**
     * Get the payments associated with this transaction.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class);
    }
}