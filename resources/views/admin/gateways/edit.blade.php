@extends('admin.layouts.app')

@section('title', 'Admin | Edit Gateway')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Gateway</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.gateways.update', $gateway) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="payment" @selected($gateway->type === 'payment')>Payment</option>
                                    <option value="withdrawal" @selected($gateway->type === 'withdrawal')>Withdrawal</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $gateway->name }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Currency</label>
                                <input type="text" name="currency" class="form-control" value="{{ $gateway->currency }}"
                                    required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ $gateway->country }}" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Rate (in USDT)</label>
                                <input type="number" step="0.00000001" name="rate_usdt" class="form-control"
                                    value="{{ $gateway->rate_usdt }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Charge Type</label>
                                <select name="charge_type" class="form-select" required>
                                    <option value="fixed" @selected($gateway->charge_type === 'fixed')>Fixed</option>
                                    <option value="percent" @selected($gateway->charge_type === 'percent')>Percent</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Charge Value</label>
                                <input type="number" step="0.00000001" name="charge_value" class="form-control"
                                    value="{{ $gateway->charge_value }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Minimum Limit</label>
                                <input type="number" step="0.00000001" name="min_limit" class="form-control"
                                    value="{{ $gateway->min_limit }}" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Maximum Limit</label>
                                <input type="number" step="0.00000001" name="max_limit" class="form-control"
                                    value="{{ $gateway->max_limit }}" />
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="3">{{ $gateway->description }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $gateway->address }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">QR Path</label>
                                <input type="text" name="qr_path" class="form-control" value="{{ $gateway->qr_path }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requires_txn_id" value="1"
                                        id="requires_txn_id" @checked($gateway->requires_txn_id)>
                                    <label class="form-check-label" for="requires_txn_id">Require Transaction ID</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requires_screenshot" value="1"
                                        id="requires_screenshot" @checked($gateway->requires_screenshot)>
                                    <label class="form-check-label" for="requires_screenshot">Require Screenshot</label>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Custom Fields (JSON)</label>
                                <textarea name="custom_fields" class="form-control"
                                    rows="4">{{ json_encode($gateway->custom_fields, JSON_PRETTY_PRINT) }}</textarea>
                                <small class="text-muted">Supports types: text, textarea, select, checkbox, radio,
                                    file</small>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        id="is_active" @checked($gateway->is_active)>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('admin.gateways.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection