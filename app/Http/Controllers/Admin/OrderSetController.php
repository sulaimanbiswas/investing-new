<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderSet;
use App\Models\Platform;
use Illuminate\Http\Request;

class OrderSetController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderSet::with('platform')->withCount('orders');

        if ($search = $request->string('search')->toString()) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($platformId = $request->input('platform_id')) {
            $query->where('platform_id', $platformId);
        }

        $orderSets = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $platforms = Platform::orderBy('name')->get();

        return view('admin.order-sets.index', compact('orderSets', 'platforms'));
    }

    public function create()
    {
        $platforms = Platform::orderBy('name')->get();
        return view('admin.order-sets.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'platform_id' => ['required', 'exists:platforms,id'],
        ]);

        OrderSet::create($data);

        flash()->success('Order set created successfully.');

        return redirect()->route('admin.order-sets.index');
    }

    public function edit(OrderSet $order_set)
    {
        $platforms = Platform::orderBy('name')->get();
        return view('admin.order-sets.edit', ['orderSet' => $order_set, 'platforms' => $platforms]);
    }

    public function update(Request $request, OrderSet $order_set)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'platform_id' => ['required', 'exists:platforms,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $order_set->update($data);

        flash()->success('Order set updated successfully.');

        return redirect()->route('admin.order-sets.index');
    }

    public function destroy(OrderSet $order_set)
    {
        $order_set->delete();

        flash()->success('Order set deleted successfully.');

        return redirect()->route('admin.order-sets.index');
    }

    public function toggle(Request $request, OrderSet $order_set)
    {
        $order_set->is_active = !$order_set->is_active;
        $order_set->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => $order_set->is_active]);
        }

        flash()->success('Order set ' . ($order_set->is_active ? 'activated' : 'deactivated') . ' successfully.');

        return redirect()->route('admin.order-sets.index');
    }
}
