<?php

namespace App\Http\Controllers;

use App\Models\UserOrder;
use App\Models\UserOrderSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all orders with relationships
        $orders = UserOrder::with(['userOrderSet.orderSet', 'productPackageItem.product'])
            ->whereHas('userOrderSet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('user.orders.index', compact('orders'));
    }
}