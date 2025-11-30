<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderSet;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['orderSet', 'platform', 'orderProducts.product']);

        // Search by order_id
        if ($search = $request->string('search')->toString()) {
            $query->where('order_id', 'like', "%{$search}%");
        }

        // Filter by type
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Filter by order set
        if ($orderSetId = $request->input('order_set_id')) {
            $query->where('order_set_id', $orderSetId);
        }

        // Filter by platform
        if ($platformId = $request->input('platform_id')) {
            $query->where('platform_id', $platformId);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $orders = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $platforms = Platform::orderBy('name')->get();
        $orderSets = OrderSet::orderBy('name')->get();

        return view('admin.orders.index', compact('orders', 'platforms', 'orderSets'));
    }

    public function create()
    {
        $platforms = Platform::orderBy('name')->get();
        $orderSets = OrderSet::orderBy('name')->get();
        return view('admin.orders.create', compact('platforms', 'orderSets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_set_id' => ['required', 'exists:order_sets,id'],
            'type' => ['required', 'in:single,combo'],
            'platform_id' => ['required', 'exists:platforms,id'],
            'profit_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        // Generate unique order ID
        $data['order_id'] = $this->generateOrderId();

        $order = Order::create([
            'order_set_id' => $data['order_set_id'],
            'type' => $data['type'],
            'order_id' => $data['order_id'],
            'platform_id' => $data['platform_id'],
            'profit_percentage' => $data['profit_percentage'],
        ]);

        // Attach products
        foreach ($data['products'] as $product) {
            $order->orderProducts()->create([
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        $order->load(['orderProducts.product', 'platform', 'orderSet']);
        $platforms = Platform::orderBy('name')->get();
        $orderSets = OrderSet::orderBy('name')->get();
        return view('admin.orders.edit', compact('order', 'platforms', 'orderSets'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'order_set_id' => ['required', 'exists:order_sets,id'],
            'type' => ['required', 'in:single,combo'],
            'platform_id' => ['required', 'exists:platforms,id'],
            'profit_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        $order->update([
            'order_set_id' => $data['order_set_id'],
            'type' => $data['type'],
            'platform_id' => $data['platform_id'],
            'profit_percentage' => $data['profit_percentage'],
            'is_active' => $data['is_active'] ?? $order->is_active,
        ]);

        // Delete old products and add new ones
        $order->orderProducts()->delete();
        foreach ($data['products'] as $product) {
            $order->orderProducts()->create([
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function toggle(Request $request, Order $order)
    {
        $order->is_active = !$order->is_active;
        $order->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => $order->is_active]);
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order ' . ($order->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function getProducts(Request $request)
    {
        $platformId = $request->input('platform_id');
        $products = Product::where('platform_id', $platformId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        return response()->json($products);
    }

    private function generateOrderId(): string
    {
        do {
            $orderId = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::where('order_id', $orderId)->exists());

        return $orderId;
    }
}
