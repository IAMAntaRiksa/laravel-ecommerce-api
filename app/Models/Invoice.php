<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice', 'customer_id', 'courier', 'courier_service',
        'courier_cost', 'weight', 'name', 'phone', 'city_id', 'province_id',
        'address', 'status', 'grand_total', 'snap_token',
    ];

    public function orders()
    {
        $this->hasMany(Order::class);
    }
    public function customer()
    {
        $this->belongsTo(Customer::class);
    }

    public function city()
    {
        $this->belongsTo(City::class, 'city_id', 'city_id');
    }
    public function province()
    {
        $this->belongsTo(City::class, 'province_id', 'province_id');
    }
}