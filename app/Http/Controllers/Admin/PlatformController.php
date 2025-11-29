<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::withCount('products')->orderByDesc('created_at')->paginate(15);
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
        return redirect()->route('admin.platforms.index')->with('status', 'Platform created');
    }

    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    public function update(Request $request, Platform $platform)
    {
        $data = $this->validatePlatform($request);
        $platform->update($data);
        return redirect()->route('admin.platforms.index')->with('status', 'Platform updated');
    }

    public function destroy(Platform $platform)
    {
        $platform->delete();
        return redirect()->route('admin.platforms.index')->with('status', 'Platform deleted');
    }

    private function validatePlatform(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'commission' => ['required', 'numeric', 'min:0', 'max:100'],
            'start_price' => ['required', 'numeric', 'min:0'],
            'end_price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
