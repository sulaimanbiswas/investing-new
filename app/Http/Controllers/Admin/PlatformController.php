<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::withCount('products')->orderBy('created_at', 'asc')->paginate(15);
        return view('admin.platforms.index', compact('platforms'));
    }

    public function create()
    {
        return view('admin.platforms.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePlatform($request);
        Platform::create($data);

        flash()->success('Platform created successfully.');

        return redirect()->route('admin.platforms.index');
    }

    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    public function update(Request $request, Platform $platform)
    {
        $data = $this->validatePlatform($request);
        $platform->update($data);

        flash()->success('Platform updated successfully.');

        return redirect()->route('admin.platforms.index');
    }

    public function destroy(Platform $platform)
    {
        $platform->delete();

        flash()->success('Platform deleted successfully.');

        return redirect()->route('admin.platforms.index');
    }

    private function validatePlatform(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'package_name' => ['required', 'string', 'in:vip1,vip2,vip3'],
            'commission' => ['required', 'numeric', 'min:0', 'max:100'],
            'start_price' => ['required', 'numeric', 'min:0'],
            'end_price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
