@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => __('Discounts'), 'asset' => __('Discounts'), 'buttons' => [
        ['new' => true, 'link' => route('admin.discounts.create')]
    ]])
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0">{{ __('Discounts') }}</h3>
        </div>
        <div class="">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Uses') }}</th>
                        <th>{{ __('Starts At') }}</th>
                        <th>{{ __('Ends At') }}</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discounts as $discount)
                    <tr>
                        <td>
                            <h6 class="mb-0">
                                <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="text-decoration-none">
                                    {{ $discount->name }}
                                </a>
                            </h6>
                        </td>
                        <td>
                            @if($discount->data['fixed_value'] === true)
                                {{ $discount->data['fixed_values']['Eur'] ?? 0 }}€
                            @else
                                {{ $discount->data['percentage'] }}%
                            @endif
                        </td>
                        <td>
                            @php
                                $status = $discount->status;
                                $badgeClass = match($status) {
                                    'active' => 'bg-success',
                                    'expired' => 'bg-danger',
                                    'scheduled' => 'bg-warning',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>
                            {{ $discount->uses }}
                            @if($discount->max_uses)
                                / {{ $discount->max_uses }}
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success">
                                {{ $discount->starts_at ? $discount->starts_at->format('M j, Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $discount->ends_at ? $discount->ends_at->format('M j, Y') : 'Indefinitely' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.discounts.edit', $discount->id) }}">
                                            <i class="bi bi-pencil me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-percent me-2"></i> {{ __('No discounts found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection