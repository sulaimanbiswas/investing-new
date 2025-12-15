<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductPackage;
use App\Models\OrderSet;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductPackage::with(['orderSet', 'platform', 'productPackageItems.product']);

        // Search by package_id
        if ($search = $request->string('search')->toString()) {
            $query->where('package_id', 'like', "%{$search}%");
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

        $productPackages = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $platforms = Platform::orderBy('name')->get();
        $orderSets = OrderSet::orderBy('name')->get();

        return view('admin.product-packages.index', compact('productPackages', 'platforms', 'orderSets'));
    }

    public function create(Request $request)
    {
        $platforms = Platform::orderBy('name')->get();
        $orderSets = OrderSet::with('platform')->orderBy('name')->get();
        $preSelectedOrderSetId = $request->input('order_set_id');
        return view('admin.product-packages.create', compact('platforms', 'orderSets', 'preSelectedOrderSetId'));
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

        // Generate unique package ID
        $data['package_id'] = $this->generatePackageId();

        $productPackage = ProductPackage::create([
            'order_set_id' => $data['order_set_id'],
            'type' => $data['type'],
            'package_id' => $data['package_id'],
            'platform_id' => $data['platform_id'],
            'profit_percentage' => $data['profit_percentage'],
        ]);

        // Attach products
        foreach ($data['products'] as $product) {
            $productPackage->productPackageItems()->create([
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        flash()->success('Product Package created successfully.');

        // If created from order set manage page, redirect back there
        if ($request->input('redirect_to_order_set')) {
            return redirect()->route('admin.order-sets.manage', $data['order_set_id']);
        }

        return redirect()->route('admin.product-packages.index');
    }

    public function edit(ProductPackage $product_package)
    {
        $product_package->load(['productPackageItems.product', 'platform', 'orderSet']);
        $platforms = Platform::orderBy('name')->get();
        $orderSets = OrderSet::with('platform')->orderBy('name')->get();
        return view('admin.product-packages.edit', ['productPackage' => $product_package, 'platforms' => $platforms, 'orderSets' => $orderSets]);
    }

    public function update(Request $request, ProductPackage $product_package)
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

        // Handle checkbox: if not present in request, set to false
        $data['is_active'] = $request->has('is_active') ? true : false;

        $product_package->update([
            'order_set_id' => $data['order_set_id'],
            'type' => $data['type'],
            'platform_id' => $data['platform_id'],
            'profit_percentage' => $data['profit_percentage'],
            'is_active' => $data['is_active'],
        ]);

        // Delete old products and add new ones
        $product_package->productPackageItems()->delete();
        foreach ($data['products'] as $product) {
            $product_package->productPackageItems()->create([
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        flash()->success('Product Package updated successfully.');

        // If updated from order set manage page, redirect back there
        if ($request->input('redirect_to_order_set')) {
            return redirect()->route('admin.order-sets.manage', $data['order_set_id']);
        }

        return redirect()->route('admin.product-packages.index');
    }

    public function destroy(Request $request, ProductPackage $product_package)
    {
        $orderSetId = $product_package->order_set_id;
        $product_package->delete();

        flash()->success('Product Package deleted successfully.');

        // Check if should redirect to order set manage page
        if ($request->input('from_manage') || $request->header('referer') && str_contains($request->header('referer'), 'order-sets')) {
            return redirect()->route('admin.order-sets.manage', $orderSetId);
        }

        return redirect()->route('admin.product-packages.index');
    }

    public function toggle(Request $request, ProductPackage $product_package)
    {
        $product_package->is_active = !$product_package->is_active;
        $product_package->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => $product_package->is_active]);
        }

        flash()->success('Product Package ' . ($product_package->is_active ? 'activated' : 'deactivated') . ' successfully.');

        return redirect()->route('admin.product-packages.index');
    }

    public function getProducts(Request $request)
    {
        $platformId = $request->input('platform_id');
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perPage = 20;
        $exclude = $request->input('exclude', []);

        $query = Product::where('platform_id', $platformId)
            ->where('is_active', true);

        // Exclude already selected products
        if (!empty($exclude)) {
            $query->whereNotIn('id', $exclude);
        }

        // Search by name
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $query->orderBy('name');

        // Paginate results
        $total = $query->count();
        $products = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get(['id', 'name', 'price']);

        // Format for Select2
        $items = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'text' => $product->name,
                'price' => $product->price
            ];
        });

        return response()->json([
            'items' => $items,
            'has_more' => ($page * $perPage) < $total
        ]);
    }

    private function generatePackageId(): string
    {
        do {
            $packageId = 'PKG-' . strtoupper(Str::random(8));
        } while (ProductPackage::where('package_id', $packageId)->exists());

        return $packageId;
    }
}
