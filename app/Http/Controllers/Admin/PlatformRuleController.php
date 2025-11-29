<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformRule;
use Illuminate\Http\Request;

class PlatformRuleController extends Controller
{
    public function index(Request $request)
    {
        $query = PlatformRule::query();
        if ($search = $request->string('search')->toString()) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($status = $request->input('status')) {
            if ($status === 'active') $query->where('is_active', true);
            elseif ($status === 'inactive') $query->where('is_active', false);
        }
        $rules = $query->orderBy('sort_by')->orderByDesc('id')->paginate(20)->withQueryString();
        return view('admin.platform-rule.index', compact('rules'));
    }

    public function create()
    {
        return view('admin.platform-rule.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRule($request);
        PlatformRule::create($data);
        return redirect()->route('admin.platform-rule.index')->with('success', 'Rule created successfully.');
    }

    public function edit(PlatformRule $platform_rule)
    {
        return view('admin.platform-rule.edit', ['rule' => $platform_rule]);
    }

    public function update(Request $request, PlatformRule $platform_rule)
    {
        $data = $this->validateRule($request, $platform_rule->id);
        $platform_rule->update($data);
        return redirect()->route('admin.platform-rule.index')->with('success', 'Rule updated successfully.');
    }

    public function destroy(PlatformRule $platform_rule)
    {
        $platform_rule->delete();
        return redirect()->route('admin.platform-rule.index')->with('success', 'Rule deleted successfully.');
    }

    public function toggle(Request $request, PlatformRule $platform_rule)
    {
        $platform_rule->is_active = !$platform_rule->is_active;
        $platform_rule->save();
        if ($request->expectsJson() || $request->ajax()) return response()->json(['status' => $platform_rule->is_active]);
        return redirect()->route('admin.platform-rule.index')->with('success', 'Rule ' . ($platform_rule->is_active ? 'activated' : 'deactivated') . '.');
    }

    protected function validateRule(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_by' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
