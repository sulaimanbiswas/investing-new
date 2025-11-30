<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('platform');

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

        $products = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $platforms = Platform::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'platforms'));
    }

    public function create()
    {
        $platforms = Platform::orderBy('name')->get();

        return view('admin.products.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        Product::create($data);

        flash()->success('Product created successfully.');

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        $platforms = Platform::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'platforms'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);

        $product->update($data);

        flash()->success('Product updated successfully.');

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        flash()->success('Product deleted successfully.');

        return redirect()->route('admin.products.index');
    }

    public function toggle(Request $request, Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        $message = 'Product ' . ($product->is_active ? 'activated' : 'deactivated') . ' successfully.';

        // If this is an AJAX / JSON request, keep returning JSON for dynamic updates.
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => $product->is_active,
                'message' => $message
            ]);
        }

        flash()->success($message);

        // Regular form submission: redirect back to index with flash message.
        return redirect()->route('admin.products.index');
    }

    protected function validateProduct(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'platform_id' => ['required', 'exists:platforms,id'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
