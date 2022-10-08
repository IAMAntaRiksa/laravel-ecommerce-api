<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_customer');
    }
    public function index()
    {
        $carts = Cart::with('product')
            ->where('customer_id', auth()->guard('api_admin')->user()->id)->latest()->get();

        return new CartResource(true, 'List Data Cart : ' . auth()->guard('api_customer')->user()->name . '', $carts);
    }
}