@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => __('Stores'), 'asset' => __('Stores'), 'buttons' => [
        ['new' => true, 'link' => route('stores.create')]
    ]])
@endsection

@section('content')

  <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h3 class="h5 mb-0">{{ __('Store Locations') }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="storesTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Services') }}</th>
                                    @if(auth()->user()->super_admin)
                                        <th>{{ __('Owner ID') }}</th>
                                    @endif
                                    <th>{{ __('Hours') }}</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $store)
                                @php
                                    $workingHours = $store->working_hours ? json_decode($store->working_hours, true) : [
                                        "monday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
                                        "tuesday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
                                        "wednesday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
                                        "thursday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
                                        "friday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
                                        "saturday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
                                        "sunday" => ["active" => true, "open" => "10:00", "close" => "18:00"]
                                    ];
                                @endphp
                                <tr>
                                    <td>
                                        <div class="ratio ratio-1x1 bg-light rounded" style="width: 60px;">
                                            @if($store->thumbnail)
                                                <img src="{{ $store->thumbnail->getUrl('thumb') }}" class="img-cover" alt="{{ $store->name }}">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center text-muted">
                                                    <i class="bi bi-shop" style="font-size: 1.5rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">
                                            <a href="{{ route('stores.edit', $store->id) }}" class="text-decoration-none">
                                                {{ $store->name }}
                                            </a>
                                        </h6>
                                        <!-- <small class="text-muted">{{ $store->handle }}</small> -->
                                    </td>
                                    <td>
                                        {{ $store->address }}
                                    </td>
                                    <!-- <td>
                                        @foreach($languages as $language)
                                            @if(isset($store->attribute_data['url'][$language->code]))
                                                <a href="{{ $store->attribute_data['url'][$language->code] }}" target="_blank" class="text-decoration-none">
                                                   {!! lang_icon($language->code) !!} {{ $store->attribute_data['url'][$language->code] }}
                                                </a>
                                                @if(!$loop->last)<br> @endif
                                            @endif
                                        @endforeach
                                    </td> -->
                                    <td>
                                        @if($store->email)
                                            {{ $store->email }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($store->phone)
                                            {{ $store->phone }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($store->products as $service)
                                            <span class="badge">
                                                <a href="{{ route('admin.products.edit', $service->id) }}" target="_blank" class="d-block text-truncate text-decoration-none text-black" style="width:150px;">
                                                    <i class="bi bi-tag me-2 text-black"></i>{{ $service->translateAttribute('name') }}
                                                </a>
                                            </span>
                                        @endforeach
                                    </td>
                                    @if(auth()->user()->super_admin)
                                        <td>
                                            @if($store->owner_id)
                                                <a href="{{ route('users.edit', $store->owner_id) }}" class="text-decoration-none">
                                                    {{ $store->owner_id }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <button class="btn btn-sm btn-success btn-floating shadow-sm rounded-circle m-0" data-bs-toggle="modal" data-bs-target="#hoursModal-{{ $store->id }}" title="View Hours">
                                            <i class="bi bi-clock"></i>
                                        </button>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle m-0" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('stores.edit', $store->id) }}">
                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('stores.destroy', $store->id) }}" method="POST" class="d-inline">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<!-- Modals should be placed outside the main table -->
@foreach($stores as $store)
@php
    $workingHours = $store->working_hours ? json_decode($store->working_hours, true) : [
        "monday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "tuesday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "wednesday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "thursday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "friday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "saturday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "sunday" => ["active" => true, "open" => "10:00", "close" => "18:00"]
    ];
@endphp
<div class="modal fade" id="hoursModal-{{ $store->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $store->name }} Working Hours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Status</th>
                                <th>Opening Time</th>
                                <th>Closing Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <tr>
                                <td class="text-capitalize">{{ $day }}</td>
                                <td>
                                    @if($workingHours[$day]['active'])
                                        <span class="badge bg-success">Open</span>
                                    @else
                                        <span class="badge bg-secondary">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($workingHours[$day]['active'])
                                        {{ $workingHours[$day]['open'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($workingHours[$day]['active'])
                                        {{ $workingHours[$day]['close'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection