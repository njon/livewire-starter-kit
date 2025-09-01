@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => __('Users'), 'asset' => __('Users'), 'buttons' => [
        ['new' => true, 'link' => route('users.create')]
    ]])
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0">{{ __('Users') }}</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        @if(auth()->user()->super_admin)
                            <th>{{ __('Owner ID') }}</th>
                        @endif
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <h6 class="mb-0">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-decoration-none">
                                    {{ $user->name }}
                                </a>
                            </h6>
                        </td>
                        <td>{{ $user->email }}</td>
                        @if(auth()->user()->super_admin)
                            <td>
                                @if($user->owner_id)
                                    <a href="{{ route('users.edit', $user->owner_id) }}" class="text-decoration-none">
                                        {{ $user->owner_id }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        @endif
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                            <i class="bi bi-pencil me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
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
@endsection
