<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_id',
        'qty',
        'price',
        'weight'
    ];

    public function product()
    {
        $this->belongsTo(Product::class);
    }

    public function customer()
    {
        $this->belongsTo(Customer::class);
    }
}