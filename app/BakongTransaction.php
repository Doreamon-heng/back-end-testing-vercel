<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BakongTransaction extends Model
{
    protected $table = 'bakong_transactions';
    protected $fillable = [
        'order_id', 'amount', 'currency', 'status', 'bakong_response', 'metadata'
    ];
    protected $casts = [
        'bakong_response' => 'array',
        'metadata' => 'array',
    ];
}
