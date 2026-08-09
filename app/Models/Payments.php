<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Payments extends Model
{
    use HasFactory, Notifiable, HasApiTokens;


    protected $fillable = [
        'receiver_phone',
        'receiver_location',
        'transfer_image',
        'order_id',
        'product_id',
        'status',
        'transaction_ref',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function getTransferImageUrlAttribute()
    {
        return $this->transfer_image ? asset('storage/' . $this->transfer_image) : null;
    }

    public function setTransferImageAttribute($value)
    {
        if ($value) {
            $this->attributes['transfer_image'] = $value;
        }
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['transfer_image_url'] = $this->getTransferImageUrlAttribute();
        return $array;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function Customers()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function Orders()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function Products()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

}
