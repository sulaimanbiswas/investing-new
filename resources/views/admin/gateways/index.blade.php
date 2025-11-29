@extends('admin.layouts.app')

@section('title', 'Admin | Gateways')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Gateways</h4>
                    <a href="{{ route('admin.gateways.create') }}" class="btn btn-primary">Create Gateway</a>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Currency</th>
                                    <th>Rate (USDT)</th>
                                    <th>Charge</th>
                                    <th>Limits</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gateways as $gateway)
                                    <tr>
                                        <td>{{ $gateway->name }}</td>
                                        <td class="text-capitalize">{{ $gateway->type }}</td>
                                        <td>{{ $gateway->currency }}</td>
                                        <td>{{ $gateway->rate_usdt }}</td>
                                        <td>{{ $gateway->charge_type }}: {{ $gateway->charge_value }}</td>
                                        <td>{{ $gateway->min_limit }} - {{ $gateway->max_limit }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.gateways.toggle', $gateway) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    class="btn btn-sm {{ $gateway->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                    type="submit">
                                                    {{ $gateway->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.gateways.edit', $gateway) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" action="{{ route('admin.gateways.destroy', $gateway) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No gateways found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $gateways->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection