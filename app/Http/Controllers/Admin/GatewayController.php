<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function index()
    {
        $gateways = Gateway::orderByDesc('created_at')->paginate(15);
        return view('admin.gateways.index', compact('gateways'));
    }

    public function create()
    {
        return view('admin.gateways.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateGateway($request);
        Gateway::create($validated);

        flash()->success('Gateway created successfully.');

        return redirect()->route('admin.gateways.index');
    }

    public function edit(Gateway $gateway)
    {
        return view('admin.gateways.edit', compact('gateway'));
    }

    public function update(Request $request, Gateway $gateway)
    {
        $validated = $this->validateGateway($request);
        $gateway->update($validated);

        flash()->success('Gateway updated successfully.');

        return redirect()->route('admin.gateways.index');
    }

    public function destroy(Gateway $gateway)
    {
        $gateway->delete();

        flash()->success('Gateway deleted successfully.');

        return redirect()->route('admin.gateways.index');
    }

    public function toggle(Gateway $gateway)
    {
        $gateway->is_active = ! $gateway->is_active;
        $gateway->save();

        flash()->success('Gateway status updated successfully.');

        return redirect()->route('admin.gateways.index');
    }

    // Compatibility: filtered views for legacy routes
    public function payment()
    {
        $gateways = Gateway::where('type', 'payment')->orderByDesc('created_at')->paginate(15);
        return view('admin.gateways.index', compact('gateways'));
    }

    public function withdrawal()
    {
        $gateways = Gateway::where('type', 'withdrawal')->orderByDesc('created_at')->paginate(15);
        return view('admin.gateways.index', compact('gateways'));
    }

    private function validateGateway(Request $request): array
    {
        $data = $request->validate([
            'type' => 'required|in:payment,withdrawal',
            'name' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'rate_usdt' => 'required|numeric|min:0',
            'charge_type' => 'required|in:fixed,percent',
            'charge_value' => 'required|numeric|min:0',
            'min_limit' => 'nullable|numeric|min:0',
            'max_limit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'qr_path' => 'nullable|string|max:255',
            'logo_path' => 'nullable|string|max:255',
            'requires_txn_id' => 'sometimes|boolean',
            'requires_screenshot' => 'sometimes|boolean',
            'custom_fields' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        // Normalize custom_fields coming from hidden inputs so that DB always
        // stores a clean array of objects instead of JSON strings.
        if (array_key_exists('custom_fields', $data)) {
            $normalized = [];

            if (is_array($data['custom_fields'])) {
                foreach ($data['custom_fields'] as $value) {
                    if ($value === null || $value === '') {
                        continue;
                    }

                    // Each element is usually a JSON string created in the
                    // edit/create view. Decode once, and if the result is
                    // still a JSON string, try decoding again to handle
                    // existing double-encoded values.
                    $decoded = json_decode($value, true);

                    if (is_string($decoded)) {
                        $second = json_decode($decoded, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($second)) {
                            $decoded = $second;
                        }
                    }

                    if (is_array($decoded)) {
                        // Ensure required flag is a real boolean
                        if (isset($decoded['required']) && ! is_bool($decoded['required'])) {
                            $decoded['required'] = in_array($decoded['required'], [true, 'true', 1, '1'], true);
                        }

                        $normalized[] = $decoded;
                    }
                }
            }

            $data['custom_fields'] = $normalized;
        }

        return $data;
    }
}
